@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Manajemen Ujian Admin')

@section('content')
@include('components.shared-exam-management', [
    'user' => $user ?? auth()->user(),
    'exams' => $exams ?? [],
    'classes' => $classes ?? [],
    'subjects' => $subjects ?? [],
    'filters' => $filters ?? [],
    'totalExams' => $totalExams ?? 0,
    'activeExams' => $activeExams ?? 0,
    'completedExams' => $completedExams ?? 0,
    'totalParticipants' => $totalParticipants ?? 0,
    'userRole' => 'admin'
])
@endsection
