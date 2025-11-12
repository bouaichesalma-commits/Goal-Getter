<aside class="right-panel">
  <div class="card">
    <h4 style="margin:0 0 8px 0;">Task Statistics</h4>
    <div class="stat-grid">
      <div class="stat-item"><div class="num">{{ $tasksCount  }}</div><div class="lbl">Total</div></div>
      <div class="stat-item"><div class="num">{{ $completedCount ?? 0 }}</div><div class="lbl">Completed</div></div>
      <div class="stat-item"><div class="num">{{ $pendingCount ?? 0 }}</div><div class="lbl">Pending</div></div>
      <div class="stat-item"><div class="num">{{ $completionRate ?? '0%' }}</div><div class="lbl">Completion</div></div>
    </div>

    <div class="progress-wrap" style="margin-top:12px;">
      <div style="display:flex; justify-content:space-between; font-size:12px;">
        <div>Progress</div><div>{{ $completionRate ?? '0%' }}</div>
      </div>
      <div class="progress" style="margin-top:8px;">
        <div class="bar" style="width: {{ $completionPercent ?? 0 }}%"></div>
      </div>
    </div>
  </div>

  <div class="card" style="margin-top:14px;">
    <h4 style="margin:0 0 12px 0;">Recent Activity</h4>
    @if(isset($recent) && $recent->count())
      @foreach($recent as $r)
        <div class="recent-item">
          <div>
            <div style="font-weight:700;">{{ $r->title }}</div>
            <div style="font-size:12px;color:var(--muted)">{{ $r->created_at->format('M d, Y') }}</div>
          </div>
          <div style="color:var(--success); font-weight:700; font-size:13px; padding:6px 10px; border-radius:999px; background:#ecfff0;">{{ $r->status === 'completed' ? 'Done' : 'Pending' }}</div>
        </div>
      @endforeach
    @else
      {{-- <div class="recent-item">No recent activity</div> --}}

      ️@if(isset($tasks) && $tasks->count())

            @foreach($tasks as $task)
                @if($task->is_completed == 1  ) 
                  <div class="recent-item">
                    <div>
                      <div style="font-weight:700;">{{ $task->title }}</div>
                      <div style="font-size:12px;color:var(--muted)">{{ $task->created_at->format('M d, Y') }}</div>
                    </div>
                    <div style="color:var(--success); font-weight:700; font-size:13px; padding:6px 10px; border-radius:999px; background:#ecfff0;">Completed</div>

                  </div>
                @endif
            @endforeach
      @endif

    @endif
  </div>
</aside>
