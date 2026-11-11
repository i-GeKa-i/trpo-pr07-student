<?php /** @var \App\Core\View $this */ ?>
<section class="panel">
    <h1>Каталог</h1>
    <?php if ($products === []): ?>
        <p class="empty">Товаров пока нет. Загляните позже.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr><th>ID</th><th>Название</th><th>Цена</th><th>Наличие</th></tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $this->e($product->id) ?></td>
                        <td><?= $this->e($product->title) ?></td>
                        <td><?= $this->e($product->priceFormatted()) ?></td>
                        <td>
                            <?php if ($product->inStock): ?>
                                <span class="pill">в наличии</span>
                            <?php else: ?>
                                <span class="pill off">нет в наличии</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
