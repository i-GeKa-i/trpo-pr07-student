<?php
declare(strict_types=1);

namespace App\Model;

use JsonException;
use RuntimeException;

/** Читает dataset/pages.json: название магазина, описание, преимущества. */
final class PageRepository
{
    /** @var array{store_name:string,tagline:string,benefits:list<string>}|null */
    private ?array $cache = null;

    public function __construct(private string $file)
    {
    }

    /** @return array{store_name:string,tagline:string,benefits:list<string>} */
    public function all(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        $json = @file_get_contents($this->file);
        if ($json === false) {
            throw new RuntimeException("Не удалось прочитать {$this->file}");
        }
        try {
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException("Некорректный JSON в {$this->file}: " . $e->getMessage(), 0, $e);
        }
        if (!is_array($data) || !is_string($data['store_name'] ?? null) || trim($data['store_name']) === '') {
            throw new RuntimeException('В pages.json нет поля store_name');
        }

        return $this->cache = [
            'store_name' => $data['store_name'],
            'tagline' => is_string($data['tagline'] ?? null) ? $data['tagline'] : '',
            'benefits' => array_values(array_filter($data['benefits'] ?? [], 'is_string')),
        ];
    }
}
