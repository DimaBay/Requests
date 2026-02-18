<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Заявки в ремонт')</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: system-ui, sans-serif; max-width: 900px; margin: 0 auto; padding: 1rem; }
        nav { margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid #ddd; }
        nav a { margin-right: 1rem; color: #333; text-decoration: none; }
        nav a:hover { text-decoration: underline; }
        .alert { padding: 1rem; margin-bottom: 1rem; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .errors { color: #721c24; margin-bottom: 1rem; }
        .errors ul { margin: 0; padding-left: 1.5rem; }
    </style>
</head>
<body>
    <nav>
        <a href="{{ url('/requests/create') }}">Создать заявку</a>
        @auth
        @if(auth()->user()->role === 'dispatcher')
            <a href="{{ url('/dispatcher') }}">Панель диспетчера</a>
        @endif
        @if(auth()->user()->role === 'master')
            <a href="{{ url('/master') }}">Панель мастера</a>
        @endif
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit">Выйти</button>
        </form>
        @else
        <a href="{{ url('/login') }}">Вход</a>
        @endauth
    </nav>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @yield('content')
</body>
</html>
