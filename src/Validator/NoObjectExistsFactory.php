<?php
/**
 *
 */
declare(strict_types=1);

namespace Zend\Expressive\Doctrine\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

/**
 * Class NoObjectExistsFactory
 *
 * @package Zend\Expressive\Doctrine\Validator
 */
class NoObjectExistsFactory
{
    /**
     * @var array
     */
    protected $options = [];

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (isset($options['entity_class'])) {
            $objectRepository = $container
                ->get(EntityManagerInterface::class)
                ->getRepository($options['entity_class']);
            $options = array_merge($options, ['object_repository' => $objectRepository]);
        }
        return new NoObjectExists($options);
    }

    /**
     * Allow injecting options at build time; required for v2 compatibility.
     *
     * @param array $options
     */
    public function setCreationOptions(array $options)
    {
        $this->options = $options;
    }
}
