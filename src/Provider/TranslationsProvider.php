<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Provider;

use InvalidArgumentException;
use Locastic\SyliusTranslationPlugin\Utils\ArrayUtils;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;
use function array_key_exists;
use function array_replace_recursive;

final class TranslationsProvider implements TranslationsProviderInterface
{
    private array $bundles;

    private TranslationDomainsProviderInterface $translationDomainsProvider;

    private LocalesProviderInterface $localesProvider;

    private FileLocator $fileLocator;

    private TranslationFileNameProviderInterface $translationFileNameProvider;

    private string $appTranslationsDirectory;

    public function __construct(
        array $enabledBundles,
        TranslationDomainsProviderInterface $translationDomainsProvider,
        LocalesProviderInterface $localesProvider,
        FileLocator $fileLocator,
        TranslationFileNameProviderInterface $translationFileNameProvider,
        string $appTranslationsDirectory
    ) {
        $this->bundles = $enabledBundles;
        $this->translationDomainsProvider = $translationDomainsProvider;
        $this->localesProvider = $localesProvider;
        $this->fileLocator = $fileLocator;
        $this->translationFileNameProvider = $translationFileNameProvider;
        $this->appTranslationsDirectory = $appTranslationsDirectory;
    }

    public function getTranslations(string $defaultLocaleCode, array $locales): array
    {
        $translations = [];
        foreach ($this->bundles as $bundleName => $bundle) {
            $translations = array_replace_recursive($translations, $this->getBundleTranslations($bundleName, $defaultLocaleCode, $locales));
        }
        $translations = array_replace_recursive($translations, $this->getDirectoryTranslations($this->appTranslationsDirectory, $defaultLocaleCode, $locales));

        ArrayUtils::recursiveKsort($translations);

        dump($translations['flashes']);

        return $translations;
    }

    public function getBundleTranslations(string $bundleName, string $localeCode, array $locales): array
    {
        if (!$this->doesBundleHaveTranslations($bundleName)) {
            return [];
        }
        $translationsDirectory = $this->getBundleTranslationsDirectory($bundleName);

        return $this->getDirectoryTranslations($translationsDirectory, $localeCode, $locales);
    }

    public function getDirectoryTranslations(string $directory, string $localeCode, array $locales): array
    {
        $domains = $this->translationDomainsProvider->toArray($directory);
        $defaultLocales = $this->localesProvider->getLocalesFromCode($localeCode);

        $directoryTranslations = [];
        foreach ($domains as $domain) {
            foreach ($defaultLocales as $defaultLocale) {
                $translations = $this->getYamlTranslations($directory, $domain, $defaultLocale);
                $translations = array_replace_recursive($translations, $this->getXmlTranslations($directory, $domain, $defaultLocale));

                if (!array_key_exists($domain, $directoryTranslations)) {
                    $directoryTranslations[$domain] = [];
                }
                $translations = ArrayUtils::arrayFlatten($translations);
                foreach ($translations as $key => $value) {
                    $translations[$key] = [$localeCode => $value];
                }

                $directoryTranslations[$domain] = array_replace_recursive($directoryTranslations[$domain], $translations);
            }

            foreach ($locales as $locale) {
                $availableLocales = $this->localesProvider->getLocalesFromCode($locale);
                foreach ($availableLocales as $availableLocale) {
                    $translations = $this->getYamlTranslations($directory, $domain, $availableLocale);
                    $translations = array_replace_recursive($translations, $this->getXmlTranslations($directory, $domain, $availableLocale));

                    if (!array_key_exists($domain, $directoryTranslations)) {
                        continue;
                    }
                    $translations = ArrayUtils::arrayFlatten($translations);
                    foreach ($translations as $key => $value) {
                        $directoryTranslations[$domain][$key][$locale] = $value;
                    }
                }
            }
        }

        return $directoryTranslations;
    }

    public function doesBundleHaveTranslations(string $bundleName): bool
    {
        try {
            $this->fileLocator->locate(sprintf('@%s/Resources/translations/', $bundleName));

            return true;
        } catch (InvalidArgumentException $exception) {
            return false;
        }
    }

    public function getBundleTranslationsDirectory(string $bundleName): ?string
    {
        if (!$this->doesBundleHaveTranslations($bundleName)) {
            return null;
        }

        return $this->fileLocator->locate(sprintf('@%s/Resources/translations/', $bundleName));
    }

    public function getYamlTranslations(string $directory, string $domain, string $locale): array
    {
        $translations = [];

        $formats = ['yml', 'yaml'];
        foreach ($formats as $format) {
            $fileName = $this->translationFileNameProvider->getFromValues($directory, $domain, $locale, $format);
            $translations = array_replace_recursive($translations, $this->getTranslationFileContent($fileName));
        }

        return $translations;
    }

    public function getTranslationFileContent(string $filePath, string $type = self::TYPE_YAML): array
    {
        if (!file_exists($filePath)) {
            return [];
        }

        switch ($type) {
            case self::TYPE_XML:
                //TODO: XML
                return [];
            case self::TYPE_YAML:
            default:
                return Yaml::parse(file_get_contents($filePath));
        }
    }

    public function getXmlTranslations(string $directory, string $domain, string $locale): array
    {
        $bundleTranslations = [];

        $formats = ['xml'];
        foreach ($formats as $format) {
            $fileName = $this->translationFileNameProvider->getFromValues($directory, $domain, $locale, $format);
            if (file_exists($fileName)) {
                die('?');

                if (!array_key_exists($domain, $bundleTranslations)) {
                    $bundleTranslations[$domain] = [];
                }
                $translations = ArrayUtils::arrayFlatten($translations);

                $bundleTranslations[$domain] = array_replace_recursive($bundleTranslations[$domain], $translations);
            }
        }

        return $bundleTranslations;
    }
}
