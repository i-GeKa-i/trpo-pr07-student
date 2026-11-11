<?php
declare(strict_types=1);

// Единая точка входа: собирает зависимости и передаёт запрос Router. Разметки здесь нет.

use App\Controller\CatalogController;
use App\Controller\ErrorController;
use App\Controller\HomeController;
use App\Core\Router;
use App\Core\RoutesCsv;
use App\Core\View;
use App\Model\PageRepository;

const ROOT = __DIR__ . '/..';

require ROOT . '/src/autoload.php';

$view = new View(ROOT . '/views');

try {
    $pages = new PageRepository(ROOT . '/dataset/pages.json');
    $view->share('storeName', $pages->all()['store_name']);

    $router = new Router(
        RoutesCsv::load(ROOT . '/dataset/routes.csv'),
        fn (string $name): object => match ($name) {
            'HomeController' => new HomeController($view, $pages),
            'CatalogController' => new CatalogController($view),
            'ErrorController' => new ErrorController($view),
            default => throw new RuntimeException("Неизвестный контроллер: $name"),
        },
        ['ErrorController', 'notFound'],
    );
    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
} catch (Throwable $e) {
    error_log((string) $e);
    (new ErrorController($view))->serverError();
}
