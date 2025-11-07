<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>{{ $title ?? config('app.name', 'Goal Getter') }}</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  @vite(["resources/css/app.css",
            "resources/css/style.css",
            "resources/js/app.js",
            "resources/js/script.js" ])

  @stack('head')
</head>
<body>
  <div id="app">
    <div class="app-wrapper">
      @include('components.sidebar')

      <main class="main">
        @include('components.header')

        <div class="content">
          @yield('content')
        </div>
      </main>

      @include('components.right-panel')
    </div>
  </div>

  @stack('scripts')
</body>
</html>
