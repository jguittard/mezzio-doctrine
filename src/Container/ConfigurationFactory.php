<?php
/**
 *
 */
declare(strict_types=1);

namespace Zend\Expressive\Doctrine\Container;

use Doctrine\ORM\Cache\CacheConfiguration;
use Doctrine\ORM\Cache\DefaultCacheFactory;
use Doctrine\ORM\Cache\RegionsConfiguration;
use Doctrine\ORM\Configuration;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Doctrine\Exception;

/**
 * Class ConfigurationFactory
 *
 * @package Zend\Expressive\Doctrine\Container
 */
class ConfigurationFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $configKey
     * @return Configuration|mixed
     * @throws Exception\ConfigurationException
     * @throws \Doctrine\ORM\ORMException
     */
    protected function createWithConfig(ContainerInterface $container, $configKey)
    {
        $config = $this->retrieveConfig($container, $configKey, 'configuration');

        $configuration = new Configuration();
        $configuration->setProxyDir($config['proxy_dir']);
        $configuration->setProxyNamespace($config['proxy_namespace']);
        $configuration->setAutoGenerateProxyClasses($config['auto_generate_proxy_classes']);
        $configuration->setEntityNamespaces($config['entity_namespaces']);
        $configuration->setCustomDatetimeFunctions($config['datetime_functions']);
        $configuration->setCustomStringFunctions($config['string_functions']);
        $configuration->setCustomNumericFunctions($config['numeric_functions']);
        $configuration->setCustomHydrationModes($config['custom_hydration_modes']);
        $configuration->setClassMetadataFactoryName($config['class_metadata_factory_name']);

        foreach ($config['named_queries'] as $name => $dql) {
            $configuration->addNamedQuery($name, $dql);
        }

        foreach ($config['named_native_queries'] as $name => $query) {
            $configuration->addNamedNativeQuery($name, $query['sql'], $query['rsm']);
        }

        foreach ($config['filters'] as $name => $className) {
            $configuration->addFilter($name, $className);
        }

        $configuration->setMetadataCacheImpl($this->retrieveDependency(
            $container,
            $config['metadata_cache'],
            'cache',
            CacheFactory::class
        ));
        $configuration->setQueryCacheImpl($this->retrieveDependency(
            $container,
            $config['query_cache'],
            'cache',
            CacheFactory::class
        ));
        $configuration->setResultCacheImpl($this->retrieveDependency(
            $container,
            $config['result_cache'],
            'cache',
            CacheFactory::class
        ));
        $configuration->setHydrationCacheImpl($this->retrieveDependency(
            $container,
            $config['hydration_cache'],
            'cache',
            CacheFactory::class
        ));
        $configuration->setMetadataDriverImpl($this->retrieveDependency(
            $container,
            $config['driver'],
            'driver',
            DriverFactory::class
        ));

        if (is_string($config['naming_strategy'])) {
            $configuration->setNamingStrategy($container->get($config['naming_strategy']));
        } elseif (null !== $config['naming_strategy']) {
            $configuration->setNamingStrategy($config['naming_strategy']);
        }

        if (is_string($config['repository_factory'])) {
            $configuration->setRepositoryFactory($container->get($config['repository_factory']));
        } elseif (null !== $config['repository_factory']) {
            $configuration->setRepositoryFactory($config['repository_factory']);
        }

        if (is_string($config['entity_listener_resolver'])) {
            $configuration->setEntityListenerResolver($container->get($config['entity_listener_resolver']));
        } elseif (null !== $config['entity_listener_resolver']) {
            $configuration->setEntityListenerResolver($config['entity_listener_resolver']);
        }

        if (null !== $config['default_repository_class_name']) {
            $configuration->setDefaultRepositoryClassName($config['default_repository_class_name']);
        }

        if ($config['second_level_cache']['enabled']) {
            $regionsConfig = new RegionsConfiguration(
                $config['second_level_cache']['default_lifetime'],
                $config['second_level_cache']['default_lock_lifetime']
            );
            foreach ($config['second_level_cache']['regions'] as $regionName => $regionConfig) {
                if (array_key_exists('lifetime', $regionConfig)) {
                    $regionsConfig->setLifetime($regionName, $regionConfig['lifetime']);
                }
                if (array_key_exists('lock_lifetime', $regionConfig)) {
                    $regionsConfig->setLockLifetime($regionName, $regionConfig['lock_lifetime']);
                }
            }
            if (null === $configuration->getResultCacheImpl()) {
                throw new Exception\ConfigurationException('Cache driver is null');
            }

            $cacheFactory = new DefaultCacheFactory($regionsConfig, $configuration->getResultCacheImpl());
            $cacheFactory->setFileLockRegionDirectory($config['second_level_cache']['file_lock_region_directory']);
            $cacheConfiguration = new CacheConfiguration();
            $cacheConfiguration->setCacheFactory($cacheFactory);
            $cacheConfiguration->setRegionsConfiguration($regionsConfig);
            $configuration->setSecondLevelCacheEnabled(true);
            $configuration->setSecondLevelCacheConfiguration($cacheConfiguration);
        }

        if (is_string($config['sql_logger'])) {
            $configuration->setSQLLogger($container->get($config['sql_logger']));
        } elseif (null !== $config['sql_logger']) {
            $configuration->setSQLLogger($config['sql_logger']);
        }

        return $configuration;
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultConfig($configKey)
    {
        return [
            'metadata_cache' => 'array',
            'query_cache' => 'array',
            'result_cache' => 'array',
            'hydration_cache' => 'array',
            'driver' => $configKey,
            'auto_generate_proxy_classes' => true,
            'proxy_dir' => 'data/cache/DoctrineEntityProxy',
            'proxy_namespace' => 'DoctrineEntityProxy',
            'entity_namespaces' => [],
            'datetime_functions' => [],
            'string_functions' => [],
            'numeric_functions' => [],
            'filters' => [],
            'named_queries' => [],
            'named_native_queries' => [],
            'custom_hydration_modes' => [],
            'naming_strategy' => null,
            'default_repository_class_name' => null,
            'repository_factory' => null,
            'class_metadata_factory_name' => null,
            'entity_listener_resolver' => null,
            'second_level_cache' => [
                'enabled' => false,
                'default_lifetime' => 3600,
                'default_lock_lifetime' => 60,
                'file_lock_region_directory' => '',
                'regtions' => [],
            ],
            'sql_logger' => null,
        ];
    }
}
