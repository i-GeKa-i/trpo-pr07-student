<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;
use Throwable;

/** Единственное место, где данные превращаются в HTML: шаблон + layout. */
final class View
{
    /** @var array<string, mixed> данные, доступные всем шаблонам (например, название магазина) */
    private array $shared = [];

    public function __construct(private string $viewsPath)
    {
    }

    public function share(string $key, mixed $value): void
    {
        $this->shared[$key] = $value;
    }

    /** @param array<string, mixed> $data */
    public function render(string $template, array $data = [], int $status = 200): void
    {
        $vars = $data + $this->shared;
        $content = $this->capture($template, $vars);
        http_response_code($status);
        echo $this->capture('layout', $vars + ['content' => $content]);
    }

    public function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    /** @param array<string, mixed> $vars */
    private function capture(string $template, array $vars): string
    {
        $file = $this->viewsPath . '/' . $template . '.php';
        if (!is_file($file)) {
            throw new RuntimeException("Шаблон не найден: $template");
        }
        extract($vars, EXTR_SKIP);
        ob_start();
        try {
            require $file;
        } catch (Throwable $e) {
            ob_end_clean(); // не отдаём половину страницы
            throw $e;
        }
        return (string) ob_get_clean();
    }
}
