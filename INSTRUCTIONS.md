# Инструкции запуска

## Этап 1 — Docker + Laravel

### Запуск

```bash
docker compose up -d --build
```

### Первоначальная настройка

```bash
# Создать .env (если не создан)
cp .env.example .env

# Сгенерировать ключ приложения
docker compose exec app php artisan key:generate

# Выполнить миграции (после Этапа 2)
docker compose exec app php artisan migrate

# Выполнить сиды (после Этапа 2)
docker compose exec app php artisan db:seed
```

### Доступ

Приложение доступно по адресу: **http://localhost:8000**
