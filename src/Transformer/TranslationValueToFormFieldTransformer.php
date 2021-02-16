<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Transformer;

use Locastic\SyliusTranslationPlugin\Model\Translation;
use Locastic\SyliusTranslationPlugin\Model\TranslationDomain;
use Locastic\SyliusTranslationPlugin\Model\TranslationValue;
use Locastic\SyliusTranslationPlugin\Model\TranslationValueInterface;
use function sprintf;

final class TranslationValueToFormFieldTransformer implements TranslationValueToFormFieldTransformerInterface
{
    public function transform(TranslationValueInterface $translationValue): string
    {
        $translation = $translationValue->getTranslation();
        if (null === $translation) {
            return '';
        }

        $translationDomain = $translation->getDomain();
        $translationTheme = $translationDomain !== null ? $translationDomain->getTheme() : null;

        return sprintf(
            '[%s][%s][%s][%s]',
            $translationDomain !== null ? $translationDomain->getName() : 'null',
            $translationTheme !== null ? $translationTheme->getName() : 'null',
            $translationValue->getLocaleCode(),
            $translation->getKey()
        );
    }

    public function reverseTransform(array $submittedField): ?TranslationValueInterface
    {
        foreach ($submittedField as $domain => $domainInfo) {
            foreach ($domainInfo as $theme => $themeInfo) {
                foreach ($themeInfo as $locale => $localeInfo) {
                    foreach ($localeInfo as $key => $value) {
                        $translationDomain = new TranslationDomain();
                        $translationDomain->setName($domain);
                        $translation = new Translation();
                        $translation->setDomain($translationDomain);
                        $translation->setKey($key);

                        $translationValue = new TranslationValue();
                        $translationValue->setTheme($theme !== 'null' ? $theme : null);
                        $translationValue->setLocaleCode($locale);
                        $translationValue->setValue($value);

                        $translation->addValue($translationValue);

                        return $translationValue;
                    }
                }
            }
        }

        return null;
    }
}
