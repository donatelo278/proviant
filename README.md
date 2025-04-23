# 🧩 Task Management API (Symfony)

Простое REST API для управления задачами с валидацией данных.

## 📋 Требования

- PHP 8.1+
- Composer
- MySQL 5.7+
- Symfony CLI *(опционально)*

---

## 🚀 Установка

Клонируйте репозиторий:
```bash
git clone https://github.com/donatelo278/proviant.git
cd proviant
```

Установите зависимости:
```bash
composer install
```

Настройте базу данных:
```bash
# Создайте файл .env.local и укажите свои параметры БД
cp .env .env.local
```

Создайте базу данных и таблицы:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

---

## 🏃 Запуск

### Вариант 1: Встроенный сервер Symfony
```bash
symfony serve
```

API будет доступно по адресу:  
`http://localhost:8000/api/tasks`

### Вариант 2: OpenServer (Windows)

1. Настройте хост в OpenServer:  
   **Домен:** `proviant`  
   **Папка:** `/public`

2. Перезапустите OpenServer

---

## 📚 Документация API

### Основные эндпоинты:

- `GET /api/tasks` — Получить список всех задач  
- `POST /api/tasks` — Создать новую задачу  
- `PUT /api/tasks/{id}` — Обновить задачу  
- `DELETE /api/tasks/{id}` — Удалить задачу  

---

## 🛠 Технологии

- Symfony 6  
- Doctrine ORM  
- Validator Component  
- API Platform *(опционально)*



# 📘 Документация API для Postman

## 🚀 Основные эндпоинты

### 1. Получить список всех задач  
**Метод:** `GET`  
**URL:** `{{base_url}}/api/tasks`  
**Headers:**
```
Content-Type: application/json
```

**Пример успешного ответа (200 OK):**
```json
[
  {
    "id": 1,
    "title": "Купить продукты",
    "description": "Молоко, хлеб, яйца",
    "status": "не выполнена",
    "created_at": "2023-11-20 10:00:00"
  },
  {
    "id": 2,
    "title": "Сделать ДЗ",
    "description": "Математика, стр. 45",
    "status": "в процессе",
    "created_at": "2023-11-20 11:30:00"
  }
]
```

---

### 2. Создать новую задачу  
**Метод:** `POST`  
**URL:** `{{base_url}}/api/tasks`  
**Headers:**
```
Content-Type: application/json
```

**Тело запроса:**
```json
{
  "title": "Записаться к врачу",
  "description": "Терапевт на 15:00",
  "status": "не выполнена"
}
```

**Пример успешного ответа (201 Created):**
```json
{
  "id": 3,
  "title": "Записаться к врачу",
  "description": "Терапевт на 15:00",
  "status": "не выполнена",
  "created_at": "2023-11-20 14:45:00"
}
```

**Пример ошибки (400 Bad Request):**
```json
{
  "errors": {
    "title": "Название задачи обязательно"
  }
}
```

---

### 3. Обновить задачу  
**Метод:** `PUT`  
**URL:** `{{base_url}}/api/tasks/1`  
**Headers:**
```
Content-Type: application/json
```

**Тело запроса:**
```json
{
  "title": "Обновленное название",
  "status": "завершена"
}
```

**Пример успешного ответа (200 OK):**
```json
{
  "id": 1,
  "title": "Обновленное название",
  "description": "Молоко, хлеб, яйца",
  "status": "завершена",
  "created_at": "2023-11-20 10:00:00"
}
```

**Пример ошибки (404 Not Found):**
```json
{
  "error": "Task not found"
}
```

---

### 4. Удалить задачу  
**Метод:** `DELETE`  
**URL:** `{{base_url}}/api/tasks/1`  
**Headers:**
```
Content-Type: application/json
```

**Пример успешного ответа (200 OK):**
```json
{
  "message": "Task deleted successfully"
}
```

---

## 🛠 Настройка Postman

1. Создайте новую коллекцию **"Task API"**  
2. Добавьте переменную окружения:
```
base_url = http://localhost:8000
```
(или используйте ваш домен)

3. Импортируйте примеры запросов:
```json
{
  "info": {
    "_postman_id": "a1b2c3d4-e5f6-7890",
    "name": "Task API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Get All Tasks",
      "request": {
        "method": "GET",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "url": {
          "raw": "{{base_url}}/api/tasks",
          "host": ["{{base_url}}"],
          "path": ["api", "tasks"]
        }
      }
    },
    {
      "name": "Create Task",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n  \"title\": \"Новая задача\",\n  \"description\": \"Описание задачи\",\n  \"status\": \"не выполнена\"\n}"
        },
        "url": {
          "raw": "{{base_url}}/api/tasks",
          "host": ["{{base_url}}"],
          "path": ["api", "tasks"]
        }
      }
    }
  ]
}
```

---

## 🔍 Тестирование

1. Запустите сервер Symfony  
2. В Postman отправьте запросы по очереди:
   - Сначала создание задачи (`POST`)
   - Затем получение списка (`GET`)
   - Проверьте обновление (`PUT`)
   - И удаление (`DELETE`)

