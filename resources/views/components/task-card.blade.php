@props(['task'])

<article class="task-card" style="    display: flex; flex-direction: column;" data-priority="{{ $task->priority }}"
    data-date="{{ $task->created_at->timezone(config('app.timezone'))->format('Y-m-d') }}" >
    <div class="task-left">
        <span class="status-dot" aria-hidden="true"></span>
        <div class="task-main">
            <h3 id="task-1-title" class="task-title">
                {{ $task->title }}
                @if ($task->priority === 'low')
                    <span class="badge badge-low">Low</span>
                @endif
                @if ($task->priority === 'medium')
                    <span class="badge badge-medium">Medium</span>
                @elseif($task->priority === 'high')
                    <span class="badge badge-high">High</span>
                @endif
            </h3>
            <p class="task-desc">{{ $task->description }}</p>
        </div>
    </div>

    <div class="task-right">
        <div class="task-meta">
            <div class="meta-item">
                <i class="fa-regular fa-calendar"></i>
                <span class="meta-text">{{ $task->created_at }}</span>
            </div>

        </div>

        <div class="task-actions">
            <a href="{{route('tasks.edit',$task->id)}}" class="more-btn" aria-label="Edit"><i class="fa-solid fa-pen"></i></a>

            <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="more-btn" type="submit" onclick="return confirm('Delete this task?')"><i
                        class="fa-solid fa-trash"></i></button>
            </form>
        </div>
    </div>
</article>
