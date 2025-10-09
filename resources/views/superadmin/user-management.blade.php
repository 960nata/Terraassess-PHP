@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Manajemen Pengguna')

@section('content')
@include('components.shared-user-management', [
    'user' => $user ?? auth()->user(),
    'users' => $users ?? [],
    'totalUsers' => $totalUsers ?? 0,
    'totalTeachers' => $totalTeachers ?? 0,
    'totalStudents' => $totalStudents ?? 0,
    'totalAdmins' => $totalAdmins ?? 0,
    'userRole' => ($user ?? auth()->user())->roles_id == 1 ? 'superadmin' : (($user ?? auth()->user())->roles_id == 2 ? 'admin' : 'teacher')
])
@endsection