<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Twig;

use Locastic\SyliusTranslationPlugin\Model\TranslationValueInterface;
use Locastic\SyliusTranslationPlugin\Transformer\TranslationValueToFormFieldTransformerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class TranslationExtension extends AbstractExtension
{
    private TranslationValueToFormFieldTransformerInterface $translationValueToFormFieldTransformer;

    public function __construct(TranslationValueToFormFieldTransformerInterface $translationValueToFormFieldTransformer)
    {
        $this->translationValueToFormFieldTransformer = $translationValueToFormFieldTransformer;
    }

    public function getFilters(): iterable
    {
        return [
            new TwigFilter('locastic_sylius_translation_value_field_name', [$this, 'getTranslationValueFieldName'])
        ];
    }

    public function getTranslationValueFieldName(TranslationValueInterface $translationValue): string
    {
        return $this->translationValueToFormFieldTransformer->transform($translationValue);
    }
}
