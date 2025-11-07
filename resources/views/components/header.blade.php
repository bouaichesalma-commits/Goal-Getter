<header class="app-header">
  <div class="left">
 

    <div style="display:flex;flex-direction:column;">
      <h2 class="app-title">
        {{-- <i class="fa-solid fa-grip" style="color:"></i> --}}
        @yield('page-title', 'Task Overview')
      </h2>
      <p class="app-subtitle">@yield('page-subtitle', 'Manage your tasks')</p>
    </div>
  </div>

  <div class="header-action">
    <a class="btn btn-add" id="addTaskBtn" href="{{route('task.create')}}"><i class="fa-solid fa-plus"></i> Add New</a>
  </div>
</header>
