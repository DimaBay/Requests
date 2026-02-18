# PROMPTS — история запросов

## 2026-02-18

### Время
19:00–20:10 (Europe/Moscow)

### Полный текст запроса

Реализовать веб-сервис "Заявки в ремонтную службу" с полным функционалом: Docker Compose, защита от race condition, audit log, автотесты, деплой и т.д. Стек: PHP 8.x, Laravel, PostgreSQL, Blade, session auth. Поэтапная реализация по 13 этапам.

### Комментарий

Реализованы все этапы: Docker + Laravel, БД (миграции, модели, Enums, сиды), авторизация, создание заявки, панель диспетчера, панель мастера, atomic update для race condition (assigned → in_progress), 3 feature-теста, race_test.sh, README, docker-compose.prod.yml, DECISIONS.md, PROMPTS.md.

---

## 2026-02-18

### Время
20:10–20:20 (Europe/Moscow)

### Полный текст запроса

1. Git:
   - Инициализировать репозиторий Git в корне проекта (если ещё не создан)
   - Сделать commit всех исходников, исключая файлы из .dockerignore
   - Создать ветку main
   - Связать с удалённым репозиторием на GitHub: https://github.com/DimaBay/Requests.git
   - Пушнуть проект на GitHub
   - Убедиться, что .env и его варианты не пушатся

2. Docker Compose prod:
   - Подготовить docker-compose.prod.yml с сервисами app, db, nginx
   - Использовать environment variables из env.production.example
   - APP_ENV=production, APP_DEBUG=false
   - Volume для Postgres и storage
   - Проверить работоспособность локально (docker compose -f docker-compose.prod.yml up -d)

3. Деплой на облако:
   - Использовать Render.com / Railway или аналог
   - Поднять проект через Docker Compose
   - Добавить environment variables: APP_KEY, DB_PASSWORD и остальные необходимые
   - Проверить работу:
     - Форма создания заявки
     - Панель диспетчера и мастера
     - Действия: «Взять в работу», «Завершить»
     - Race condition через race_test.sh
     - Audit log
     - Тестовые пользователи

4. README.md:
   - Обновить раздел Deploy с инструкцией по облачному деплою
   - Указать публичный URL проекта
   - Инструкции по локальному запуску, тестовым пользователям и проверке race condition

5. PROMPTS.md:
   - Зафиксировать все запросы к AI с полным текстом, датой и временем
   - Формат Markdown

6. Автотесты:
   - Запустить `php artisan test` и убедиться, что все тесты проходят
   - Проверить, что race_test.sh корректно отрабатывает на деплое
   - Проверить Middleware по ролям

7. Результат:
   - Публичный URL проекта
   - GitHub репозиторий: https://github.com/DimaBay/Requests.git
   - README с инструкцией Deploy
   - PROMPTS.md
   - DECISIONS.md

### Комментарий

- Git репозиторий и пуш на GitHub выполнены, добавлен `/.gitignore` и проверено что `.env` не трекается.
- `docker-compose.prod.yml` приведён к требованиям (app/db/nginx, env.production.example, volume для Postgres и `storage`).
- README и PROMPTS обновлены под новые требования.
