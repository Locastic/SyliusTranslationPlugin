<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Saver;

use Locastic\SyliusTranslationPlugin\Model\TranslationValueInterface;
use Locastic\SyliusTranslationPlugin\Provider\TranslationFileNameProviderInterface;
use Locastic\SyliusTranslationPlugin\Provider\TranslationFilePathProviderInterface;
use Locastic\SyliusTranslationPlugin\Provider\TranslationsProviderInterface;
use Locastic\SyliusTranslationPlugin\Utils\ArrayUtils;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use function array_key_exists;
use function array_replace_recursive;

final class TranslationValueSaver implements TranslationValueSaverInterface
{
    private string $directory;

    private TranslationFileNameProviderInterface $translationFileNameProvider;

    private TranslationFilePathProviderInterface $translationFilePathProvider;

    private TranslationsProviderInterface $translationsProvider;

    public function __construct(
        string $directory,
        TranslationFileNameProviderInterface $translationFileNameProvider,
        TranslationFilePathProviderInterface $translationFilePathProvider,
        TranslationsProviderInterface $translationsProvider
    ) {
        $this->directory = $directory;
        $this->translationFileNameProvider = $translationFileNameProvider;
        $this->translationFilePathProvider = $translationFilePathProvider;
        $this->translationsProvider = $translationsProvider;
    }

    public function saveTranslationValue(TranslationValueInterface $translationValue): void
    {
        $translation = $translationValue->getTranslation();

        $fileName = $this->translationFileNameProvider->getFileName($translationValue);
        $filePath = $this->translationFilePathProvider->getFilePath($translationValue);
        $existingTranslations = $this->translationsProvider->getTranslations($translationValue->getLocaleCode(), [$translationValue->getLocaleCode()]);
        $existingTranslations = $existingTranslations[$translationValue->getTheme()][$translation->getDomainName()] ?? [];

        $newTranslations = array_replace_recursive($existingTranslations, [
            $translation->getKey() => [
                $translationValue->getLocaleCode() => $translationValue->getValue(),
            ],
        ]);

        $result = [];
        foreach ($newTranslations as $newTranslationKey => $newTranslation) {
            if (!array_key_exists($translationValue->getLocaleCode(), $newTranslation)) {
                continue;
            }
            if (!\is_string($newTranslation[$translationValue->getLocaleCode()])) {
                continue;
            }
            $result = array_replace_recursive($result, ArrayUtils::keyToArray($newTranslationKey, $newTranslation[$translationValue->getLocaleCode()]));
        }
        ArrayUtils::recursiveKsort($result);

        $filesystem = new Filesystem();
        $filesystem->dumpFile($filePath . $fileName, Yaml::dump($result, 8));
    }

    public function saveTranslations(array $translations): void
    {
        $files = [];
        foreach ($translations as $translation) {
            foreach ($translation->getValues() as $translationValue) {
                $fileName = $this->translationFileNameProvider->getFileName($translationValue);
                if (!\array_key_exists($fileName, $files)) {
                    $files[$fileName] = [];
                }

                $files[$fileName] = array_replace_recursive($files[$fileName], ArrayUtils::keyToArray($translation->getKey(), $translationValue->getValue()));
            }
        }

        $filesystem = new Filesystem();
        foreach ($files as $fileName => $fileTranslations) {
            $filesystem->dumpFile($this->directory . '/' . $fileName, Yaml::dump($fileTranslations, 10));
        }
    }
}
