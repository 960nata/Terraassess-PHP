@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Manajemen Kelas')

@section('content')
@include('components.shared-class-management', [
    'user' => $user ?? auth()->user(),
    'classes' => $classes ?? [],
    'totalClasses' => $totalClasses ?? 0,
    'totalStudents' => $totalStudents ?? 0,
    'totalSubjects' => $totalSubjects ?? 0,
    'totalTeachers' => $totalTeachers ?? 0,
    'userRole' => ($user ?? auth()->user())->roles_id == 1 ? 'superadmin' : (($user ?? auth()->user())->roles_id == 2 ? 'admin' : 'teacher')
])
@endsection