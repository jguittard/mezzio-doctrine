<?php
/**
 *
 */
declare(strict_types=1);

namespace Mezzio\Doctrine\Container;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

/**
 * Class EntityManagerFactory
 *
 * @package Mezzio\Doctrine\Container
 */
class EntityManagerFactory extends AbstractFactory
{
    /**
     * @var bool
     */
    private static $areTypesRegistered = false;

    /**
     * @inheritDoc
     */
    protected function createWithConfig(ContainerInterface $container, $configKey)
    {
        $this->registerTypes($container);
        $config = $this->retrieveConfig($container, $configKey, 'entity_manager');

        return EntityManager::create(
            $this->retrieveDependency(
                $container,
                $config['connection'],
                'connection',
                ConnectionFactory::class
            ),
            $this->retrieveDependency(
                $container,
                $config['configuration'],
                'configuration',
                ConfigurationFactory::class
            )
        );
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultConfig($configKey)
    {
        return [
            'connection' => $configKey,
            'configuration' => $configKey,
        ];
    }

    /**
     * Registers all declared typed, if not already done.
     *
     * @param ContainerInterface $container
     * @throws \Doctrine\DBAL\DBALException
     */
    private function registerTypes(ContainerInterface $container)
    {
        if (self::$areTypesRegistered) {
            return;
        }

        $applicationConfig = $container->has('config') ? $container->get('config') : [];
        $doctrineConfig = array_key_exists('doctrine', $applicationConfig) ? $applicationConfig['doctrine'] : [];
        $typesConfig = array_key_exists('types', $doctrineConfig) ? $doctrineConfig['types'] : [];

        foreach ($typesConfig as $name => $className) {
            if (Type::hasType($name)) {
                Type::overrideType($name, $className);
                continue;
            }
            Type::addType($name, $className);
        }

        self::$areTypesRegistered = true;
    }
}
