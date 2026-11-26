<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;


final class RoutesCsv
{
    private const COLUMNS = ['method', 'path', 'controller', 'action'];


    public static function load(string $file): array
    {
        $handle = @fopen($file, 'r');
        if ($handle === false) {
            throw new RuntimeException("Не удалось открыть файл маршрутов: $file");
        }

        $header = fgetcsv($handle, null, ';', '"', '');
        if (is_array($header) && isset($header[0])) {
            $header[0] = ltrim($header[0], "\xEF\xBB\xBF"); 
        }
        if ($header !== self::COLUMNS) {
            fclose($handle);
            throw new RuntimeException('Неверный заголовок routes.csv, ожидается: ' . implode(';', self::COLUMNS));
        }

        $routes = [];
        while (($row = fgetcsv($handle, null, ';', '"', '')) !== false) {
            if ($row === [null]) {
                continue; 
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
