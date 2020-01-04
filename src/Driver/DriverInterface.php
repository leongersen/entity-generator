<?php

namespace EntityGenerator\Driver;

interface DriverInterface
{
    /**
     * @return iterable<string>
     */
    public function getTables(): iterable;

    public function getCreateStatement(string $tableName): string;
}
