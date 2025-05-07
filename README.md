# Smart Reading Application

## Требования
- Docker
- Docker Compose
- Make

## Сборка и запуск приложения

1. Клонируйте репозиторий:
```bash
git clone https://github.com/alleksandrb/smartreading.git
cd smartreading
```

2. Соберите и запустите контейнеры:
```bash
make build
make up
```

3. Установите зависимости:
```bash
make composer-install
```

4. Запустите миграции и сидеры базы данных:
```bash
make artisan-migrate
make artisan-seed
```

## Аутентификация

В приложении предустановлен тестовый пользователь:

```
Email: test@example.com
Пароль: password
```

Для входа используйте следующий эндпоинт:

```bash
POST /api/login
Content-Type: application/json

{
    "email": "test@example.com",
    "password": "password"
}
```

В ответе вы получите токен доступа, который нужно будет включать в заголовок Authorization для последующих запросов:
```
Authorization: Bearer <ваш_токен_доступа>
```

## Эндпоинты управления задачами

### Создание задачи
```bash
POST /api/tasks
Authorization: Bearer <ваш_токен_доступа>
Content-Type: application/json

{
    "title": "Название задачи",
    "description": "Описание задачи",
    "status": "new",
    "due_date": "2026-03-20",
    "user_id": 1
}
```

### Получение списка всех задач
```bash
GET /api/tasks
Authorization: Bearer <ваш_токен_доступа>
```

### Получение информации о задаче
```bash
GET /api/tasks/{id_задачи}
Authorization: Bearer <ваш_токен_доступа>
```

### Обновление задачи
```bash
PUT /api/tasks/{id_задачи}
Authorization: Bearer <ваш_токен_доступа>
Content-Type: application/json

{
    "title": "Обновленное название",
    "description": "Обновленное описание",
    "status": "in_progress",
    "due_date": "2026-03-21",
    "user_id": 1
}
```

### Удаление задачи
```bash
DELETE /api/tasks/{id_задачи}
Authorization: Bearer <ваш_токен_доступа>
```

## Часто используемые команды

- Просмотр логов: `make logs`
- Перезапуск контейнеров: `make restart`
- Остановка контейнеров: `make down`
- Доступ к оболочке контейнера: `make shell`
- Очистка кэша: `make artisan-cache-clear`

## Устранение неполадок

Если возникли проблемы:

1. Проверьте статус контейнеров:
```bash
make ps
```

2. Просмотрите логи приложения:
```bash
make logs
```

3. Очистите кэш Laravel:
```bash
make artisan-cache-clear
```

4. Перезапустите приложение:
```bash
make down
make up
```
