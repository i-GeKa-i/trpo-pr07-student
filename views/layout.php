<?php /** @var \App\Core\View $this */ ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->e($title ?? 'Магазин') ?> — <?= $this->e($storeName ?? 'Интернет-магазин') ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header class="site-header">
    <a class="brand" href="/"><?= $this->e($storeName ?? 'Интернет-магазин') ?></a>
    <nav aria-label="Основная навигация">
        <a href="/">Главная</a>
        <a href="/catalog">Каталог</a>
        <a href="/about">О магазине</a>
    </nav>
</header>
<main class="wrap">
    <?= $content ?>
</main>
<footer class="site-footer">Практическая работа №7 · ТРПО · PHP MVC</footer>
</body>
</html>
