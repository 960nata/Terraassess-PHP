@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Mata Pelajaran Teacher')

@section('content')
@include('components.shared-subject-management', [
    'user' => $user ?? auth()->user(),
    'subjects' => $subjects ?? [],
    'totalSubjects' => $totalSubjects ?? 0,
    'totalTeachers' => $totalTeachers ?? 0,
    'totalMaterials' => $totalMaterials ?? 0,
    'totalTasks' => $totalTasks ?? 0,
    'userRole' => 'teacher'
])
@endsection
