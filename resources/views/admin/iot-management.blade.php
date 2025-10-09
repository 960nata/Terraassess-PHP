@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Manajemen IoT Admin')

@section('content')
@include('components.shared-iot-management', [
    'user' => $user ?? auth()->user(),
    'devices' => $devices ?? [],
    'totalDevices' => $totalDevices ?? 0,
    'activeDevices' => $activeDevices ?? 0,
    'totalSensors' => $totalSensors ?? 0,
    'totalDataPoints' => $totalDataPoints ?? 0,
    'userRole' => 'admin'
])
@endsection