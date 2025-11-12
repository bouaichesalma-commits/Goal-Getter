@props([
  'tasks' => null,
  'total' => null,
  'low' => null,
  'medium' => null,
  'high' => null
])

@php
  if ($tasks && is_iterable($tasks)) {
      $total =1;
      $low = 1;
      $medium =1;
      $high =3 ;
  } else {
      $total = $total ?? 0;
      $low = $low ?? 0;
      $medium = $medium ?? 0;
      $high = $high ?? 0;
  }
@endphp

<div class="task-overview-component">
  
  <section class="summary-cards" aria-label="Task summary">


    <div class="card low-priority" role="group" aria-label="Low priority tasks">
      <div class="card-icon"><i class="fa-solid fa-fire" style="color: rgb(28, 194, 240)"></i></div>
      <div class="card-body">
        <div class="card-number">{{ $low }}</div>
        <div class="card-label">Low Priority</div>
      </div>
    </div>

    <div class="card medium-priority" role="group" aria-label="Medium priority tasks">
      <div class="card-icon"><i class="fa-solid fa-fire" style="color: rgb(240, 155, 28)"></i></div>
      <div class="card-body">
        <div class="card-number">{{ $medium }}</div>
        <div class="card-label">Medium Priority</div>
      </div>
    </div>

    <div class="card high-priority" role="group" aria-label="High priority tasks">
      <div class="card-icon"><i class="fa-solid fa-fire" style="color: rgb(240, 28, 28)"></i></div>
      <div class="card-body">
        <div class="card-number">{{ $high }}</div>
        <div class="card-label">High Priority</div>
      </div>
    </div>
  </section>

  <nav class="task-filters" aria-label="Task filters">
    <div class="filters-left">
      <button class="filter-toggle" aria-pressed="false">
        <i class="fa-solid fa-filter"></i>
        All Tasks
      </button>
    </div>
    <div class="filters-right">
      <button class="filter-pill active" data-filter="all">All</button>
      <button class="filter-pill" data-filter="today">Today</button>
      <button class="filter-pill" data-filter="week">Week</button>
      <button class="filter-pill" data-filter="high">High</button>
      <button class="filter-pill" data-filter="medium">Medium</button>
      <button class="filter-pill" data-filter="low">Low</button>
    </div>
  </nav>

  <section class="tasks-list" aria-label="All tasks">
    @if($tasks && is_iterable($tasks) )
      @foreach($tasks as $task)
        @include('components.task-card', ['task' => $task])
      @endforeach
    @else
      <article class="task-card" data-priority="low" tabindex="0" aria-labelledby="task-1-title">
        <div class="task-left">
          <span class="status-dot" aria-hidden="true"></span>
          <div class="task-main">
            <h3 id="task-1-title" class="task-title">Task New <span class="badge badge-low">Low</span></h3>
            <p class="task-desc">ASAP</p>
          </div>
        </div>

        <div class="task-right">
          <div class="task-meta">
            <div class="meta-item">
              <i class="fa-regular fa-calendar"></i>
              <span class="meta-text">May 01</span>
            </div>
            <div class="meta-item">
              <i class="fa-regular fa-clock"></i>
              <span class="meta-text">Created Apr 29</span>
            </div>
          </div>

          <div class="task-actions" aria-hidden="true">
            <button class="more-btn" aria-label="More options"><i class="fa-solid fa-ellipsis-vertical"></i></button>
          </div>
        </div>
      </article>
    @endif

    <div class="add-new-placeholder" role="button" tabindex="0" aria-label="Add new task">
      <i class="fa-solid fa-plus"></i>
      <span>Add New Task</span>
    </div>
  </section>
</div>
