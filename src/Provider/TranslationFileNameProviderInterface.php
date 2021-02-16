<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Provider;

use Locastic\SyliusTranslationPlugin\Exception\GenerateTranslationFileNameException;
use Locastic\SyliusTranslationPlugin\Exception\TranslationNotFoundException;
use Locastic\SyliusTranslationPlugin\Model\TranslationValueInterface;

interface TranslationFileNameProviderInterface
{
    /**
     * @throws GenerateTranslationFileNameException
     * @throws TranslationNotFoundException
     */
    public function getFileName(TranslationValueInterface $translationValue): string;

    public function getFromValues(string $directory, string $domain, string $locale, string $format): string;
}
