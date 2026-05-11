<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Cast a Postgres TEXT[]/CHAR(n)[] column to/from a PHP array of strings.
 *
 * @implements CastsAttributes<list<string>|null, mixed>
 */
class PostgresStringArray implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            return array_map('strval', $value);
        }

        $trimmed = trim((string) $value, '{}');

        if ($trimmed === '') {
            return [];
        }

        $items = str_getcsv($trimmed, ',', '"', '\\');

        return array_map(
            fn (string $item): string => trim($item),
            $items
        );
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if (! is_array($value)) {
            $value = [$value];
        }

        $escaped = array_map(function (mixed $item): string {
            $string = (string) $item;
            $escaped = str_replace(['\\', '"'], ['\\\\', '\\"'], $string);

            return '"'.$escaped.'"';
        }, $value);

        return '{'.implode(',', $escaped).'}';
    }
}
