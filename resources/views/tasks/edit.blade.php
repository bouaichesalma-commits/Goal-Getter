@extends('layouts.app')

@section('title', 'Edit Task')
@section('page-subtitle', 'Modify your task')

@section('content')
    <div class="panel">
        <h3>Modify Your Task:</h3>

        <form action="{{ route('tasks.update', $task->id) }}" method="POST" style="margin-top:12px;">
            @csrf
            @method('PUT')

            <div style="margin-bottom:10px;">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}" required
                    style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">
                @error('title')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom:10px;">
                <label>Description</label>
                <textarea name="description" rows="4" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">{{ old('description', $task->description) }}</textarea>
            </div>

            <div style="display:flex;gap:10px;margin-bottom:10px;">
                <div style="flex:1;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <span>Completed</span>
                        <div style="position: relative; display: inline-block;">
                            <input type="checkbox" name="is_completed" value="1"
                                {{ old('is_completed', $task->is_completed) ? 'checked' : '' }} style="display: none;">
                            <div class="toggle-switch"
                                style="width: 50px; height: 24px; background: {{ old('is_completed', $task->is_completed) ? 'var(--accent)' : '#ddd' }}; border-radius: 12px; position: relative; transition: background 0.3s;">
                                <div class="toggle-knob"
                                    style="position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background: white; border-radius: 50%; transition: transform 0.3s; {{ old('is_completed', $task->is_completed) ? 'transform: translateX(26px);' : '' }}">
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                <div style="flex:1;">
                    <label>Priority</label>
                    <select name="priority" style="width:100%;padding:8px;border-radius:16px;border:1px solid #ddd;">
                        <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>

            <div style="margin-top:12px;">
                <button class="btn btn-add" type="submit"><i class="fa-solid fa-check"></i> Modify Task</button>
                <a href="/" style="margin-left:8px;">Cancel</a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('input[type="checkbox"][name="is_completed"]');
                
                checkboxes.forEach(checkbox => {
                    const toggle = checkbox.nextElementSibling;
                    
                    // Initialize toggle appearance
                    updateToggleAppearance(checkbox, toggle);
                    
                    // Add click event to toggle
                    toggle.addEventListener('click', function() {
                        checkbox.checked = !checkbox.checked;
                        updateToggleAppearance(checkbox, toggle);
                    });

                    // Also update on change (for good measure)
                    checkbox.addEventListener('change', function() {
                        updateToggleAppearance(checkbox, toggle);
                    });
                });
                
                function updateToggleAppearance(checkbox, toggle) {
                    const knob = toggle.querySelector('.toggle-knob');
                    if (checkbox.checked) {
                        toggle.style.background = 'var(--accent)';
                        knob.style.transform = 'translateX(26px)';
                    } else {
                        toggle.style.background = '#ddd';
                        knob.style.transform = 'translateX(0)';
                    }
                }
            });
        </script> 
    @endpush
@endsection
