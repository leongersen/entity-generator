<?php

namespace EntityGenerator\Command;

use EntityGenerator\Driver\PdoDriver;
use EntityGenerator\Generator\Entity;
use EntityGenerator\Generator\Generator;
use EntityGenerator\Generator\Namer;
use EntityGenerator\Generator\Renderer;
use EntityGenerator\Mapper\MySqlMapper;
use Nyholm\DSN;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class GenerateEntitiesCommand extends Command
{
    public function configure(): void
    {
        $this
            ->setName('entity-generator:generate')
            ->addOption('dsn', null, InputOption::VALUE_REQUIRED, 'DSN', getenv('DATABASE_URL'))
            ->addOption('namespace', null, InputOption::VALUE_REQUIRED, 'Namespace', 'App\\Entity')
            ->addOption('directory', null, InputOption::VALUE_REQUIRED, 'Output directory', 'src\\Entity')
            ->addOption('collection', null, InputOption::VALUE_REQUIRED, 'Collection class', '\\Doctrine\\Common\\Collections\\ArrayCollection');
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $namespace = $input->getOption('namespace');
        $collectionClass = $input->getOption('collection');
        $outputDirection = $input->getOption('directory');

        // TODO - When adding additional drivers/mappers, this will need to be done in a factory
        $dsn = new DSN($input->getOption('dsn'));
        $driver = new PdoDriver($dsn);
        $mapper = new MySqlMapper();

        $filesystem = new Filesystem();
        $filesystem->mkdir($outputDirection);

        $generator = new Generator($driver, $mapper);
        $renderer = new Renderer(__DIR__ . '/../../templates');

        /** @var Entity $entity */
        foreach ($generator->generateEntities() as $entity) {
            $fileName = $outputDirection . '/' . Namer::entityName($entity->getTable()) . '.php';
            $class = $renderer->render($entity, $namespace, $collectionClass);
            $filesystem->dumpFile($fileName, $class);
        }

        return 0;
    }
}
