@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Manajemen Materi Admin')

@section('content')
@include('components.shared-material-management', [
    'user' => $user ?? auth()->user(),
    'materials' => $materials ?? [],
    'subjects' => $subjects ?? [],
    'classes' => $classes ?? [],
    'totalMaterials' => $totalMaterials ?? 0,
    'publishedMaterials' => $publishedMaterials ?? 0,
    'totalViews' => $totalViews ?? 0,
    'totalDownloads' => $totalDownloads ?? 0,
    'userRole' => 'admin'
])
@endsection