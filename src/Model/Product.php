<?php
declare(strict_types=1);

namespace App\Model;

final class Product
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly int $price,
        public readonly bool $inStock = true,
    ) {
    }

    public function priceFormatted(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' ₽';
    }
}
