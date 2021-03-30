<?php

declare(strict_types=1);

namespace Tests\Locastic\SyliusTranslationPlugin\TranslationMigrations;

use Locastic\SyliusTranslationPlugin\Provider\ThemesProviderInterface;
use Locastic\SyliusTranslationPlugin\TranslationMigration\AbstractTranslationMigration;

final class Version20210315112200 extends AbstractTranslationMigration
{
    public function up(): void
    {
        $this->addTranslation('a.my_super.key', 'messages', 'de_DE', 'Meine wunderbach Wert! Override', ThemesProviderInterface::NAME_DEFAULT);
    }
}
