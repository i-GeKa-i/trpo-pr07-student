<?php /** @var \App\Core\View $this */ ?>
<section class="hero">
    <div>
        <span class="pill">PHP MVC</span>
        <h1><?= $this->e($storeName) ?></h1>
        <?php if ($tagline !== ''): ?>
            <p class="muted"><?= $this->e($tagline) ?></p>
        <?php endif; ?>
        <a class="btn" href="/catalog">Перейти в каталог</a>
    </div>
    <img src="/assets/shop-logo.svg" alt="Логотип: <?= $this->e($storeName) ?>" width="320">
</section>

<section aria-labelledby="benefits-title">
    <h2 id="benefits-title">Преимущества</h2>
    <?php if ($benefits === []): ?>
        <p class="empty">Преимущества пока не заполнены.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($benefits as $benefit): ?>
                <div class="card"><?= $this->e($benefit) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
