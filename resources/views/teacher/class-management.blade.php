@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Manajemen Kelas Teacher')

@section('content')
@include('components.shared-class-management', [
    'user' => $user ?? auth()->user(),
    'classes' => $classes ?? [],
    'totalClasses' => $totalClasses ?? 0,
    'totalStudents' => $totalStudents ?? 0,
    'totalSubjects' => $totalSubjects ?? 0,
    'totalTeachers' => $totalTeachers ?? 0,
    'userRole' => 'teacher'
])
@endsection
