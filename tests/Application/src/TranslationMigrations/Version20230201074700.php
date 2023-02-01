<?php

declare(strict_types=1);

namespace Tests\Locastic\SyliusTranslationPlugin\TranslationMigrations;

use Locastic\SymfonyTranslationBundle\Provider\ThemesProviderInterface;
use Locastic\SymfonyTranslationBundle\TranslationMigration\AbstractTranslationMigration;

final class Version20230201074700 extends AbstractTranslationMigration
{
    public function up(): void
    {
        $this->addTranslation('test.translation', 'messages', 'en', 'This is a test translation', ThemesProviderInterface::NAME_DEFAULT);
    }
}
