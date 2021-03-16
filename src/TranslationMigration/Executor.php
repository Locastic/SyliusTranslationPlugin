<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\TranslationMigration;

use Exception;
use Locastic\SyliusTranslationPlugin\Model\Translation;
use Locastic\SyliusTranslationPlugin\Model\TranslationMigrationInterface;
use Locastic\SyliusTranslationPlugin\Saver\TranslationValueSaverInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class Executor implements ExecutorInterface
{
    /** @var Translation[] */
    private array $translations = [];

    private TranslationValueSaverInterface $translationValueSaver;

    private FactoryInterface $translationMigrationFactory;

    private RepositoryInterface $translationMigrationRepository;

    public function __construct(
        TranslationValueSaverInterface $translationValueSaver,
        FactoryInterface $translationMigrationFactory,
        RepositoryInterface $translationMigrationRepository
    ) {
        $this->translationValueSaver = $translationValueSaver;
        $this->translationMigrationFactory = $translationMigrationFactory;
        $this->translationMigrationRepository = $translationMigrationRepository;
    }

    public function addTranslation(Translation $translation): void
    {
        $this->translations[] = $translation;
    }

    public function execute(AbstractTranslationMigration $migration): void
    {
        try {
            $this->executeMigration($migration);
        } catch (Exception $exception) {
            $this->skipMigration($migration);
        }
    }

    private function executeMigration(AbstractTranslationMigration $migration): void
    {
        if ($this->hasAlreadyBeenPlayed($migration)) {
            return;
        }
        $migration->up();

        foreach ($this->translations as $translation) {
            foreach ($translation->getValues() as $translationValue) {
                $this->translationValueSaver->saveTranslationValue($translationValue);
            }
        }

        $this->markVersion($migration);
    }

    private function skipMigration(AbstractTranslationMigration $migration): void
    {
        $this->markVersion($migration);
    }

    private function markVersion(AbstractTranslationMigration $migration): void
    {
        /** @var TranslationMigrationInterface $translationMigration */
        $translationMigration = $this->translationMigrationFactory->createNew();
        $translationMigration->setNumber($migration->getVersionNumber());

        $this->translationMigrationRepository->add($translationMigration);
    }

    private function hasAlreadyBeenPlayed(AbstractTranslationMigration $migration): bool
    {
        return $this->translationMigrationRepository->findOneBy(['number' => $migration->getVersionNumber()]) instanceof TranslationMigrationInterface;
    }
}
