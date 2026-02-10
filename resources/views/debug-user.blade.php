@extends('layouts.unified-layout')

@section('title', 'Debug User')

@section('content')
<div class="debug-info">
    <h2>Debug User Information</h2>
    <p><strong>User ID:</strong> {{ $user->id ?? 'N/A' }}</p>
    <p><strong>User Name:</strong> {{ $user->name ?? 'N/A' }}</p>
    <p><strong>User Email:</strong> {{ $user->email ?? 'N/A' }}</p>
    <p><strong>Roles ID:</strong> {{ $user->roles_id ?? 'N/A' }}</p>
    <p><strong>Auth User ID:</strong> {{ auth()->user()->id ?? 'N/A' }}</p>
    <p><strong>Auth User Name:</strong> {{ auth()->user()->name ?? 'N/A' }}</p>
    <p><strong>Auth User Roles ID:</strong> {{ auth()->user()->roles_id ?? 'N/A' }}</p>
    
    <h3>Role Check:</h3>
    <p>Is Superadmin: {{ $user->roles_id == 1 ? 'Yes' : 'No' }}</p>
    <p>Is Admin: {{ $user->roles_id == 2 ? 'Yes' : 'No' }}</p>
    <p>Is Teacher: {{ $user->roles_id == 3 ? 'Yes' : 'No' }}</p>
    <p>Is Student: {{ $user->roles_id == 4 ? 'Yes' : 'No' }}</p>
</div>
@endsection
