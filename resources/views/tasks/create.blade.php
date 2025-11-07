@extends('layouts.app')

@section('title', 'Create Task')
@section('page-subtitle', 'Add a new task')

@section('content')
  <div class="panel">
    <h3>Create New Task</h3>

    <form action="{{ route('task.store') }}" method="POST" style="margin-top:12px;">
      @csrf

      <div style="margin-bottom:10px;">
        <label>Title</label>
        <input type="text" name="title" value="{{ old('title') }}" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">
        @error('title') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div style="margin-bottom:10px;">
        <label>Description</label>
        <textarea name="description" rows="4" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">{{ old('description') }}</textarea>
      </div>

      <div style="display:flex;gap:10px;margin-bottom:10px;">
        <div style="flex:1;">
          <label>Priority</label>
          <select name="priority" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">
            <option value="low" {{ old('priority')=='low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ old('priority')=='medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ old('priority')=='high' ? 'selected' : '' }}>High</option>
          </select>
        </div>

        <div style="flex:1;">
          <label>Due date</label>
          <input type="date" name="due_date" value="{{ old('due_date') }}" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">
        </div>
      </div>

      <div style="margin-top:12px;">
        <button class="btn btn-add" type="submit"><i class="fa-solid fa-check"></i> Save Task</button>
        <a href="/" style="margin-left:8px;">Cancel</a>
      </div>
    </form>
  </div>
@endsection
