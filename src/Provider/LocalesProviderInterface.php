<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Provider;

interface LocalesProviderInterface
{
    public function getLocalesFromCode(string $localeCode): array;
}
