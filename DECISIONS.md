# Архитектурные решения

## 1. Почему Laravel

- Быстрая разработка, готовый стек (auth, validation, DB, sessions)
- Огромная экосистема, документация
- Blade — простая шаблонизация без SPA

## 2. Почему PostgreSQL

- ACID, надёжные транзакции
- Удобно для audit log и сложных запросов
- Хорошая поддержка в Laravel (pdo_pgsql)

## 3. Почему atomic update

- Race condition при «Взять в работу»: два мастера могут одновременно взять одну заявку
- `UPDATE ... WHERE id = ? AND status = 'assigned'` — одна атомарная операция
- Если `affected rows = 0` — кто-то уже взял, возвращаем 409

## 4. Почему Service слой

- Вся бизнес-логика в одном месте (RequestService)
- Контроллеры остаются тонкими, переиспользование логики
- Audit log и транзакции — в сервисе, не в контроллере

## 5. Почему audit log через таблицу

- Простота: обычные миграции и Eloquent
- Хранение в той же БД, не нужен отдельный сервис
- Удобно смотреть историю: `SELECT * FROM request_logs`

## 6. Почему session auth

- Нет SPA, нет необходимости в API-токенах
- Безопасность: HttpOnly cookies, CSRF
- Стандартный flow Laravel: login form → session

## 7. Почему Docker Compose

- Воспроизводимая среда: PHP, Nginx, PostgreSQL
- Один `docker compose up` — всё поднимается
- Упрощает деплой на любой VPS
