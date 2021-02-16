<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Provider;

interface TranslationDomainsProviderInterface
{
    public function toArray(string $translationsDirectory): array;
}
