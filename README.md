# Задача
Необходимо создать приложение-задачник (ToDo list).
Фреймворки PHP использовать нельзя, библиотеки можно. Сложная архитектура не нужна.
В приложении нужно с помощью чистого PHP реализовать модель MVC. Решите поставленные задачи минимально необходимым количеством кода.
Верстка на bootstrap, к дизайну особых требований нет.

Задачи состоят из:
- имени пользователя;
- е-mail;
- текста задачи;

Стартовая страница - список задач с возможностью сортировки по имени пользователя, email и статусу.
- Вывод задач нужно сделать страницами по 3 штуки (с пагинацией).
- Видеть список задач и создавать новые может любой посетитель без авторизации.

Сделайте вход для администратора (логин "admin", пароль "123").
- Администратор имеет возможность редактировать текст задачи и поставить галочку о выполнении.
- Выполненные задачи в общем списке выводятся с соответствующей отметкой. 

[Требования к коду](https://beejee.ru/coding-challenge-requirements)


# Установка проекта

### Склонировать проект
```shell
git clone https://github.com/igoninkirill/todo_b.git
```
### Настроить под себя .env
```shell
cp .env.example .env
```
### Поднять контейнеры
```shell
docker-compose up
```
### Зайти в контейнер
```shell
docker compose exec app bash
```
### Установить зависимости
```shell
composer install
```
### Запустить миграции
```shell
php migrate.php
```

