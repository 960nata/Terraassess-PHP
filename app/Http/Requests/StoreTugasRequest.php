<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTugasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->roles_id == 2; // Only pengajar can create tugas
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'deadline' => 'required|date|after:now',
            'tipe_tugas' => 'required|in:individual,kelompok,quiz,multiple',
            'kelas_mapel_id' => 'required|exists:kelas_mapels,id',
            'file_tugas.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:10240', // 10MB max
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul tugas harus diisi.',
            'judul.max' => 'Judul tugas maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi tugas harus diisi.',
            'deadline.required' => 'Deadline tugas harus diisi.',
            'deadline.after' => 'Deadline harus lebih dari waktu sekarang.',
            'tipe_tugas.required' => 'Tipe tugas harus dipilih.',
            'tipe_tugas.in' => 'Tipe tugas tidak valid.',
            'kelas_mapel_id.required' => 'Kelas mapel harus dipilih.',
            'kelas_mapel_id.exists' => 'Kelas mapel tidak ditemukan.',
            'file_tugas.*.file' => 'File harus berupa file yang valid.',
            'file_tugas.*.mimes' => 'File harus berupa PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, atau TXT.',
            'file_tugas.*.max' => 'Ukuran file maksimal 10MB.',
        ];
    }
}
