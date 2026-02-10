@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Manajemen Mata Pelajaran Admin')

@section('content')
@include('components.shared-subject-management', [
    'user' => $user ?? auth()->user(),
    'subjects' => $subjects ?? [],
    'totalSubjects' => $totalSubjects ?? 0,
    'totalTeachers' => $totalTeachers ?? 0,
    'totalMaterials' => $totalMaterials ?? 0,
    'totalTasks' => $totalTasks ?? 0,
    'userRole' => 'admin'
])
@endsection