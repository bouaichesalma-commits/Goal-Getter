@extends('layouts.app')

@section('title', 'Create Task')
@section('page-subtitle', 'Add a new task')

@section('content')
    <div class="panel">
        <h3>Create New Task</h3>

        <form action="{{ route('tasks.store') }}" method="POST" style="margin-top:12px;">
            @csrf

            <div style="margin-bottom:10px;">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">
                @error('title')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom:10px;">
                <label>Description</label>
                <textarea name="description" rows="4" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">{{ old('description') }}</textarea>
            </div>

            <div style="display:flex;gap:10px;margin-bottom:10px;">

              <div style="flex:1;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                       <span>Completed</span>
                        <div style="position: relative; display: inline-block;">
                            <input type="checkbox" name="is_completed" value="1"
                                {{ old('is_completed') ? 'checked' : '' }} style="display: none;">
                            <div
                                style="width: 50px; height: 24px; background: #ddd; border-radius: 12px; position: relative; transition: background 0.3s;">
                                <div
                                    style="position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background: white; border-radius: 50%; transition: transform 0.3s; {{ old('is_completed') ? 'transform: translateX(26px);' : '' }}">
                                </div>
                            </div>
                        </div>
                       
                    </label>
                </div>
                <div style="flex:1;">
                    <label>Priority</label>
                    <select name="priority" style="width:100%;padding:8px;border-radius:16px;border:1px solid #ddd;">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                

               
            </div>
             <div style="margin-top:12px;">
                    <button class="btn btn-add" type="submit"><i class="fa-solid fa-check"></i> Save Task</button>
                    <a href="/" style="margin-left:8px;">Cancel</a>
                </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('input[type="checkbox"][name="is_completed"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const toggle = this.nextElementSibling;
                    const knob = toggle.querySelector('div');

                    if (this.checked) {
                        toggle.style.background = 'var(--accent)';
                        knob.style.transform = 'translateX(26px)';
                    } else {
                        toggle.style.background = '#ddd';
                        knob.style.transform = 'translateX(0)';
                    }
                });
            });
        </script> 
    @endpush
@endsection
