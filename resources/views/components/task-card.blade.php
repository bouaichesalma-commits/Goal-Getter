@props(['task'])

<article class="task-card" data-priority="{{ $task->priority ?? 'low' }}" tabindex="0" ">
  <div class="task-left">
    <span class="status-dot" aria-hidden="true"></span>
    <div class="task-main">
      <h3 id="task-1-title" class="task-title">
        Titre
        <span class="badge badge-low">Low</span>
      </h3>
      <p class="task-desc">Description</p>
    </div>
  </div>

  <div class="task-right">
    <div class="task-meta">
      <div class="meta-item">
        <i class="fa-regular fa-calendar"></i>
        <span class="meta-text">2033</span>
      </div>
      <div class="meta-item">
        <i class="fa-regular fa-clock"></i>
        <span class="meta-text">Created 2025</span>
      </div>
    </div>

    <div class="task-actions">
      <a href="" class="more-btn" aria-label="Edit"><i class="fa-solid fa-pen"></i></a>

      <form action="" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button class="more-btn" type="submit" onclick="return confirm('Delete this task?')"><i class="fa-solid fa-trash"></i></button>
      </form>
    </div>
  </div>
</article>
