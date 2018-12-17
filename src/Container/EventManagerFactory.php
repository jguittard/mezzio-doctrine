<?php
/**
 *
 */
declare(strict_types=1);

namespace Zend\Expressive\Doctrine\Container;

use Doctrine\Common\EventManager;
use Doctrine\Common\EventSubscriber;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Doctrine\Exception;

/**
 * Class EventManagerFactory
 *
 * @package Zend\Expressive\Doctrine\Container
 */
class EventManagerFactory extends AbstractFactory
{
    /**
     * @inheritDoc
     */
    protected function createWithConfig(ContainerInterface $container, $configKey)
    {
        $config = $this->retrieveConfig($container, $configKey, 'event_manager');
        $eventManager = new EventManager();

        foreach ($config['subscribers'] as $subscriber) {
            if (is_object($subscriber)) {
                $subscriberName = get_class($subscriber);
            } elseif (! is_string($subscriber)) {
                $subscriberName = gettype($subscriber);
            } elseif ($container->has($subscriber)) {
                $subscriber = $container->get($subscriber);
                $subscriberName = $subscriber;
            } elseif (class_exists($subscriber)) {
                $subscriber = new $subscriber();
                $subscriberName = get_class($subscriber);
            } else {
                $subscriberName = $subscriber;
            }

            if (! $subscriber instanceof EventSubscriber) {
                throw new Exception\DomainException(sprintf(
                    'Invalid event subscriber "%s" given, mut be a dependency name, class name or an instance'
                    . ' implementing %s',
                    $subscriberName,
                    EventSubscriber::class
                ));
            }

            $eventManager->addEventSubscriber($subscriber);
        }

        return $eventManager;
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultConfig($configKey)
    {
        return [
            'subscribers' => [],
        ];
    }
}
