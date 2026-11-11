<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\PageRepository;

final class HomeController
{
    public function __construct(private View $view, private PageRepository $pages)
    {
    }

    public function index(): void
    {
        $page = $this->pages->all();
        $this->view->render('home/index', [
            'title' => 'Главная',
            'tagline' => $page['tagline'],
            'benefits' => $page['benefits'],
        ]);
    }

    public function about(): void
    {
        $this->view->render('home/about', [
            'title' => 'О магазине',
            'tagline' => $this->pages->all()['tagline'],
        ]);
    }
}
