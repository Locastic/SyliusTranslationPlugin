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

        return sprintf(
            '[%s][%s][%s][%s]',
            $translationValue->getTheme(),
            $translation->getDomainName(),
            $translation->getKey(),
            $translationValue->getLocaleCode()
        );
    }

    public function reverseTransform(array $submittedField): ?TranslationValueInterface
    {
        foreach ($submittedField as $theme => $themeInfo) {
            foreach ($themeInfo as $domain => $domainInfo) {
                foreach ($domainInfo as $key => $keyInfo) {
                    foreach ($keyInfo as $locale => $value) {
                        $translation = new Translation();
                        $translation->setDomainName($domain);
                        $translation->setKey($key);

                        $translationValue = new TranslationValue();
                        $translationValue->setTheme($theme);
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
