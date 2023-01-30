<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Model;

use Locastic\SymfonyTranslationBundle\Model\TranslationMigration as BaseTranslationMigration;
use Sylius\Component\Resource\Model\ResourceInterface;

class TranslationMigration extends BaseTranslationMigration implements ResourceInterface
{
}
