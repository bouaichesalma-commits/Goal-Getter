<aside class="sidebar">
  <div class="brand">
    <div class="logo">⚡</div>
    <h1>{{ config('app.name', 'Goal Getter') }}</h1>
  </div>

  <div class="profile">
    <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 2)) }}</div>
    <div class="meta">
      <div class="name">{{ auth()->user()->name ?? 'Salma' }}</div>
      <div class="sub">Your productivity</div>
    </div>
  </div>

  <nav class="nav">
    <a class="" href="">
      <span class="icon"> <i class="fa-solid fa-grip" style="color: var(--accent); "></i></span> Dashboard
    </a>
    <a class="{{ request()->routeIs('tasks.index') ? 'active' : '' }}" href="">
      <span class="icon"><i class="fa-solid fa-list-check"  style="color: var(--accent);"></i></span> Pending Tasks
    </a>

      <a class="{{ request()->routeIs('tasks.index') ? 'active' : '' }}" href="">
      <span class="icon"><i class="fa-regular fa-circle-check"  style="color: var(--accent);"></i></span> Completed Tasks 
    </a>
  </nav>

  <div class="pro-tip">
    <div class="title">Pro Tip</div>
    Use keyboard shortcuts and quick add to speed up.
  </div>
</aside>
