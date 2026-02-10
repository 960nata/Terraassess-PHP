@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
@include('components.shared-notification-management', [
    'user' => $user ?? auth()->user(),
    'notifications' => $notifications ?? [],
    'totalNotifications' => $notifications->count() ?? 0,
    'readNotifications' => $notifications->where('is_read', true)->count() ?? 0,
    'unreadNotifications' => $notifications->where('is_read', false)->count() ?? 0,
    'urgentNotifications' => $notifications->where('type', 'error')->count() ?? 0,
    'userRole' => 'admin',
    'users' => $users ?? []
])
@endsection
