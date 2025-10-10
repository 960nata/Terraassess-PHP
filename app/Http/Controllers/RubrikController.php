<?php

namespace App\Http\Controllers;

use App\Models\RubrikPenilaian;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RubrikController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the rubrik for a specific task.
     */
    public function show($tugasId)
    {
        $tugas = Tugas::with('rubrik')->findOrFail($tugasId);
        
        // Check if user has permission to view this task's rubrik
        if (!in_array(Auth::user()->roles_id, [1, 2, 3])) { // Superadmin, Admin, Teacher
            abort(403, 'Unauthorized access');
        }

        return view('teacher.rubrik.show', compact('tugas'));
    }

    /**
     * Store a new rubrik for a task.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tugas_id' => 'required|exists:tugas,id',
            'aspek.*' => 'required|string|max:255',
            'bobot.*' => 'required|integer|min:1|max:100',
            'deskripsi.*' => 'nullable|string|max:1000',
        ]);

        // Check if user has permission to create rubrik
        if (!in_array(Auth::user()->roles_id, [1, 2, 3])) { // Superadmin, Admin, Teacher
            abort(403, 'Unauthorized access');
        }

        // Validasi total bobot = 100
        $totalBobot = array_sum($request->bobot);
        if ($totalBobot != 100) {
            return back()->with('error', 'Total bobot harus 100%. Saat ini: ' . $totalBobot . '%');
        }

        // Delete existing rubrik for this task
        RubrikPenilaian::where('tugas_id', $request->tugas_id)->delete();

        // Create new rubrik
        foreach ($request->aspek as $index => $aspek) {
            RubrikPenilaian::create([
                'tugas_id' => $request->tugas_id,
                'aspek' => $aspek,
                'bobot' => $request->bobot[$index],
                'deskripsi' => $request->deskripsi[$index] ?? null,
            ]);
        }

        return back()->with('success', 'Rubrik penilaian berhasil dibuat!');
    }

    /**
     * Update an existing rubrik.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'aspek' => 'required|string|max:255',
            'bobot' => 'required|integer|min:1|max:100',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        $rubrik = RubrikPenilaian::findOrFail($id);
        
        // Check if user has permission to update rubrik
        if (!in_array(Auth::user()->roles_id, [1, 2, 3])) { // Superadmin, Admin, Teacher
            abort(403, 'Unauthorized access');
        }

        $rubrik->update([
            'aspek' => $request->aspek,
            'bobot' => $request->bobot,
            'deskripsi' => $request->deskripsi,
        ]);

        return back()->with('success', 'Rubrik berhasil diperbarui!');
    }

    /**
     * Delete a rubrik.
     */
    public function destroy($id)
    {
        $rubrik = RubrikPenilaian::findOrFail($id);
        
        // Check if user has permission to delete rubrik
        if (!in_array(Auth::user()->roles_id, [1, 2, 3])) { // Superadmin, Admin, Teacher
            abort(403, 'Unauthorized access');
        }

        $rubrik->delete();

        return back()->with('success', 'Rubrik berhasil dihapus!');
    }

    /**
     * Get rubrik data for a task (API endpoint).
     */
    public function getRubrik($tugasId)
    {
        $rubrik = RubrikPenilaian::where('tugas_id', $tugasId)->get();
        
        return response()->json([
            'success' => true,
            'data' => $rubrik
        ]);
    }
}
