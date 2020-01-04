<?php

namespace EntityGenerator\Generator;

class Namer
{
    public static function entityName(string $dbTable): string
    {
        return self::upperCamelize($dbTable);
    }

    protected static function upperCamelize(string $string): string
    {
        return implode(array_map('ucfirst', explode('_', $string)));
    }

    public static function relate(string $column): string
    {
        return str_replace('_id', '', $column);
    }

    public static function camelize(string $string): string
    {
        return lcfirst(self::upperCamelize($string));
    }

    public static function pluralize(string $string): string
    {
        if (mb_substr($string, -1) === 'y') {
            $string = mb_substr($string, 0, -1) . 'ie';
        }

        return mb_substr($string, -1) === 's' ? $string : $string . 's';
    }
}
