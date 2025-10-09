@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Manajemen IoT')

@section('content')
@include('components.shared-iot-management', [
    'user' => $user ?? auth()->user(),
    'devices' => $devices ?? [],
    'totalDevices' => $totalDevices ?? 0,
    'activeDevices' => $activeDevices ?? 0,
    'totalSensors' => $totalSensors ?? 0,
    'totalDataPoints' => $totalDataPoints ?? 0,
    'userRole' => ($user ?? auth()->user())->roles_id == 1 ? 'superadmin' : (($user ?? auth()->user())->roles_id == 2 ? 'admin' : 'teacher')
])
@endsection