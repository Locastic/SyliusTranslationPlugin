<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Provider;

use Locastic\SymfonyTranslationBundle\Model\TranslationValueInterface;
use Locastic\SymfonyTranslationBundle\Provider\DefaultTranslationDirectoryProviderInterface;
use Locastic\SymfonyTranslationBundle\Provider\ThemesProviderInterface;
use Locastic\SymfonyTranslationBundle\Provider\TranslationFilePathProviderInterface;

final class TranslationFilePathProvider implements TranslationFilePathProviderInterface
{
    private TranslationFilePathProviderInterface $decoratedTranslationFilePathProvider;

    private ThemesProviderInterface $themesProvider;

    private DefaultTranslationDirectoryProviderInterface $defaultTranslationDirectoryProvider;

    public function __construct(
        TranslationFilePathProviderInterface $decoratedTranslationFilePathProvider,
        ThemesProviderInterface $themesProvider,
        DefaultTranslationDirectoryProviderInterface $defaultTranslationDirectoryProvider
    ) {
        $this->decoratedTranslationFilePathProvider = $decoratedTranslationFilePathProvider;
        $this->themesProvider = $themesProvider;
        $this->defaultTranslationDirectoryProvider = $defaultTranslationDirectoryProvider;
    }

    public function getFilePath(TranslationValueInterface $translationValue): string
    {
        $theme = $this->themesProvider->findOneByName($translationValue->getTheme());
        if (null === $theme || ThemesProviderInterface::NAME_DEFAULT === $theme->getName()) {
            return $this->defaultTranslationDirectoryProvider->getDefaultDirectory();
        }

        return $theme->getPath() . '/translations/';
    }

    public function getDefaultDirectory(): string
    {
        return $this->decoratedTranslationFilePathProvider->getDefaultDirectory();
    }
}
