@extends('layouts.app')

@section('title', 'Панель диспетчера')

@section('content')
<h1>Панель диспетчера</h1>
<form method="GET" action="{{ route('dispatcher.index') }}">
    <label>Статус:</label>
    <select name="status" onchange="this.form.submit()">
        <option value="">Все</option>
        @foreach(\App\Enums\RequestStatus::cases() as $status)
            <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                {{ $status->label() }}
            </option>
        @endforeach
    </select>
</form>
<table>
    <tr>
        <th>ID</th>
        <th>Клиент</th>
        <th>Телефон</th>
        <th>Адрес</th>
        <th>Статус</th>
        <th>Мастер</th>
        <th>Действия</th>
    </tr>
    @forelse($requests as $r)
        <tr>
            <td>{{ $r->id }}</td>
            <td>{{ $r->client_name }}</td>
            <td>{{ $r->phone }}</td>
            <td>{{ $r->address }}</td>
            <td>{{ $r->status->label() }}</td>
            <td>{{ $r->assignedTo?->name ?? '—' }}</td>
            <td>
                @if($r->status->value === 'new')
                    <form action="{{ route('dispatcher.assign', $r) }}" method="POST" style="display:inline;">
                        @csrf
                        <select name="master_id" required>
                            <option value="">Выберите мастера</option>
                            @foreach($masters as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit">Назначить</button>
                    </form>
                    <form action="{{ route('dispatcher.cancel', $r) }}" method="POST" style="display:inline;" onsubmit="return confirm('Отменить заявку?');">
                        @csrf
                        <button type="submit">Отменить</button>
                    </form>
                @elseif(!in_array($r->status->value, ['done', 'canceled']))
                    <form action="{{ route('dispatcher.cancel', $r) }}" method="POST" style="display:inline;" onsubmit="return confirm('Отменить заявку?');">
                        @csrf
                        <button type="submit">Отменить</button>
                    </form>
                @else
                    —
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="7">Заявок нет</td></tr>
    @endforelse
</table>
{{ $requests->withQueryString()->links() }}
@endsection
