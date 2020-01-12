<?php
/**
 *
 */
declare(strict_types=1);

namespace Mezzio\Doctrine\Container;

use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\CacheProvider;
use Doctrine\Common\Cache\ChainCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\MemcachedCache;
use Doctrine\Common\Cache\PredisCache;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\Common\Cache\WinCacheCache;
use Doctrine\Common\Cache\ZendDataCache;
use Psr\Container\ContainerInterface;
use Mezzio\Doctrine\Exception;

/**
 * Class CacheFactory
 *
 * @package Mezzio\Doctrine\Container
 */
class CacheFactory extends AbstractFactory
{
    /**
     * @inheritDoc
     */
    protected function createWithConfig(ContainerInterface $container, $configKey)
    {
        $config = $this->retrieveConfig($container, $configKey, 'cache');

        if (! array_key_exists('class', $config)) {
            throw new Exception\ConfigurationException('Missing "class" config key');
        }

        $instance = null;

        if (array_key_exists('instance', $config)) {
            $instance = is_string($config['instance']) ? $container->get($config['instance']) : $config['instance'];
        }

        switch ($config['class']) {
            case FilesystemCache::class:
                $cache = new $config['class']($config['directory']);
                break;

            case PredisCache::class:
                $cache = new $config['class']($instance);
                break;

            case ChainCache::class:
                $providers = array_map(
                    function ($provider) use ($container) {
                        return $this->createWithConfig($container, $provider);
                    },
                    is_array($config['providers']) ? $config['providers'] : []
                );
                $cache = new $config['class']($providers);
                break;

            default:
                $cache = new $config['class']();
        }

        if ($cache instanceof MemcachedCache) {
            $cache->setMemcached($instance);
        } elseif ($cache instanceof RedisCache) {
            $cache->setRedis($instance);
        }

        if ($cache instanceof CacheProvider && array_key_exists('namespace', $config)) {
            $cache->setNamespace($config['namespace']);
        }

        return $cache;
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultConfig($configKey)
    {
        switch ($configKey) {
            case 'apcu':
                return [
                    'class' => ApcuCache::class,
                    'namespace' => 'container-interop-doctrine',
                ];

            case 'array':
                return [
                    'class' => ArrayCache::class,
                    'namespace' => 'container-interop-doctrine',
                ];

            case 'filesystem':
                return [
                    'class' => FilesystemCache::class,
                    'directory' => 'data/cache/DoctrineCache',
                    'namespace' => 'container-interop-doctrine',
                ];

            case 'memcached':
                return [
                    'class' => MemcachedCache::class,
                    'instance' => 'my_memcached_alias',
                    'namespace' => 'container-interop-doctrine',
                ];

            case 'predis':
                return [
                    'class' => PredisCache::class,
                    'instance' => 'my_predis_alias',
                    'namespace' => 'container-interop-doctrine',
                ];

            case 'redis':
                return [
                    'class' => RedisCache::class,
                    'instance' => 'my_redis_alias',
                    'namespace' => 'container-interop-doctrine',
                ];

            case 'wincache':
                return [
                    'class' => WinCacheCache::class,
                    'namespace' => 'container-interop-doctrine',
                ];

            case 'zenddata':
                return [
                    'class' => ZendDataCache::class,
                    'namespace' => 'container-interop-doctrine',
                ];

            case 'chain':
                return [
                    'class' => ChainCache::class,
                    'namespace' => 'container-interop-doctrine',
                    'providers' => [],
                ];
        }
        return [];
    }
}
