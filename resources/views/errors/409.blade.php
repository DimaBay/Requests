<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>409 — Конфликт</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 600px; margin: 2rem auto; padding: 1rem; text-align: center; }
        h1 { color: #c00; }
        a { color: #0066cc; }
    </style>
</head>
<body>
    <h1>409 — Конфликт</h1>
    <p>{{ $exception->getMessage() ?: 'Заявка уже взята в работу. Обновите страницу.' }}</p>
    <p><a href="{{ url()->previous() }}">Вернуться назад</a></p>
</body>
</html>
