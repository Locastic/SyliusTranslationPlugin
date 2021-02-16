<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Transformer;

use Locastic\SyliusTranslationPlugin\Model\TranslationValueInterface;

interface TranslationValueToFormFieldTransformerInterface
{
    public function transform(TranslationValueInterface $translationValue): string;

    public function reverseTransform(array $submittedField): ?TranslationValueInterface;
}
