<?php /** @var \App\Core\View $this */ ?>
<section class="panel">
    <h1>О магазине «<?= $this->e($storeName) ?>»</h1>
    <?php if ($tagline !== ''): ?>
        <p class="muted"><?= $this->e($tagline) ?></p>
    <?php endif; ?>
    <h2>Как обрабатывается запрос</h2>
    <ol>
        <li><b>public/index.php</b> — единая точка входа получает URL.</li>
        <li><b>Router</b> — выбирает контроллер и действие по <code>routes.csv</code>.</li>
        <li><b>Controller</b> — готовит данные и выбирает представление.</li>
        <li><b>Model</b> — источник данных (в этой работе — <code>pages.json</code> и тестовый массив товаров).</li>
        <li><b>View</b> — отвечает только за HTML.</li>
    </ol>
</section>
