<?php
declare(strict_types=1);

namespace App\Core;

use Closure;
use RuntimeException;

/** Сопоставляет «метод + путь» с действием контроллера. HTML не формирует. */
final class Router
{
    /**
     * @param list<array{method:string,path:string,controller:string,action:string}> $routes из routes.csv
     * @param Closure(string): object $makeController создаёт контроллер по имени (передаёт ему зависимости)
     * @param array{0:string,1:string} $notFound контроллер и действие для неизвестного пути
     */
    public function __construct(
        private array $routes,
        private Closure $makeController,
        private array $notFound,
    ) {
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $path = $this->normalize($uri);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                $this->call($route['controller'], $route['action']);
                return;
            }
        }
        $this->call(...$this->notFound);
    }

    private function call(string $controller, string $action): void
    {
        $object = ($this->makeController)($controller);
        if (!method_exists($object, $action)) {
            throw new RuntimeException("У $controller нет метода $action()");
        }
        $object->$action();
    }

    /** '/catalog/?x=1' -> '/catalog'; битый URI -> '' (не совпадёт ни с одним маршрутом). */
    private function normalize(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);
        if (!is_string($path)) {
            return '';
        }
        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
