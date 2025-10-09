<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Menampilkan halaman manajemen notifikasi untuk admin
     */
    public function index()
    {
        $authRoles = Auth()->user()->roles_id;
        
        // Hanya admin dan super admin yang bisa akses
        if (!in_array($authRoles, [1])) {
            abort(403, 'Unauthorized access');
        }

        $notifications = Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('menu/admin/notifications/index', [
            'notifications' => $notifications,
            'title' => 'Manajemen Notifikasi'
        ]);
    }

    /**
     * Menampilkan form untuk membuat notifikasi baru
     */
    public function create()
    {
        $authRoles = Auth()->user()->roles_id;
        
        if (!in_array($authRoles, [1])) {
            abort(403, 'Unauthorized access');
        }

        $users = User::where('roles_id', '!=', 1)->get(); // Semua user kecuali admin
        $roles = [
            ['id' => 2, 'name' => 'Pengajar'],
            ['id' => 3, 'name' => 'Siswa']
        ];

        return view('menu/admin/notifications/create', [
            'users' => $users,
            'roles' => $roles,
            'title' => 'Buat Notifikasi'
        ]);
    }

    /**
     * Menyimpan notifikasi baru
     */
    public function store(Request $request)
    {
        $authRoles = Auth()->user()->roles_id;
        
        if (!in_array($authRoles, [1])) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'type' => 'required|in:info,warning,success,error',
            'target_type' => 'required|in:all,role,specific',
            'target_role' => 'required_if:target_type,role',
            'target_users' => 'required_if:target_type,specific|array',
            'target_users.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            $notifications = [];

            if ($request->target_type === 'all') {
                // Broadcast ke semua user kecuali admin
                $users = User::where('roles_id', '!=', 1)->get();
                foreach ($users as $user) {
                    $notifications[] = [
                        'user_id' => $user->id,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => $request->excerpt,
                        'type' => $request->type,
                        'data' => json_encode(['broadcast' => true]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            } elseif ($request->target_type === 'role') {
                // Kirim ke user dengan role tertentu
                $users = User::where('roles_id', $request->target_role)->get();
                foreach ($users as $user) {
                    $notifications[] = [
                        'user_id' => $user->id,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => $request->excerpt,
                        'type' => $request->type,
                        'data' => json_encode(['role_target' => $request->target_role]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            } elseif ($request->target_type === 'specific') {
                // Kirim ke user tertentu
                foreach ($request->target_users as $userId) {
                    $notifications[] = [
                        'user_id' => $userId,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => $request->excerpt,
                        'type' => $request->type,
                        'data' => json_encode(['specific_target' => true]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            // Insert batch notifications
            if (!empty($notifications)) {
                Notification::insert($notifications);
            }

            DB::commit();

            return redirect()->route('admin.notifications.index')
                ->with('success', 'Notifikasi berhasil dikirim ke ' . count($notifications) . ' pengguna');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengirim notifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan notifikasi user yang sedang login
     */
    public function userNotifications()
    {
        $userId = Auth()->id();
        $user = Auth()->user();
        
        $notifications = Notification::where('user_id', $userId)
            ->orWhereNull('user_id') // Broadcast notifications
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Use consistent notifications view for all users
        return view('notifications/user', [
            'notifications' => $notifications,
            'title' => 'Notifikasi Saya'
        ]);
    }

    /**
     * Mark notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where(function($query) {
                $query->where('user_id', Auth()->id())
                      ->orWhereNull('user_id');
            })
            ->first();

        if ($notification && !$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Mark semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        $userId = Auth()->id();
        
        Notification::where('user_id', $userId)
            ->orWhereNull('user_id')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * API untuk mendapatkan notifikasi unread count
     */
    public function getUnreadCount()
    {
        $userId = Auth()->id();
        
        $count = Notification::where(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhereNull('user_id');
        })->where('is_read', false)->count();

        return response()->json(['count' => $count]);
    }

    /**
     * API untuk mendapatkan notifikasi terbaru (untuk real-time)
     */
    public function getLatestNotifications()
    {
        $userId = Auth()->id();
        
        $notifications = Notification::where(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhereNull('user_id');
        })
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        return response()->json($notifications);
    }

    /**
     * Menampilkan detail notifikasi
     */
    public function show($id)
    {
        $authRoles = Auth()->user()->roles_id;
        
        if (!in_array($authRoles, [1])) {
            abort(403, 'Unauthorized access');
        }

        $notification = Notification::with('user')->findOrFail($id);
        
        return response()->json($notification);
    }

    /**
     * Hapus notifikasi
     */
    public function destroy($id)
    {
        $authRoles = Auth()->user()->roles_id;
        
        if (!in_array($authRoles, [1])) {
            abort(403, 'Unauthorized access');
        }

        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus');
    }

    /**
     * Hapus semua notifikasi
     */
    public function destroyAll()
    {
        $authRoles = Auth()->user()->roles_id;
        
        if (!in_array($authRoles, [1])) {
            abort(403, 'Unauthorized access');
        }

        Notification::truncate();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Semua notifikasi berhasil dihapus');
    }
}