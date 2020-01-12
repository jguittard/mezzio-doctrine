<?php
/**
 *
 */

declare(strict_types=1);

namespace Mezzio\Doctrine;

use Doctrine\Migrations\Configuration\Configuration as MigrationsConfiguration;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ConfigProvider
 *
 * @package Mezzio\Doctrine
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
            'validators' => $this->getValidators(),
        ];
    }

    /**
     * Return dependency mappings for this component.
     *
     * @return array
     */
    public function getDependencyConfig(): array
    {
        return [
            'factories' => [
                EntityManagerInterface::class => Container\EntityManagerFactory::class,
                Fixture\FixtureCommand::class => Container\FixtureCommandFactory::class,
                MigrationsConfiguration::class => Container\MigrationsConfigurationFactory::class,
                'doctrine.migrations_cmd.execute' => [Container\MigrationsCommandFactory::class, 'execute'],
                'doctrine.migrations_cmd.generate' => [Container\MigrationsCommandFactory::class, 'generate'],
                'doctrine.migrations_cmd.migrate' => [Container\MigrationsCommandFactory::class, 'migrate'],
                'doctrine.migrations_cmd.status' => [Container\MigrationsCommandFactory::class, 'status'],
                'doctrine.migrations_cmd.version' => [Container\MigrationsCommandFactory::class, 'version'],
                'doctrine.migrations_cmd.diff' => [Container\MigrationsCommandFactory::class, 'diff'],
                'doctrine.migrations_cmd.latest' => [Container\MigrationsCommandFactory::class, 'latest'],
            ],
        ];
    }

    public function getValidators(): array
    {
        return [
            'factories' => [
                Validator\NoObjectExists::class => Validator\NoObjectExistsFactory::class,
                Validator\ObjectExists::class => Validator\ObjectExistsFactory::class,
            ],
        ];
    }
}
