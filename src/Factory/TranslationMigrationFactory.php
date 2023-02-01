<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Factory;

use Locastic\SymfonyTranslationBundle\Factory\TranslationMigrationFactoryInterface;
use Locastic\SymfonyTranslationBundle\Model\TranslationMigrationInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class TranslationMigrationFactory implements FactoryInterface, TranslationMigrationFactoryInterface
{
    private string $className;

    public function __construct(
        string $className,
    ) {
        $this->className = $className;
    }

    public function createNew(): TranslationMigrationInterface
    {
        return new $this->className();
    }
}
