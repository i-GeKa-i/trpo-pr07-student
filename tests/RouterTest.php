<?php
declare(strict_types=1);

// Запуск: php tests/RouterTest.php  (код возврата 1, если проверка не прошла)

use App\Core\Router;
use App\Core\RoutesCsv;

const ROOT = __DIR__ . '/..';
require ROOT . '/src/autoload.php';

$calls = [];
$fake = new class($calls) {
    public function __construct(private array &$calls) {}
    public function index(): void { $this->calls[] = 'index'; }
    public function notFound(): void { $this->calls[] = 'notFound'; }
};
$router = new Router(
    [['method' => 'GET', 'path' => '/catalog', 'controller' => 'X', 'action' => 'index']],
    fn (string $name): object => $fake,
    ['X', 'notFound'],
);

$cases = [
    ['GET', '/catalog', 'index'],
    ['GET', '/catalog/', 'index'],      // завершающий слэш
    ['GET', '/catalog?page=2', 'index'], // query не мешает
    ['get', '/catalog', 'index'],        // регистр метода
    ['POST', '/catalog', 'notFound'],    // чужой метод
    ['GET', '/nope', 'notFound'],        // неизвестный путь
    ['GET', 'http:///', 'notFound'],     // битый URI
];
$failed = 0;
foreach ($cases as [$method, $uri, $expected]) {
    $calls = [];
    $router->dispatch($method, $uri);
    if ($calls !== [$expected]) {
        $failed++;
        echo "FAIL: $method $uri -> " . json_encode($calls) . ", ожидалось $expected\n";
    }
}

// Каждая строка dataset/routes.csv должна указывать на существующий метод контроллера.
$routes = RoutesCsv::load(ROOT . '/dataset/routes.csv');
if ($routes === []) {
    $failed++;
    echo "FAIL: routes.csv пуст\n";
}
foreach ($routes as $r) {
    if (!method_exists('App\\Controller\\' . $r['controller'], $r['action'])) {
        $failed++;
        echo "FAIL: {$r['controller']}::{$r['action']}() не существует\n";
    }
}

echo $failed === 0 ? 'OK: ' . count($cases) . ' кейсов роутера, ' . count($routes) . " маршрутов из routes.csv\n" : "Провалено: $failed\n";
exit($failed === 0 ? 0 : 1);
