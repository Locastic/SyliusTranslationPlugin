<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Saver;

use Locastic\SyliusTranslationPlugin\Model\TranslationInterface;
use Locastic\SyliusTranslationPlugin\Model\TranslationValueInterface;

interface TranslationValueSaverInterface
{
    public function saveTranslationValue(TranslationValueInterface $translationValue): void;

    /**
     * @param array|TranslationInterface[] $translations
     */
    public function saveTranslations(array $translations): void;
}
