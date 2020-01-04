<?php

namespace EntityGenerator\Mapper;

use EntityGenerator\Generator\Column;
use EntityGenerator\Generator\Entity;
use EntityGenerator\Generator\Reference;
use PHPSQLParser\PHPSQLParser;

class MySqlMapper implements MapperInterface
{
    /** @var PHPSQLParser */
    private $parser;

    public function __construct()
    {
        $this->parser = new PHPSQLParser();
    }

    public function map(string $createStatement): Entity
    {
        $parsed = $this->parser->parse($createStatement);
        $createDef = $parsed['TABLE']['create-def'];

        $entity = new Entity($parsed['TABLE']['no_quotes']['parts'][0]);

        foreach ($createDef['sub_tree'] as $tree) {
            switch ($tree['expr_type']) {
                case 'column-def':
                    $this->mapColumnDef($entity, $tree);
                    break;
                case 'primary-key':
                    $this->mapPrimaryKey($entity, $tree);
                    break;
                case 'unique-index':
                case 'index':
                    break;
                case 'foreign-key':
                    $this->mapForeignKey($entity, $tree);
                    break;
                default:
                    dd($tree);

            }
        }

        return $entity;
    }

    private function mapColumnDef(Entity $entity, array $columnDef): void
    {
        $name = null;
        $type = null;
        $comment = null;
        $nullable = false;
        $generated = false;
        $unsigned = false;
        $length = null;
        $precision = null;
        $scale = null;

        foreach ($columnDef['sub_tree'] as $tree) {
            switch ($tree['expr_type']) {
                case 'colref':
                    $name = $tree['no_quotes']['parts'][0];
                    break;
                case 'column-type':
                    $nullable = $tree['nullable'];
                    $generated = $tree['auto_inc'];
                    $comment = $tree['comment'] ?? null;

                    foreach ($tree['sub_tree'] as $subTree) {
                        switch ($subTree['expr_type']) {
                            case 'data-type':
                                $type = $subTree['base_expr'];
                                $length = $subTree['length'] ?? null;
                                $scale = $subTree['decimals'] ?? null;

                                if ($scale !== null) {
                                    $precision = $length;
                                    $length = null;
                                }

                                break;
                        }

                        if (isset($subTree['unsigned']) && strpos($type, 'int') !== false) {
                            $unsigned = $subTree['unsigned'];
                        }
                    }

                    break;
            }
        }

        $column = new Column($name, $type, $comment, $nullable, $generated, $unsigned, $length, $precision, $scale);

        $entity->addColumn($column);
    }

    private function mapPrimaryKey(Entity $entity, array $primaryKey): void
    {
        foreach ($primaryKey['sub_tree'] as $tree) {
            switch ($tree['expr_type']) {
                case 'column-list':
                    foreach ($tree['sub_tree'] as $subTree) {
                        switch ($subTree['expr_type']) {
                            case 'index-column':
                                $name = $subTree['no_quotes']['parts'][0];
                                $entity->getColumn($name)->markId();
                                break;
                        }
                    }

                    break;
            }
        }
    }

    private function mapForeignKey(Entity $entity, array $foreignKey): void
    {
        $column = null;

        foreach ($foreignKey['sub_tree'] as $tree) {
            switch ($tree['expr_type']) {
                case 'column-list':
                    foreach ($tree['sub_tree'] as $subTree) {
                        switch ($subTree['expr_type']) {
                            case 'index-column':
                                $name = $subTree['no_quotes']['parts'][0];
                                $column = $entity->getColumn($name);
                                $entity->removeColumn($name);
                                break;
                        }
                    }

                    break;
                case 'foreign-ref':
                    $this->mapForeignRef($entity, $column, $tree);
                    break;
            }
        }
    }

    private function mapForeignRef(Entity $entity, Column $column, array $foreignRef): void
    {
        $table = null;
        $referencedColumnName = null;

        foreach ($foreignRef['sub_tree'] as $tree) {
            switch ($tree['expr_type']) {
                case 'table':
                    $table = $tree['no_quotes']['parts'][0];

                    break;
                case 'column-list':
                    foreach ($tree['sub_tree'] as $subTree) {
                        switch ($subTree['expr_type']) {
                            case 'index-column':
                                $name = $subTree['no_quotes']['parts'][0];
                                $referencedColumnName = $name;
                                break;
                        }
                    }

                    break;
            }
        }

        $reference = new Reference($table, $column->getName(), $column->isNullable(), $referencedColumnName);

        $entity->addReference($reference);
    }
}
