<?php
/**
 *
 */

declare(strict_types=1);

namespace Mezzio\Doctrine\Container;

use Doctrine\Migrations\Configuration\Configuration;
use Psr\Container\ContainerInterface;

/**
 * Class MigrationsConfigurationFactory
 * @package Mezzio\Doctrine\Container
 */
class MigrationsConfigurationFactory extends AbstractFactory
{
    /**
     * @inheritDoc
     */
    protected function createWithConfig(ContainerInterface $container, $configKey)
    {
        $migrationsConfig = $this->retrieveConfig($container, $configKey, 'migrations_configuration');

        $configuration = new Configuration(
            $this->retrieveDependency(
                $container,
                $configKey,
                'connection',
                ConnectionFactory::class
            )
        );

        $configuration->setName($migrationsConfig['name']);
        $configuration->setMigrationsDirectory($migrationsConfig['directory']);
        $configuration->setMigrationsNamespace($migrationsConfig['namespace']);
        $configuration->setMigrationsTableName($migrationsConfig['table']);
        $configuration->registerMigrationsFromDirectory($migrationsConfig['directory']);

        if (method_exists($configuration, 'setMigrationsColumnName')) {
            $configuration->setMigrationsColumnName($migrationsConfig['column']);
        }
        return $configuration;
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultConfig($configKey)
    {
        return [
            'directory' => 'data/doctrine-orm/migrations',
            'name'      => 'Doctrine Database Migrations',
            'namespace' => 'Doctrine\Migrations',
            'table'     => 'migrations',
            'column'    => 'version',
        ];
    }
}