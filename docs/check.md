# Протокол проверки

Запуск: `php -S 127.0.0.1:8765 -t public`, PHP 8.4.27, Windows 10. Warning/Notice/Deprecated в логе сервера отсутствуют.

## Маршруты из dataset/routes.csv

| Строка routes.csv | Ожидание | Результат |
|-------------------|----------|-----------|
| `GET;/;HomeController;index` | 200, название, описание и 3 преимущества из pages.json | OK |
| `GET;/catalog;CatalogController;index` | 200, таблица из 3 тестовых товаров | OK |
| `GET;/about;HomeController;about` | 200, описание MVC-цикла | OK |
| `GET;/missing;ErrorController;notFound` | 404, собственная страница | OK |

## Дополнительные сценарии

| Сценарий | Результат |
|----------|-----------|
| `/nope` (нет в routes.csv) | 404, страница `views/error/404.php` |
| `/catalog/` (завершающий слэш) | 200, как `/catalog` |
| `/catalog?x=1` | 200, query игнорируется |
| `pages.json` временно переименован | 500, страница `views/error/500.php`, PHP warning не выводится |
| `php tests/RouterTest.php` | `OK: 7 кейсов роутера, 4 маршрутов из routes.csv` |

## Чек-лист перед сдачей

- [x] Проект запускается по README без правки кода
- [x] Файлы dataset используются в коде (routes.csv, pages.json, shop-logo.svg)
- [x] Нет PHP warnings и notices в обычном сценарии
- [x] Есть состояния для пустых/ошибочных данных (пустой каталог, нет преимуществ, 404, 500)
- [x] Имена файлов и классов отражают назначение
- [x] В Git несколько осмысленных коммитов
