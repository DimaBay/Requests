# Заявки в ремонтную службу

Веб-сервис для управления заявками в ремонтную службу.

## Стек

- PHP 8.2
- Laravel 11
- PostgreSQL
- Blade
- Session-based auth
- Docker Compose

## Локальный запуск

```bash
# Сборка и запуск
docker compose up -d --build

# Создать .env (если ещё нет)
cp .env.example .env

# Ключ приложения
docker compose exec app php artisan key:generate

# Миграции
docker compose exec app php artisan migrate

# Сиды (пользователи и заявки)
docker compose exec app php artisan db:seed
```

Приложение: **http://localhost:8000**

## Тестовые пользователи

| Роль      | Email                 | Пароль   |
|-----------|-----------------------|----------|
| Диспетчер | dispatcher@test.local | password |
| Мастер 1  | master1@test.local    | password |
| Мастер 2  | master2@test.local    | password |

## Проверка race condition

Скрипт `race_test.sh` выполняет 2 параллельных запроса «Взять в работу» на одну заявку. Один возвращает 302, второй — 409.

```bash
# Заявка с ID=3 в сидах — assigned для master1
bash race_test.sh 3

# Или с указанием URL
BASE_URL=http://localhost:8000 bash race_test.sh 3
```

## Автотесты

```bash
docker compose exec app php artisan test
# или
docker compose exec app ./vendor/bin/phpunit
```

## Audit log

Все изменения статусов записываются в таблицу `request_logs`:
- `request_id` — заявка
- `old_status` — старый статус (null для создания)
- `new_status` — новый статус
- `changed_by` — ID пользователя (null для создания клиентом)

Просмотр: `SELECT * FROM request_logs ORDER BY created_at DESC;`
```

---
**GitHub:** https://github.com/DimaBay/Requests.git
