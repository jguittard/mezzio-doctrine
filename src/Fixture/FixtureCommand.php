<?php
/**
 *
 */
declare(strict_types=1);

namespace Mezzio\Doctrine\Fixture;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class FixtureCommand
 *
 * @package Mezzio\Doctrine\Fixture
 */
class FixtureCommand extends Command
{
    const PURGE_MODE_TRUNCATE = 2;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var array
     */
    private $paths;

    /**
     * FixtureCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct('data-fixture:import');
    }

    /**
     * @param array $paths
     * @return FixtureCommand
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;
        return $this;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Import Data Fixtures')
            ->setHelp(
                <<<EOT
                Executes the data-fixtures import command
EOT
            )
            ->addOption('append', null, InputOption::VALUE_NONE, 'Append data to existing data.')
            ->addOption('purge-with-truncate', null, InputOption::VALUE_NONE, 'Truncate tables before inserting data');
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     *
     * @throws LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loader = new Loader();
        $purger = new ORMPurger();

        if ($input->getOption('purge-with-truncate')) {
            $purger->setPurgeMode(self::PURGE_MODE_TRUNCATE);
        }

        $executor = new ORMExecutor($this->entityManager, $purger);

        foreach ($this->paths as $key => $value) {
            $loader->loadFromDirectory($value);
        }

        $executor->execute($loader->getFixtures(), $input->getOption('append'));
        $output->writeln("<info>Fixtures have been loaded.</info>");
        return 0;
    }
}
