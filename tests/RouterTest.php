<?php
declare(strict_types=1);


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
    ['GET', '/catalog/', 'index'],      
    ['GET', '/catalog?page=2', 'index'], 
    ['get', '/catalog', 'index'],        
    ['POST', '/catalog', 'notFound'],    
    ['GET', '/nope', 'notFound'],        
    ['GET', 'http:///', 'notFound'],     
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
