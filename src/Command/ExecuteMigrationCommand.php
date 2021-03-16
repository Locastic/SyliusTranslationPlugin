<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Command;

use Doctrine\Migrations\Finder\GlobFinder;
use Locastic\SyliusTranslationPlugin\TranslationMigration\ExecutorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function sprintf;

final class ExecuteMigrationCommand extends Command
{
    /** @var string */
    public static $defaultName = 'locastic:sylius-translation:migration:migrate';

    private GlobFinder $translationFinder;

    private string $translationMigrationDirectory;

    private ExecutorInterface $migrationExecutor;

    protected OutputInterface $output;

    public function __construct(
        GlobFinder $translationFinder,
        string $translationMigrationDirectory,
        ExecutorInterface $migrationExecutor,
        string $name = null
    ) {
        parent::__construct($name);

        $this->translationFinder = $translationFinder;
        $this->translationMigrationDirectory = $translationMigrationDirectory;
        $this->migrationExecutor = $migrationExecutor;
    }

    protected function configure(): void
    {
        $this->setDescription('Execute migration of translations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->writeln('Starting to execute migration of translations', OutputInterface::VERBOSITY_NORMAL);

        $migrations = $this->translationFinder->findMigrations($this->translationMigrationDirectory);
        foreach ($migrations as $value) {
            $migration = new $value($this->migrationExecutor);

            $this->migrationExecutor->execute($migration);
        }

        return 0;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $this->output = $output;
    }

    protected function writeLn(string $message, int $level = OutputInterface::OUTPUT_NORMAL): void
    {
        $this->output->writeln(sprintf('[%s] %s', date('Y-m-d H:i:s'), $message), $level);
    }
}
