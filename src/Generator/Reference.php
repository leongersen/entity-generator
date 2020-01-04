<?php

namespace EntityGenerator\Generator;

class Reference
{
    /** @var string */
    private $table;

    /** @var string */
    private $column;

    /** @var bool */
    private $nullable;

    /** @var string */
    private $referencedColumn;

    /** @var bool */
    private $isOwningSide;

    public function __construct(string $table, string $column, bool $nullable, string $referencedColumn, bool $isOwningSide = true)
    {
        $this->table = $table;
        $this->column = $column;
        $this->nullable = $nullable;
        $this->referencedColumn = $referencedColumn;
        $this->isOwningSide = $isOwningSide;
    }

    public function invert(string $table): Reference
    {
        return new self($table, $this->referencedColumn, false, $this->column, false);
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return string
     */
    public function getReferencedColumn(): string
    {
        return $this->referencedColumn;
    }

    /**
     * @return bool
     */
    public function isOwningSide(): bool
    {
        return $this->isOwningSide;
    }
}
