@extends('layouts.app')

@section('page-title', 'Tasks')
@section('page-subtitle', 'All tasks')

@section('content')
    <div class="panel">
        {{-- <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
      <h3 style="margin:0">All Tasks</h3>
      <a href="{{ route('task.create') }}" class="btn btn-add"><i class="fa-solid fa-plus"></i> New Task</a>
    </div> --}}

        <div style=" display: grid;  grid-template-columns: repeat(2, 1fr);  gap: 12px;">
            @forelse($tasks as $task)
                <x-task-card :task="$task" />
            @empty
                <div class="panel" style="padding:20px;">
                    No tasks yet. <a href="{{ route('tasks.create') }}">Create one</a>
                </div>
            @endforelse

            <div style="grid-column: 1 / -1; margin-top:12px;">
                {{ $tasks->links() }}
            </div>
        </div>

    </div>
@endsection
