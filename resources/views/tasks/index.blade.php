@extends('layouts.app')

@section('page-title', 'Tasks')
@section('page-subtitle', 'All tasks')

@section('content')
  <div class="panel">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
      <h3 style="margin:0">All Tasks</h3>
      <a href="{{ route('task.create') }}" class="btn btn-add"><i class="fa-solid fa-plus"></i> New Task</a>
    </div>

    <div style="display:flex;flex-direction:column;gap:12px;">
      @forelse($tasks as $task)
        <x-task-card :task="$task" />
      @empty
        <div class="panel" style="padding:20px;">No tasks yet. <a href="{{ route('task.create') }}">Create one</a></div>
      @endforelse

      <div style="margin-top:12px;">
        {{-- {{ $tasks->links() }} --}}
      </div>
    </div>
  </div>
@endsection
