# PROMPTS — история запросов к AI

## 2025-02-18

### Время
~ (текущая сессия)

### Полный текст запроса

Реализовать веб-сервис "Заявки в ремонтную службу" с полным функционалом: Docker Compose, защита от race condition, audit log, автотесты, деплой и т.д. Стек: PHP 8.x, Laravel, PostgreSQL, Blade, session auth. Поэтапная реализация по 13 этапам.

### Комментарий

Реализованы все этапы: Docker + Laravel, БД (миграции, модели, Enums, сиды), авторизация, создание заявки, панель диспетчера, панель мастера, atomic update для race condition (assigned → in_progress), 3 feature-теста, race_test.sh, README, docker-compose.prod.yml, DECISIONS.md, PROMPTS.md.

---

## 2025-02-18 (вторая сессия)

### Время
~ (текущая сессия)

### Полный текст запроса

Следующие задачи:

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

6. Результат:
   - Публичный URL проекта
   - GitHub репозиторий: https://github.com/DimaBay/Requests.git
   - README с инструкцией Deploy
   - PROMPTS.md
   - DECISIONS.md

### Комментарий

Выполнено:
- Git репозиторий инициализирован, связан с GitHub, код запушен
- .gitignore настроен, .env не пушится
- docker-compose.prod.yml подготовлен с volumes для postgres и storage
- README.md обновлён с инструкциями по деплою на Render.com и Railway
- PROMPTS.md обновлён с историей запросов
- Деплой на облако требует ручной настройки через веб-интерфейс платформ
