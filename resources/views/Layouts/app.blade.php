<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>{{ $title ?? config('app.name', 'Goal Getter') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    
    @vite(['resources/css/app.css', 'resources/css/style.css', 'resources/js/app.js', 'resources/js/script.js'])


    @stack('head')
</head>

<body>
    <div id="app">
        <div class="app-wrapper">
            {{-- @include('components.sidebar') --}}
            <x-sidebar />

            <main class="main">
                @include('components.header')




                @if (session('success'))
                    <x-alert type="success">
                        {{ session('success') }}
                    </x-alert>
                @endif
                @if (session('error'))
                    <x-alert type="danger">
                        {{ session('error') }}
                    </x-alert>
                @endif



                <div class="content">
                    @yield('content')
                </div>
            </main>

            <x-right-panel />
        </div>
    </div>

    @stack('scripts')







</body>

</html>
