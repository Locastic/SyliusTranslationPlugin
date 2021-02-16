<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Transformer;

use Locastic\SyliusTranslationPlugin\Model\Translation;
use Locastic\SyliusTranslationPlugin\Model\TranslationDomain;
use Locastic\SyliusTranslationPlugin\Model\TranslationInterface;
use Locastic\SyliusTranslationPlugin\Model\TranslationValue;

final class TranslationKeyToTranslationTransformer implements TranslationKeyToTranslationTransformerInterface
{
    public function transform(string $domain, string $key, array $values): TranslationInterface
    {
        $translation = new Translation();

        $translationDomain = new TranslationDomain();
        $translationDomain->setName($domain);
        $translation->setDomain($translationDomain);
        $translation->setKey($key);
        foreach ($values as $localeCode => $value) {
            if (!\is_string($value)) {
                // TODO: Apparently value can sometimes be array here. We should understand why
                continue;
            }
            $translationValue = new TranslationValue();
            $translationValue->setLocaleCode($localeCode);
            $translationValue->setValue($value);

            $translation->addValue($translationValue);
        }

        return $translation;
    }

    public function transformMultiple(array $translationKeys): array
    {
        $translations = [];
        foreach ($translationKeys as $domain => $translationData) {
            foreach ($translationData as $key => $values) {
                $translations[] = $this->transform($domain, $key, $values);
            }
        }

        return $translations;
    }
}
