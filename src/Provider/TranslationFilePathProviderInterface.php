<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Provider;

use Locastic\SyliusTranslationPlugin\Model\TranslationValueInterface;

interface TranslationFilePathProviderInterface
{
    public function getFilePath(TranslationValueInterface $translationValue): string;
}
