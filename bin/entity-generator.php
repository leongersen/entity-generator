<?php

require __DIR__ . '/../vendor/autoload.php';

use EntityGenerator\Command\GenerateEntitiesCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new GenerateEntitiesCommand());

$application->run();
