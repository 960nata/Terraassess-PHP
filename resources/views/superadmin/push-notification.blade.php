@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Push Notifikasi Super Admin')

@section('content')
@include('components.shared-notification-management', [
    'user' => $user ?? auth()->user(),
    'notifications' => $recentNotifications ?? [],
    'totalNotifications' => $stats['total'] ?? 0,
    'readNotifications' => $stats['sent_today'] ?? 0,
    'unreadNotifications' => $stats['pending'] ?? 0,
    'urgentNotifications' => $stats['failed'] ?? 0,
    'userRole' => ($user ?? auth()->user())->roles_id == 1 ? 'superadmin' : (($user ?? auth()->user())->roles_id == 2 ? 'admin' : 'teacher'),
    'users' => $users ?? []
])
@endsection