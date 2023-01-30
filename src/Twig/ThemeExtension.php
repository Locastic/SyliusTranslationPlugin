<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Twig;

use Locastic\SymfonyTranslationBundle\Provider\ThemesProviderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ThemeExtension extends AbstractExtension
{
    private ThemesProviderInterface $themeProvider;

    public function __construct(ThemesProviderInterface $themeProvider)
    {
        $this->themeProvider = $themeProvider;
    }

    public function getFunctions(): iterable
    {
        return [
            new TwigFunction('locastic_sylius_translation_get_themes', [$this->themeProvider, 'getAll'])
        ];
    }
}
