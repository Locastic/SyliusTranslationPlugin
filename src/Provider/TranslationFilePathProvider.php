<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Provider;

use Locastic\SymfonyTranslationBundle\Model\TranslationValueInterface;
use Locastic\SymfonyTranslationBundle\Provider\ThemesProviderInterface;
use Locastic\SymfonyTranslationBundle\Provider\TranslationFilePathProviderInterface;

final class TranslationFilePathProvider implements TranslationFilePathProviderInterface
{
    private TranslationFilePathProviderInterface $decoratedTranslationFilePathProvider;

    private ThemesProviderInterface $themesProvider;

    public function __construct(
        TranslationFilePathProviderInterface $decoratedTranslationFilePathProvider,
        ThemesProviderInterface $themesProvider
    ) {
        $this->decoratedTranslationFilePathProvider = $decoratedTranslationFilePathProvider;
        $this->themesProvider = $themesProvider;
    }

    public function getFilePath(TranslationValueInterface $translationValue): string
    {
        $theme = $this->themesProvider->findOneByName($translationValue->getTheme());
        if (null === $theme) {
            $theme = $this->themesProvider->getDefaultTheme();
        }

        return $theme->getPath();
    }

    public function getDefaultDirectory(): string
    {
        return $this->decoratedTranslationFilePathProvider->getDefaultDirectory();
    }
}
