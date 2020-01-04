<?php

namespace EntityGenerator\Generator;

use EntityGenerator\Driver\DriverInterface;
use EntityGenerator\Mapper\MapperInterface;

class Generator
{
    /** @var DriverInterface */
    private $driver;

    /** @var MapperInterface */
    private $mapper;

    public function __construct(DriverInterface $driver, MapperInterface $mapper)
    {
        $this->driver = $driver;
        $this->mapper = $mapper;
    }

    public function generateEntities(): array
    {
        $tables = $this->driver->getTables();
        /** @var array<string, Entity> $entities */
        $entities = [];

        foreach ($tables as $table) {
            $entities[$table] = $this->mapper->map($this->driver->getCreateStatement($table));
        }

        /**
         * @var string $table
         * @var Entity $entity
         */
        foreach ($entities as $table => $entity) {
            foreach ($entity->getReferences() as $reference) {
                if ($reference->isOwningSide()) {
                    /** @var Entity $referencedEntity */
                    $referencedEntity = $entities[$reference->getTable()];
                    $referencedEntity->addReference($reference->invert($table));
                }
            }
        }

        return array_values($entities);
    }
}
