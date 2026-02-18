# Инструкция по деплою

## Локальная проверка docker-compose.prod.yml

```bash
# 1. Создать .env из примера
cp env.production.example .env

# 2. Отредактировать .env (установить DB_PASSWORD и другие переменные)
# DB_PASSWORD=your_strong_password
# APP_URL=http://localhost

# 3. Запустить продакшен конфигурацию
docker compose -f docker-compose.prod.yml up -d --build

# 4. Сгенерировать ключ приложения
docker compose -f docker-compose.prod.yml exec app php artisan key:generate

# 5. Выполнить миграции
docker compose -f docker-compose.prod.yml exec app php artisan migrate

# 6. Заполнить тестовыми данными
docker compose -f docker-compose.prod.yml exec app php artisan db:seed

# 7. Проверить работу
# Открыть http://localhost
```

## Облачный деплой

См. раздел "Deploy" в README.md для инструкций по Render.com и Railway.
