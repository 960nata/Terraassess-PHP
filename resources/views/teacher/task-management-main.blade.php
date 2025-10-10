@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
@include('components.shared-task-management', [
    'user' => $user ?? auth()->user(),
    'tasks' => $tasks ?? [],
    'classes' => $classes ?? [],
    'subjects' => $subjects ?? [],
    'filters' => $filters ?? [],
    'totalTasks' => $stats['total'] ?? 0,
    'activeTasks' => $stats['active'] ?? 0,
    'completedTasks' => $stats['completed'] ?? 0,
    'activeClasses' => $activeClasses ?? 0,
    'userRole' => 'teacher'
])
@endsection
