<?php
/**
 *
 */
declare(strict_types=1);

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Helper\HelperSet;

require 'vendor/autoload.php';

$container = require 'config/container.php';

$entityManager = $container->get(Doctrine\ORM\EntityManagerInterface::class);

$helperSet = new HelperSet([
    'em' => new Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager),
    'connection' => new Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($entityManager->getConnection()),
]);

$commands = [
    $container->get(Zend\Expressive\Doctrine\Fixture\FixtureCommand::class),
    $container->get('doctrine.migrations_cmd.execute'),
    $container->get('doctrine.migrations_cmd.generate'),
    $container->get('doctrine.migrations_cmd.migrate'),
    $container->get('doctrine.migrations_cmd.status'),
    $container->get('doctrine.migrations_cmd.version'),
    $container->get('doctrine.migrations_cmd.diff'),
    $container->get('doctrine.migrations_cmd.latest'),
];

ConsoleRunner::run($helperSet, $commands);
