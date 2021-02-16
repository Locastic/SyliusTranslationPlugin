<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Provider;

use Locastic\SyliusTranslationPlugin\Exception\GenerateTranslationFileNameException;
use Locastic\SyliusTranslationPlugin\Exception\TranslationNotFoundException;
use Locastic\SyliusTranslationPlugin\Model\TranslationValueInterface;
use function sprintf;

final class TranslationFileNameProvider implements TranslationFileNameProviderInterface
{
    public function getFileName(TranslationValueInterface $translationValue): string
    {
        if (null === $translationValue->getTranslation()) {
            throw new TranslationNotFoundException();
        }

        if (null === $translationValue->getTranslation()->getDomain() || null === $translationValue->getLocaleCode()) {
            throw new GenerateTranslationFileNameException();
        }

        return sprintf('%s.%s.yaml', $translationValue->getTranslation()->getDomain()->getName(), $translationValue->getLocaleCode());
    }

    public function getFromValues(string $directory, string $domain, string $locale, string $format): string
    {
        return sprintf('%s%s.%s.%s', $directory, $domain, $locale, $format);
    }
}
