@extends('layouts.app')

@section('page-title', 'Edit Task')
@section('page-subtitle', 'Update task')

@section('content')
  <div class="panel">
    <h3>Edit Task</h3>

    <form action="{{ route('tasks.update', $task) }}" method="POST" style="margin-top:12px;">
      @csrf
      @method('PUT')

      <div style="margin-bottom:10px;">
        <label>Title</label>
        <input type="text" name="title" value="{{ old('title', $task->title) }}" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">
        @error('title') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div style="margin-bottom:10px;">
        <label>Description</label>
        <textarea name="description" rows="4" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">{{ old('description', $task->description) }}</textarea>
      </div>

      <div style="display:flex;gap:10px;margin-bottom:10px;">
        <div style="flex:1;">
          <label>Priority</label>
          <select name="priority" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">
            <option value="low" {{ old('priority', $task->priority)=='low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ old('priority', $task->priority)=='medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ old('priority', $task->priority)=='high' ? 'selected' : '' }}>High</option>
          </select>
        </div>

        <div style="flex:1;">
          <label>Due date</label>
          <input type="date" name="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">
        </div>
      </div>

      <div style="margin-top:12px;">
        <button class="btn btn-add" type="submit"><i class="fa-solid fa-check"></i> Update Task</button>
        <a href="{{ route('tasks.index') }}" style="margin-left:8px;">Cancel</a>
      </div>
    </form>
  </div>
@endsection
