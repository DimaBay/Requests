@extends('layouts.app')

@section('title', 'Панель мастера')

@section('content')
<h1>Панель мастера</h1>
<p>Мои заявки</p>
<table>
    <tr>
        <th>ID</th>
        <th>Клиент</th>
        <th>Телефон</th>
        <th>Адрес</th>
        <th>Проблема</th>
        <th>Статус</th>
        <th>Действия</th>
    </tr>
    @forelse($requests as $r)
        <tr>
            <td>{{ $r->id }}</td>
            <td>{{ $r->client_name }}</td>
            <td>{{ $r->phone }}</td>
            <td>{{ $r->address }}</td>
            <td>{{ \Illuminate\Support\Str::limit($r->problem_text, 50) }}</td>
            <td>{{ $r->status->label() }}</td>
            <td>
                @if($r->status->value === 'assigned')
                    <form action="{{ route('master.start', $r) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">Взять в работу</button>
                    </form>
                @elseif($r->status->value === 'in_progress')
                    <form action="{{ route('master.done', $r) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">Завершить</button>
                    </form>
                @else
                    —
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="7">Нет назначенных заявок</td></tr>
    @endforelse
</table>
{{ $requests->links() }}
@endsection
