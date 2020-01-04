<?php

namespace EntityGenerator\Generator;

class TypeMapper
{
    public static function phpType(Column $column, bool $docBlock = false): string
    {
        $type = self::mapType($column);

        if ($type && $docBlock && $column->isNullable()) {
            return $type . '|null';
        }

        if ($type && $column->isNullable()) {
            return '?' . $type;
        }

        return $type;
    }

    private static function mapType(Column $column): string
    {
        switch ($column->getType()) {
            case 'tinyint':
                return $column->getLength() === 1 ? 'bool' : 'int';
            case 'smallint':
            case 'bigint':
            case 'int':
                return 'int';
            case 'decimal':
                return 'float';
            case 'char':
            case 'varchar':
            case 'text':
            case 'mediumtext':
            case 'longtext':
                return 'string';
            case 'date':
            case 'datetime':
                return '\\DateTime';
        }

        return '';
    }
}
