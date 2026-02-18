@extends('layouts.app')

@section('title', 'Создать заявку')

@section('content')
<h1>Создать заявку в ремонтную службу</h1>
@if ($errors->any())
    <div class="errors">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="{{ route('requests.store') }}">
    @csrf
    <p>
        <label for="client_name">Имя клиента *</label><br>
        <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}" required>
    </p>
    <p>
        <label for="phone">Телефон *</label><br>
        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required>
    </p>
    <p>
        <label for="address">Адрес *</label><br>
        <input type="text" name="address" id="address" value="{{ old('address') }}" required>
    </p>
    <p>
        <label for="problem_text">Описание проблемы *</label><br>
        <textarea name="problem_text" id="problem_text" rows="5" required>{{ old('problem_text') }}</textarea>
    </p>
    <p>
        <button type="submit">Отправить заявку</button>
    </p>
</form>
@endsection
