<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUjianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->roles_id == 2; // Only pengajar can create ujian
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
            'waktu_mulai' => 'required|date|after:now',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'durasi' => 'required|integer|min:1|max:480', // Max 8 hours
            'tipe_ujian' => 'required|in:essay,multiple',
            'kelas_mapel_id' => 'required|exists:kelas_mapels,id',
            'bobot_nilai' => 'required|numeric|min:0|max:100',
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
            'judul.required' => 'Judul ujian harus diisi.',
            'judul.max' => 'Judul ujian maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi ujian harus diisi.',
            'waktu_mulai.required' => 'Waktu mulai ujian harus diisi.',
            'waktu_mulai.after' => 'Waktu mulai harus lebih dari waktu sekarang.',
            'waktu_selesai.required' => 'Waktu selesai ujian harus diisi.',
            'waktu_selesai.after' => 'Waktu selesai harus lebih dari waktu mulai.',
            'durasi.required' => 'Durasi ujian harus diisi.',
            'durasi.min' => 'Durasi minimal 1 menit.',
            'durasi.max' => 'Durasi maksimal 480 menit (8 jam).',
            'tipe_ujian.required' => 'Tipe ujian harus dipilih.',
            'tipe_ujian.in' => 'Tipe ujian tidak valid.',
            'kelas_mapel_id.required' => 'Kelas mapel harus dipilih.',
            'kelas_mapel_id.exists' => 'Kelas mapel tidak ditemukan.',
            'bobot_nilai.required' => 'Bobot nilai harus diisi.',
            'bobot_nilai.numeric' => 'Bobot nilai harus berupa angka.',
            'bobot_nilai.min' => 'Bobot nilai minimal 0.',
            'bobot_nilai.max' => 'Bobot nilai maksimal 100.',
        ];
    }
}
