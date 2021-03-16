<?php

declare(strict_types=1);

namespace Tests\Locastic\SyliusTranslationPlugin\TranslationMigration;

use Locastic\SyliusTranslationPlugin\Provider\ThemesProviderInterface;
use Locastic\SyliusTranslationPlugin\TranslationMigration\AbstractTranslationMigration;

final class Version20210315112000 extends AbstractTranslationMigration
{
    public function up(): void
    {
        $this->addTranslation('a.my_super.key', 'messages', 'de_DE', 'Meine wunderbach Wert!', ThemesProviderInterface::NAME_DEFAULT);
        $this->addTranslation('a.my_super.key_2', 'messages', 'de_DE', 'Meine wunderbach Wert!', ThemesProviderInterface::NAME_DEFAULT);
        $this->addTranslation('app.shop.ui.cart', 'messages', 'de_DE', 'Warenkorb', ThemesProviderInterface::NAME_DEFAULT);
        $this->addTranslation('app.shop.ui.cart_2', 'messages', 'de_DE', 'Warenkorb', ThemesProviderInterface::NAME_DEFAULT);
        $this->addTranslation('app.shop.ui.cart_3', 'messages', 'de_DE', 'Warenkorb', ThemesProviderInterface::NAME_DEFAULT);
    }
}
