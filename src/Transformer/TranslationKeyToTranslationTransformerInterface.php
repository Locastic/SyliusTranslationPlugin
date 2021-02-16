<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Transformer;

use Locastic\SyliusTranslationPlugin\Model\TranslationInterface;

interface TranslationKeyToTranslationTransformerInterface
{
    /**
     * @return array|TranslationInterface[]
     */
    public function transformMultiple(array $translationKeys): array;
}
