<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Provider;

use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;

interface ThemesProviderInterface
{
    /**
     * @return array|ThemeInterface[]
     */
    public function getAll(): array;

    public function findOneByName(string $name): ?ThemeInterface;

    public function getDefaultTheme(): ThemeInterface;
}
