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
      <div style="  display: grid;grid-template-columns: repeat(2, 1fr); gap: 12px;" >
            @if ($tasks && is_iterable($tasks))
            @foreach ($tasks as $task)
                    @include('components.task-card', ['task' => $task])
            @endforeach
        @endif
                
      </div>
      <div>
         {{ $tasks->links() }}
      </div>

        <a href="{{route('tasks.create')}}">
            <div class="add-new-placeholder" role="button" tabindex="0" aria-label="Add new task">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Task</span>
            </div>
        </a>
    </section>

  @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const filterButtons = document.querySelectorAll(".filter-pill");
                const tasks = document.querySelectorAll(".task-card");

                filterButtons.forEach(btn => {
                    btn.addEventListener("click", () => {
                        const filter = btn.dataset.filter;

                        document.querySelector(".filter-pill.active")?.classList.remove("active");
                        btn.classList.add("active");

                        // Use string comparison (YYYY-MM-DD format)
                        const todayStr = new Date().toISOString().split('T')[0];

                        const weekAgo = new Date();
                        weekAgo.setDate(weekAgo.getDate() - 7);
                        const weekAgoStr = weekAgo.toISOString().split('T')[0];

                        tasks.forEach(task => {
                            const priority = task.dataset.priority;
                            const taskDate = task.dataset.date;

                            let show = false;

                            if (filter === "all") show = true;
                            else if (filter === "today" && taskDate === todayStr) show = true;
                            else if (filter === "week" && taskDate >= weekAgoStr && taskDate <=
                                todayStr) show = true;
                            else if (filter === priority) show = true;

                            task.style.display = show ? "flex" : "none";
                        });
                    });
                });
            });
        </script>
    @endpush