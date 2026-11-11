# Практическая работа №7. PHP MVC интернет-магазин

Дисциплина «Технологии разработки программного обеспечения».
Каркас учебного интернет-магазина «Учебный магазин» на чистом PHP по схеме MVC: единая точка входа, `Router`, контроллеры, представления.

## Технологии

PHP 8.1+ (проверено на 8.4), без фреймворков и Composer, HTML + CSS.

## Запуск

Из корня репозитория:

```bash
php -S localhost:8000 -t public
```

Откройте http://localhost:8000/. Если порт занят — подставьте другой.
Проверка маршрутов и контроллеров без браузера:

```bash
php tests/RouterTest.php
```

## Структура

| Путь | Назначение |
|------|-----------|
| `public/index.php` | единая точка входа: собирает зависимости, передаёт запрос в `Router`, разметки не содержит |
| `public/css`, `public/assets` | стили и логотип (копия `dataset/assets/shop-logo.svg`, т.к. отдаётся веб-сервером) |
| `src/Core` | `Router`, `View` (render), `RoutesCsv` (чтение `routes.csv`) |
| `src/Controller` | `HomeController`, `CatalogController`, `ErrorController` |
| `src/Model` | `PageRepository` (читает `pages.json`), `Product` |
| `views` | `layout.php` (общая шапка/подвал) и шаблоны страниц |
| `data` | `products.php` — тестовый массив товаров для каталога (полноценная модель — в следующей работе) |
| `dataset` | исходные данные работы |

## Как используется dataset

- `dataset/routes.csv` — `RoutesCsv` читает его при каждом запросе, `Router` работает только по этим строкам.
- `dataset/pages.json` — `PageRepository` берёт название магазина, описание и преимущества для главной страницы, шапки и `/about`.
- `dataset/assets/shop-logo.svg` — логотип на главной (`public/assets/shop-logo.svg`).

## Маршруты (проверены вручную по всем строкам routes.csv)

| Маршрут | Контроллер → действие | Представление | HTTP |
|---------|----------------------|---------------|------|
| `GET /` | `HomeController::index` | `views/home/index.php` | 200 |
| `GET /catalog` | `CatalogController::index` | `views/catalog/index.php` | 200 |
| `GET /about` | `HomeController::about` | `views/home/about.php` | 200 |
| `GET /missing` | `ErrorController::notFound` | `views/error/404.php` | 404 |
| любой неизвестный путь или метод | `ErrorController::notFound` | `views/error/404.php` | 404 |
| сбой (нет/битый `pages.json`, `routes.csv`) | `ErrorController::serverError` | `views/error/500.php` | 500 |

Подробный протокол проверки — в [docs/check.md](docs/check.md).

## Разделение ответственности

- **Router** — только сопоставляет «метод + путь» с `Контроллер::действие`; HTML не формирует, зависимости контроллерам не выдумывает (их создаёт фабрика, переданная в конструктор).
- **Controller** — готовит данные и выбирает представление.
- **Model** — источник данных (`PageRepository`, `Product`).
- **View** — только HTML; JSON и CSV не открывает, маршрут не выбирает.

## Что изменялось по шагам (история Git)

1. `chore` — dataset, `.gitignore`.
2. `feat` — `Router`, `RoutesCsv`, `View`, автозагрузчик.
3. `feat` — `public/index.php`, layout, CSS (шаг 3: появилась единая точка входа, `Router` подключён к routes.csv).
4. `feat` — `HomeController`, `ErrorController`, `PageRepository`, представления главной, about, 404, 500.
5. `feat` — `CatalogController`, `Product`, каталог с тестовыми данными и пустым состоянием.
6. `test`, `docs` — самопроверка роутера, README и протокол проверки.

## Обработка ошибок и пустых данных

- Неизвестный URL или метод → собственная страница 404 со статусом 404, без PHP warning.
- Нет/повреждён `pages.json` или `routes.csv` → страница 500, детали пишутся в `error_log`, пользователю не показываются.
- Пустой список товаров → блок «Товаров пока нет»; пустые преимущества → «Преимущества пока не заполнены».
