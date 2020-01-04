<?php

namespace EntityGenerator\Generator;

class Entity
{
    /** @var string */
    private $table;

    /** @var Column[] */
    private $columns = [];

    /** @var Reference[] */
    private $references = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getColumn(string $name): Column
    {
        return $this->columns[$name];
    }

    public function removeColumn(string $name): void
    {
        unset($this->columns[$name]);
    }

    public function addColumn(Column $column): void
    {
        $this->columns[$column->getName()] = $column;
    }

    /**
     * @return Reference[]
     */
    public function getReferences(): array
    {
        return $this->references;
    }

    public function addReference(Reference $reference): void
    {
        $this->references[] = $reference;
    }
}
