@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Manajemen Notifikasi Super Admin')

@section('content')
@include('components.shared-notification-management', [
    'user' => $user ?? auth()->user(),
    'notifications' => $notifications ?? [],
    'totalNotifications' => $totalNotifications ?? 0,
    'readNotifications' => $readNotifications ?? 0,
    'unreadNotifications' => $unreadNotifications ?? 0,
    'urgentNotifications' => $urgentNotifications ?? 0,
    'userRole' => ($user ?? auth()->user())->roles_id == 1 ? 'superadmin' : (($user ?? auth()->user())->roles_id == 2 ? 'admin' : 'teacher'),
    'users' => $users ?? []
])
@endsection