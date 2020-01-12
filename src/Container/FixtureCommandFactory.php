<?php
/**
 *
 */
declare(strict_types=1);

namespace Mezzio\Doctrine\Container;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Mezzio\Doctrine\Fixture\FixtureCommand;

/**
 * Class FixtureCommandFactory
 *
 * @package Mezzio\Doctrine\Container
 */
class FixtureCommandFactory
{
    /**
     * @param ContainerInterface $container
     * @return FixtureCommand
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $paths = [];

        if (isset($config['doctrine']['fixture']) && is_array($config['doctrine']['fixture'])) {
            $paths = $config['doctrine']['fixture'];
        }

        $em = $container->get(EntityManagerInterface::class);
        $importCommand = new FixtureCommand($em);
        $importCommand->setPaths($paths);

        return $importCommand;
    }
}
