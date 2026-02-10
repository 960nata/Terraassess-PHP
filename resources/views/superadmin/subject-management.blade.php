@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Mata Pelajaran')

@section('content')
@include('components.shared-subject-management', [
    'user' => $user ?? auth()->user(),
    'subjects' => $subjects ?? [],
    'totalSubjects' => $totalSubjects ?? 0,
    'totalTeachers' => $totalTeachers ?? 0,
    'totalMaterials' => $totalMaterials ?? 0,
    'totalTasks' => $totalTasks ?? 0,
    'userRole' => ($user ?? auth()->user())->roles_id == 1 ? 'superadmin' : (($user ?? auth()->user())->roles_id == 2 ? 'admin' : 'teacher')
])
@endsection