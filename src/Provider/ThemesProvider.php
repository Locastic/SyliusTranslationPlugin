<?php

declare(strict_types = 1);

namespace Locastic\SyliusTranslationPlugin\Provider;

use Locastic\SymfonyTranslationBundle\Factory\ThemeFactoryInterface as LocasticThemeFactoryInterface;
use Locastic\SymfonyTranslationBundle\Model\ThemeInterface;
use Locastic\SymfonyTranslationBundle\Provider\ThemesProviderInterface;
use Sylius\Bundle\ThemeBundle\Factory\ThemeFactoryInterface as SyliusThemeFactoryInterface;
use Sylius\Bundle\ThemeBundle\Repository\ThemeRepositoryInterface;

final class ThemesProvider implements ThemesProviderInterface
{
    private ThemeRepositoryInterface $themeRepository;

    private SyliusThemeFactoryInterface $syliusThemeFactory;

    private LocasticThemeFactoryInterface $locasticThemeFactory;

    private string $baseDirectory;

    public function __construct(
        ThemeRepositoryInterface $themeRepository,
        SyliusThemeFactoryInterface $themeFactory,
        LocasticThemeFactoryInterface $locasticThemeFactory,
        string $baseDirectory
    ) {
        $this->themeRepository = $themeRepository;
        $this->syliusThemeFactory = $themeFactory;
        $this->locasticThemeFactory = $locasticThemeFactory;
        $this->baseDirectory = $baseDirectory;
    }

    public function getAll(): array
    {
        $themes = [self::NAME_DEFAULT => $this->getDefaultTheme()];

        return array_merge($themes, $this->themeRepository->findAll());
    }

    public function findOneByName(string $name): ?ThemeInterface
    {
        if (self::NAME_DEFAULT === $name) {
            return $this->getDefaultTheme();
        }

        $syliusTheme = $this->themeRepository->findOneByName($name);
        if (null === $syliusTheme) {
            return null;
        }

        return $this->locasticThemeFactory->createNew($syliusTheme->getName(), $syliusTheme->getPath());
    }

    public function getDefaultTheme(): ThemeInterface
    {
        $syliusTheme = $this->syliusThemeFactory->create(self::NAME_DEFAULT, '');

        return $this->locasticThemeFactory->createNew($syliusTheme->getName(), $syliusTheme->getPath());
    }
}
