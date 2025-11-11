@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back')
@php
    $tasks = [
        [
            'id' => 1,
            'title' => 'Finish Laravel project',
            'description' => 'Complete all controllers and views',
            'status' => 'in_progress',
            'due_date' => '2025-11-10',
        ],
        [
            'id' => 2,
            'title' => 'Buy groceries',
            'description' => 'Milk, Eggs, Bread, Fruits',
            'status' => 'pending',
            'due_date' => '2025-11-07',
        ],
        [
            'id' => 3,
            'title' => 'Prepare presentation',
            'description' => 'Slides for Monday meeting',
            'status' => 'pending',
            'due_date' => '2025-11-08',
        ],
        [
            'id' => 4,
            'title' => 'Clean the house',
            'description' => 'Vacuum and dust all rooms',
            'status' => 'completed',
            'due_date' => '2025-11-05',
        ],
        [
            'id' => 5,
            'title' => 'Call the bank',
            'description' => 'Inquire about new account options',
            'status' => 'pending',
            'due_date' => '2025-11-06',
        ],
    ];

@endphp





    @section('content')
        <x-task :tasks="$tasks" />
    @endsection
