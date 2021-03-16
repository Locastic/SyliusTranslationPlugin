<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\TranslationMigration;

use Locastic\SyliusTranslationPlugin\Model\Translation;

interface ExecutorInterface
{
    public function addTranslation(Translation $translation): void;

    public function execute(AbstractTranslationMigration $migration): void;
}
