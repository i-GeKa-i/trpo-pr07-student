<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\Product;

final class CatalogController
{
    public function __construct(private View $view)
    {
    }

    public function index(): void
    {
        /** @var list<array{id:int,title:string,price:int,in_stock:bool}> $rows */
        $rows = require ROOT . '/data/products.php';
        $products = array_map(
            fn (array $r): Product => new Product($r['id'], $r['title'], $r['price'], $r['in_stock']),
            $rows,
        );

        $this->view->render('catalog/index', ['title' => 'Каталог', 'products' => $products]);
    }
}
