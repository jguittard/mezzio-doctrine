<?php
/**
 *
 */
declare(strict_types=1);

namespace Mezzio\Doctrine\Container;

use Doctrine\DBAL\Driver\PDOMySql\Driver as PdoMysqlDriver;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;

/**
 * Class ConnectionFactory
 *
 * @package Mezzio\Doctrine\Container
 */
class ConnectionFactory extends AbstractFactory
{
    /**
     * @inheritDoc
     */
    protected function createWithConfig(ContainerInterface $container, $configKey)
    {
        $config = $this->retrieveConfig($container, $configKey, 'connection');
        $params = $config['params'] + [
                'driverClass' => $config['driver_class'],
                'wrapperClass' => $config['wrapper_class'],
                'pdo' => is_string($config['pdo']) ? $container->get($config['pdo']) : $config['pdo'],
            ];

        $connection = DriverManager::getConnection(
            $params,
            $this->retrieveDependency(
                $container,
                $config['configuration'],
                'configuration',
                ConfigurationFactory::class
            ),
            $this->retrieveDependency(
                $container,
                $config['event_manager'],
                'event_manager',
                EventManagerFactory::class
            )
        );

        $platform = $connection->getDatabasePlatform();

        foreach ($config['doctrine_mapping_types'] as $dbType => $doctrineType) {
            $platform->registerDoctrineTypeMapping($dbType, $doctrineType);
        }

        foreach ($config['doctrine_commented_types'] as $doctrineType) {
            $platform->markDoctrineTypeCommented($doctrineType);
        }

        return $connection;
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultConfig($configKey)
    {
        return [
            'driver_class' => PdoMysqlDriver::class,
            'wrapper_class' => null,
            'pdo' => null,
            'configuration' => $configKey,
            'event_manager' => $configKey,
            'params' => [],
            'doctrine_mapping_types' => [],
            'doctrine_commented_types' => [],
        ];
    }
}
