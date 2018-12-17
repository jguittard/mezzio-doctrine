<?php
/**
 *
 */

declare(strict_types=1);

namespace Zend\Expressive\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ConfigProvider
 *
 * @package Zend\Expressive\Doctrine
 */
class ConfigProvider
{
    /**
     * Return configuration for this component.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Return dependency mappings for this component.
     *
     * @return array
     */
    public function getDependencyConfig()
    {
        return [
            'factories' => [
                EntityManagerInterface::class => Container\EntityManagerFactory::class,
            ],
        ];
    }
}
