@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Push Notifikasi Admin')

@section('content')
@include('components.shared-notification-management', [
    'user' => $user ?? auth()->user(),
    'notifications' => $notifications ?? [],
    'totalNotifications' => $totalNotifications ?? 0,
    'readNotifications' => $readNotifications ?? 0,
    'unreadNotifications' => $unreadNotifications ?? 0,
    'urgentNotifications' => $urgentNotifications ?? 0,
    'userRole' => 'admin',
    'users' => $users ?? []
])
@endsection