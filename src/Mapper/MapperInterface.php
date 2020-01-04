<?php

namespace EntityGenerator\Mapper;

use EntityGenerator\Generator\Entity;

interface MapperInterface
{
    public function map(string $createStatement): Entity;
}
