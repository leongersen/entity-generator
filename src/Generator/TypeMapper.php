<?php

namespace EntityGenerator\Generator;

class TypeMapper
{
    public static function phpType(Column $column, bool $docBlock = false): string
    {
        $type = self::mapPhpType($column);

        if ($type && $docBlock && $column->isNullable()) {
            return $type . '|null';
        }

        if ($type && $column->isNullable()) {
            return '?' . $type;
        }

        return $type;
    }

    public static function doctrineType(Column $column): string
    {
        switch ($column->getType()) {
            case 'tinyint':
                return $column->getLength() === 1 ? 'boolean' : 'integer';
            case 'smallint':
            case 'bigint':
            case 'int':
                return 'integer';
            case 'decimal':
                return 'decimal';
            case 'double':
            case 'char':
            case 'varchar':
            case 'text':
            case 'mediumtext':
            case 'longtext':
                return 'string';
            case 'timestamp':
            case 'date':
            case 'datetime':
                return 'datetime';
        }

        return '';
    }

    private static function mapPhpType(Column $column): string
    {
        switch ($column->getType()) {
            case 'tinyint':
                return $column->getLength() === 1 ? 'bool' : 'int';
            case 'smallint':
            case 'bigint':
            case 'int':
                return 'int';
            case 'double':
            case 'decimal':
                return 'float';
            case 'char':
            case 'varchar':
            case 'text':
            case 'mediumtext':
            case 'longtext':
                return 'string';
            case 'timestamp':
            case 'date':
            case 'datetime':
                return '\\DateTime';
        }

        return '';
    }
}
