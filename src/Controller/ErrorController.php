<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;

final class ErrorController
{
    public function __construct(private View $view)
    {
    }

    public function notFound(): void
    {
        $this->view->render('error/404', ['title' => 'Страница не найдена'], 404);
    }

    public function serverError(): void
    {
        $this->view->render('error/500', ['title' => 'Ошибка сервера'], 500);
    }
}
