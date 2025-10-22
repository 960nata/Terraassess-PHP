<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintReply;
use App\Models\ComplaintAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComplaintManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Complaint::with(['user', 'replies.user', 'resolvedBy', 'attachments']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter berdasarkan prioritas
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $complaints = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'high_priority' => Complaint::where('priority', 'high')->count()
        ];

        return view('admin.complaints.index', [
            'title' => 'Kelola Pengaduan',
            'user' => $user,
            'complaints' => $complaints,
            'stats' => $stats,
            'filters' => [
                'status' => $request->input('status', ''),
                'category' => $request->input('category', ''),
                'priority' => $request->input('priority', ''),
                'search' => $request->input('search', '')
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Complaint $complaint)
    {
        $user = Auth::user();
        
        $complaint->load(['user', 'replies.user', 'resolvedBy', 'attachments', 'replies.attachments']);

        return view('admin.complaints.show', [
            'title' => 'Detail Pengaduan',
            'user' => $user,
            'complaint' => $complaint
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high'
        ]);

        $updateData = [
            'status' => $request->status,
            'priority' => $request->priority
        ];

        // Jika status diubah ke resolved atau closed, set resolved_by dan resolved_at
        if (in_array($request->status, ['resolved', 'closed']) && !$complaint->resolved_by) {
            $updateData['resolved_by'] = Auth::id();
            $updateData['resolved_at'] = now();
        }

        $complaint->update($updateData);

        return redirect()->route('admin.complaints.show', $complaint->id)
            ->with('success', 'Status pengaduan berhasil diperbarui');
    }

    /**
     * Store a reply to the complaint
     */
    public function reply(Request $request, Complaint $complaint)
    {
        $request->validate([
            'message' => 'required|string|min:5',
            'is_internal_note' => 'boolean',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif,pdf,doc,docx'
        ], [
            'message.required' => 'Pesan balasan harus diisi',
            'message.min' => 'Pesan balasan minimal 5 karakter',
            'attachments.*.file' => 'File lampiran tidak valid',
            'attachments.*.max' => 'Ukuran file maksimal 5MB',
            'attachments.*.mimes' => 'Tipe file yang diizinkan: jpg, jpeg, png, gif, pdf, doc, docx'
        ]);

        $reply = ComplaintReply::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_internal_note' => $request->boolean('is_internal_note', false)
        ]);

        // Handle file uploads for reply
        if ($request->hasFile('attachments')) {
            $this->handleFileUploads($request->file('attachments'), null, $reply->id);
        }

        // Jika ini balasan pertama dari admin, ubah status ke in_progress
        if ($complaint->status === 'pending' && !$request->boolean('is_internal_note')) {
            $complaint->update(['status' => 'in_progress']);
        }

        $message = $request->boolean('is_internal_note') 
            ? 'Catatan internal berhasil ditambahkan' 
            : 'Balasan berhasil dikirim';

        return redirect()->route('admin.complaints.show', $complaint->id)
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint $complaint)
    {
        $complaint->delete();

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Pengaduan berhasil dihapus');
    }

    /**
     * Get complaint statistics for dashboard
     */
    public function statistics()
    {
        $stats = [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'high_priority' => Complaint::where('priority', 'high')->count(),
            'today' => Complaint::whereDate('created_at', today())->count(),
            'this_week' => Complaint::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Complaint::whereMonth('created_at', now()->month)->count()
        ];

        return response()->json($stats);
    }

    /**
     * Handle file uploads for complaints and replies
     */
    private function handleFileUploads($files, $complaintId = null, $replyId = null)
    {
        foreach ($files as $file) {
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('complaints', $fileName, 'public');
            
            ComplaintAttachment::create([
                'complaint_id' => $complaintId,
                'complaint_reply_id' => $replyId,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getMimeType(),
                'file_extension' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'uploaded_by' => Auth::id()
            ]);
        }
    }

    /**
     * Download attachment
     */
    public function downloadAttachment(ComplaintAttachment $attachment)
    {
        return $attachment->download();
    }
}