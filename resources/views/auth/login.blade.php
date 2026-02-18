@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<h1>Вход в систему</h1>
@if ($errors->any())
    <div class="errors">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="{{ route('login') }}">
    @csrf
    <p>
        <label for="email">Email</label><br>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
    </p>
    <p>
        <label for="password">Пароль</label><br>
        <input type="password" name="password" id="password" required>
    </p>
    <p>
        <label>
            <input type="checkbox" name="remember">
            Запомнить меня
        </label>
    </p>
    <p>
        <button type="submit">Войти</button>
    </p>
</form>
<p><a href="{{ url('/requests/create') }}">Создать заявку (без входа)</a></p>
@endsection
