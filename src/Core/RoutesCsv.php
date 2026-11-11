<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/** Читает dataset/routes.csv (разделитель «;», колонки method;path;controller;action). */
final class RoutesCsv
{
    private const COLUMNS = ['method', 'path', 'controller', 'action'];

    /** @return list<array{method:string,path:string,controller:string,action:string}> */
    public static function load(string $file): array
    {
        $handle = @fopen($file, 'r');
        if ($handle === false) {
            throw new RuntimeException("Не удалось открыть файл маршрутов: $file");
        }

        $header = fgetcsv($handle, null, ';', '"', '');
        if (is_array($header) && isset($header[0])) {
            $header[0] = ltrim($header[0], "\xEF\xBB\xBF"); // файл из dataset начинается с BOM
        }
        if ($header !== self::COLUMNS) {
            fclose($handle);
            throw new RuntimeException('Неверный заголовок routes.csv, ожидается: ' . implode(';', self::COLUMNS));
        }

        $routes = [];
        while (($row = fgetcsv($handle, null, ';', '"', '')) !== false) {
            if ($row === [null]) {
                continue; // пустая строка
            }
            if (count($row) !== count(self::COLUMNS)) {
                fclose($handle);
                throw new RuntimeException('Неверная строка в routes.csv: ' . implode(';', $row));
            }
            $routes[] = array_combine(self::COLUMNS, $row);
        }
        fclose($handle);

        return $routes;
    }
}
