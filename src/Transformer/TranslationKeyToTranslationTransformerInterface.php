<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Transformer;

use Locastic\SyliusTranslationPlugin\Model\TranslationInterface;

interface TranslationKeyToTranslationTransformerInterface
{
    public function transform(string $domain, string $key, array $values): TranslationInterface;

    /**
     * @return array|TranslationInterface[]
     */
    public function transformMultiple(array $translationKeys): array;
}
