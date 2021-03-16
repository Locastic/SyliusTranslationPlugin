<?php

declare(strict_types=1);

namespace Tests\Locastic\SyliusTranslationPlugin\TranslationMigration;

use Locastic\SyliusTranslationPlugin\Provider\ThemesProviderInterface;
use Locastic\SyliusTranslationPlugin\TranslationMigration\AbstractTranslationMigration;

final class Version20210315112100 extends AbstractTranslationMigration
{
    public function up(): void
    {
        $this->addTranslation('a.my_super.key_2', 'messages', 'de_DE', 'Meine wunderbach Wert!', ThemesProviderInterface::NAME_DEFAULT);
    }
}
