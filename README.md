# Тестовое задание: PHP + Smarty + MySQL блог

Простой блог на чистом PHP без фреймворков, с MySQL и шаблонизатором Smarty.

## Что реализовано

- Главная страница:
  - вывод категорий, в которых есть статьи;
  - по 3 последних статьи в каждой категории;
  - кнопка `Все статьи`.
- Страница категории:
  - название и описание категории;
  - список статей;
  - сортировка (`по дате`, `по просмотрам`);
  - пагинация.
- Страница статьи:
  - полная информация о статье;
  - 3 похожие статьи по совпадающим категориям.
- Сидинг:
  - создание таблиц;
  - заполнение тестовыми категориями и статьями.
- Дополнительно:
  - Docker-окружение;
  - SCSS-исходник для стилей;
  - Makefile для быстрых команд запуска.

## Стек

- PHP 8.1+
- Smarty
- MySQL
- Docker
- SCSS

## Быстрый запуск через Makefile

```bash
make install
make up
make seed
```

Сайт будет доступен по адресу: [http://localhost:8080](http://localhost:8080)

Полезные команды:

```bash
make help
make ps
make logs
make down
make local-serve
```

## Запуск через Docker без Makefile

1. Установить зависимости:

```bash
composer install
```

2. Поднять контейнеры:

```bash
docker compose up -d --build
```

3. Запустить сидинг:

```bash
docker compose exec app php database/seed.php
```

4. Открыть сайт: [http://localhost:8080](http://localhost:8080)

MySQL снаружи контейнера доступен на `127.0.0.1:3307`.

Доступы Docker-БД:

```text
database: blog
user: blog
password: blog
```

Если Docker Hub временно не скачивает образ `php:8.3-apache`, можно сначала выполнить:

```bash
docker pull php:8.3-apache
```

и затем повторить `docker compose up -d --build`.

## Запуск без Docker

Для локального запуска нужны PHP 8.1+, Composer и доступная MySQL-БД.

1. Создать `.env`:

```bash
cp .env.example .env
```

2. Указать в `.env` локальные доступы к MySQL, например:

```env
APP_ENV=local
APP_URL=http://localhost:8080

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=blog
DB_USER=root
DB_PASSWORD=
```

3. Установить зависимости:

```bash
composer install
```

4. Создать таблицы и тестовые данные:

```bash
php database/seed.php
```

или

```bash
composer seed
```

5. Запустить встроенный PHP-сервер:

```bash
php -S localhost:8080 -t public
```

Сайт: [http://localhost:8080](http://localhost:8080)

## Структура

- `public/` — точка входа и статика
- `src/Core` — ядро (Router, Database, View, Env)
- `src/Controllers` — контроллеры страниц
- `src/Repositories` — запросы в MySQL
- `templates/` — Smarty шаблоны
- `database/` — SQL схема и сидер
- `assets/scss/` — SCSS исходник
- `public/assets/css/` — готовый CSS

## Команды

```bash
make help
make lint
make seed
make local-seed
```

## Использование ИИ

При выполнении задания использовался ИИ как помощник для проверки структуры проекта, формулировки README, подготовки тестовых данных и ускорения рутинных правок. Архитектура проекта, разбор требований, интеграция PHP/MySQL/Smarty и финальная проверка решения выполнялись осознанно с пониманием кода.
