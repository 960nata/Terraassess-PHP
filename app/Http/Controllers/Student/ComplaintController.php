<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintReply;
use App\Models\ComplaintAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $complaints = Complaint::where('user_id', $user->id)
            ->with(['replies.user', 'resolvedBy', 'attachments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.complaints.index', [
            'title' => 'Pengaduan Saya',
            'user' => $user,
            'complaints' => $complaints
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        return view('student.complaints.create', [
            'title' => 'Buat Pengaduan Baru',
            'user' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|in:akademik,fasilitas,bullying,lainnya',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif,pdf,doc,docx'
        ], [
            'category.required' => 'Kategori pengaduan harus dipilih',
            'category.in' => 'Kategori pengaduan tidak valid',
            'subject.required' => 'Judul pengaduan harus diisi',
            'subject.max' => 'Judul pengaduan maksimal 255 karakter',
            'message.required' => 'Isi pengaduan harus diisi',
            'message.min' => 'Isi pengaduan minimal 10 karakter',
            'attachments.*.file' => 'File lampiran tidak valid',
            'attachments.*.max' => 'Ukuran file maksimal 5MB',
            'attachments.*.mimes' => 'Tipe file yang diizinkan: jpg, jpeg, png, gif, pdf, doc, docx'
        ]);

        $complaint = Complaint::create([
            'user_id' => Auth::id(),
            'category' => $request->category,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
            'priority' => 'medium'
        ]);

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $this->handleFileUploads($request->file('attachments'), $complaint->id);
        }

        return redirect()->route('student.complaints.show', $complaint->id)
            ->with('success', 'Pengaduan berhasil dikirim. Tim admin akan segera menindaklanjuti.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Complaint $complaint)
    {
        $user = Auth::user();
        
        // Pastikan hanya pemilik yang bisa melihat
        if ($complaint->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini');
        }

        $complaint->load(['replies.user', 'resolvedBy', 'attachments', 'replies.attachments']);

        return view('student.complaints.show', [
            'title' => 'Detail Pengaduan',
            'user' => $user,
            'complaint' => $complaint
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Complaint $complaint)
    {
        $user = Auth::user();
        
        // Pastikan hanya pemilik yang bisa edit
        if ($complaint->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini');
        }

        // Hanya bisa edit jika status masih pending
        if ($complaint->status !== 'pending') {
            return redirect()->route('student.complaints.show', $complaint->id)
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat diedit');
        }

        return view('student.complaints.edit', [
            'title' => 'Edit Pengaduan',
            'user' => $user,
            'complaint' => $complaint
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Complaint $complaint)
    {
        $user = Auth::user();
        
        // Pastikan hanya pemilik yang bisa update
        if ($complaint->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini');
        }

        // Hanya bisa update jika status masih pending
        if ($complaint->status !== 'pending') {
            return redirect()->route('student.complaints.show', $complaint->id)
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat diedit');
        }

        $request->validate([
            'category' => 'required|in:akademik,fasilitas,bullying,lainnya',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ], [
            'category.required' => 'Kategori pengaduan harus dipilih',
            'category.in' => 'Kategori pengaduan tidak valid',
            'subject.required' => 'Judul pengaduan harus diisi',
            'subject.max' => 'Judul pengaduan maksimal 255 karakter',
            'message.required' => 'Isi pengaduan harus diisi',
            'message.min' => 'Isi pengaduan minimal 10 karakter'
        ]);

        $complaint->update([
            'category' => $request->category,
            'subject' => $request->subject,
            'message' => $request->message
        ]);

        return redirect()->route('student.complaints.show', $complaint->id)
            ->with('success', 'Pengaduan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint $complaint)
    {
        $user = Auth::user();
        
        // Pastikan hanya pemilik yang bisa hapus
        if ($complaint->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini');
        }

        // Hanya bisa hapus jika status masih pending
        if ($complaint->status !== 'pending') {
            return redirect()->route('student.complaints.index')
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat dihapus');
        }

        $complaint->delete();

        return redirect()->route('student.complaints.index')
            ->with('success', 'Pengaduan berhasil dihapus');
    }

    /**
     * Handle file uploads for complaints
     */
    private function handleFileUploads($files, $complaintId)
    {
        foreach ($files as $file) {
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('complaints', $fileName, 'public');
            
            ComplaintAttachment::create([
                'complaint_id' => $complaintId,
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
        $user = Auth::user();
        
        // Check if user has access to this attachment
        if ($attachment->complaint && $attachment->complaint->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini');
        }
        
        if ($attachment->complaintReply && $attachment->complaintReply->complaint->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini');
        }

        return $attachment->download();
    }

    /**
     * Delete attachment
     */
    public function deleteAttachment(ComplaintAttachment $attachment)
    {
        $user = Auth::user();
        
        // Check if user has access to this attachment
        if ($attachment->complaint && $attachment->complaint->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini');
        }
        
        if ($attachment->complaintReply && $attachment->complaintReply->complaint->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini');
        }

        // Only allow deletion if complaint is still pending
        if ($attachment->complaint && $attachment->complaint->status !== 'pending') {
            return response()->json(['error' => 'File tidak dapat dihapus karena pengaduan sudah diproses'], 403);
        }

        $attachment->deleteFile();
        $attachment->delete();

        return response()->json(['success' => 'File berhasil dihapus']);
    }
}