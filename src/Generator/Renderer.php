<?php

namespace EntityGenerator\Generator;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class Renderer
{
    /** @var Environment */
    private $twig;

    public function __construct(string $templatePath)
    {
        $loader = new FilesystemLoader($templatePath);
        $this->twig = new Environment($loader, [
            'cache' => false
        ]);

        $camelizeFilter = new TwigFilter('camelize', [Namer::class, 'camelize']);
        $entityNameFilter = new TwigFilter('entity_name', [Namer::class, 'entityName']);
        $relate = new TwigFilter('relate', [Namer::class, 'relate']);
        $pluralize = new TwigFilter('pluralize', [Namer::class, 'pluralize']);

        $phpTypeFilter = new TwigFilter('php_type', [TypeMapper::class, 'phpType']);
        $ucfirst = new TwigFilter('ucfirst', 'ucfirst');

        $this->twig->addFilter($phpTypeFilter);
        $this->twig->addFilter($camelizeFilter);
        $this->twig->addFilter($entityNameFilter);
        $this->twig->addFilter($relate);
        $this->twig->addFilter($ucfirst);
        $this->twig->addFilter($pluralize);
    }

    public function render(Entity $entity, string $namespace, string $collectionClass): string
    {
        return $this->twig->render('entity.php.twig', [
            'namespace' => $namespace,
            'entity' => $entity,
            'collectionClass' => $collectionClass
        ]);
    }
}
