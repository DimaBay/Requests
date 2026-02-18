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

## Docker команды

```bash
docker compose up -d --build   # Запуск
docker compose down            # Остановка
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan test
docker compose logs -f app     # Логи
```

---

## Deploy

### Продакшен на VPS

1. Клонировать репозиторий:
```bash
git clone https://github.com/DimaBay/Requests.git repair-requests
cd repair-requests
```

2. Настроить `.env`:
```bash
cp env.production.example .env
# Отредактировать: APP_DEBUG=false, APP_ENV=production, DB_PASSWORD и т.д.
```

3. Запуск:
```bash
docker compose -f docker-compose.prod.yml up -d --build
docker compose -f docker-compose.prod.yml exec app php artisan key:generate
docker compose -f docker-compose.prod.yml exec app php artisan migrate
docker compose -f docker-compose.prod.yml exec app php artisan db:seed
```

### Облачный деплой (Render.com / Railway)

#### Render.com

1. **Создать новый Web Service:**
   - Подключить репозиторий: `https://github.com/DimaBay/Requests.git`
   - Branch: `main`
   - Root Directory: `.`
   - Environment: `Docker`
   - Dockerfile Path: `Dockerfile`

2. **Добавить PostgreSQL Database:**
   - Создать новый PostgreSQL database
   - Запомнить connection string

3. **Настроить Environment Variables:**
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-app-name.onrender.com
   APP_KEY=base64:... (сгенерировать: php artisan key:generate --show)
   DB_CONNECTION=pgsql
   DB_HOST=<из connection string>
   DB_PORT=5432
   DB_DATABASE=<из connection string>
   DB_USERNAME=<из connection string>
   DB_PASSWORD=<из connection string>
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   PORT=80
   ```

4. **Добавить команды для первого запуска:**
   - Build Command: `docker compose -f docker-compose.prod.yml build`
   - Start Command: `docker compose -f docker-compose.prod.yml up`

5. **После деплоя выполнить миграции:**
   ```bash
   # Через Render Shell или SSH
   docker compose -f docker-compose.prod.yml exec app php artisan migrate
   docker compose -f docker-compose.prod.yml exec app php artisan db:seed
   ```

#### Railway

1. **Создать новый проект:**
   - Connect GitHub repo: `https://github.com/DimaBay/Requests.git`
   - Branch: `main`

2. **Добавить PostgreSQL:**
   - Add PostgreSQL service
   - Railway автоматически создаст переменные `DATABASE_URL`

3. **Настроить Environment Variables:**
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-app-name.up.railway.app
   APP_KEY=base64:... (сгенерировать локально)
   DB_CONNECTION=pgsql
   DB_HOST=${{Postgres.PGHOST}}
   DB_PORT=${{Postgres.PGPORT}}
   DB_DATABASE=${{Postgres.PGDATABASE}}
   DB_USERNAME=${{Postgres.PGUSER}}
   DB_PASSWORD=${{Postgres.PGPASSWORD}}
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   ```

4. **Настроить Deploy:**
   - Build Command: `docker compose -f docker-compose.prod.yml build`
   - Start Command: `docker compose -f docker-compose.prod.yml up`

5. **После деплоя выполнить миграции:**
   ```bash
   # Через Railway CLI или Dashboard → Service → Shell
   docker compose -f docker-compose.prod.yml exec app php artisan migrate
   docker compose -f docker-compose.prod.yml exec app php artisan db:seed
   ```

### Проверка работы после деплоя

1. **Форма создания заявки:**
   - Открыть `https://your-app-url.com/requests/create`
   - Создать тестовую заявку

2. **Панель диспетчера:**
   - Войти как `dispatcher@test.local` / `password`
   - Проверить список заявок, фильтры
   - Назначить мастера на заявку
   - Отменить заявку

3. **Панель мастера:**
   - Войти как `master1@test.local` / `password`
   - Проверить список назначенных заявок
   - Взять заявку в работу
   - Завершить заявку

4. **Race condition:**
   ```bash
   BASE_URL=https://your-app-url.com bash race_test.sh 3
   # Ожидается: один запрос 302, второй 409
   ```

5. **Audit log:**
   ```sql
   SELECT * FROM request_logs ORDER BY created_at DESC;
   ```

## Публичный URL

После деплоя обновить этот раздел с реальным URL проекта.

**GitHub:** https://github.com/DimaBay/Requests.git
