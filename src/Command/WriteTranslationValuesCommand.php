<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Command;

use Locastic\SyliusTranslationPlugin\Provider\TranslationsProviderInterface;
use Locastic\SyliusTranslationPlugin\Saver\TranslationValueSaverInterface;
use Locastic\SyliusTranslationPlugin\Transformer\TranslationKeyToTranslationTransformerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use function sprintf;

final class WriteTranslationValuesCommand extends Command
{
    /** @var string */
    public static $defaultName = 'locastic:sylius-translation:dump-all';

    private TranslationsProviderInterface $translationsProvider;

    private TranslationKeyToTranslationTransformerInterface $translationTransformer;

    private TranslationValueSaverInterface $translationValueSaver;

    private string $localeCode;

    private array $locales;

    private TranslatorInterface $translator;

    protected OutputInterface $output;

    public function __construct(
        TranslationsProviderInterface $translationsProvider,
        TranslationKeyToTranslationTransformerInterface $translationTransformer,
        TranslationValueSaverInterface $translationValueSaver,
        string $localeCode,
        array $locales,
        TranslatorInterface $translator,
        string $name = null
    ) {
        parent::__construct($name);

        $this->translationsProvider = $translationsProvider;
        $this->translationTransformer = $translationTransformer;
        $this->translationValueSaver = $translationValueSaver;
        $this->localeCode = $localeCode;
        $this->locales = $locales;
        $this->translator = $translator;
    }

    protected function configure(): void
    {
        $this->setDescription('Dump all translations into files');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->writeln('Starting to dump translations', OutputInterface::VERBOSITY_NORMAL);

        $translations = $this->translationsProvider->getTranslations($this->localeCode, $this->locales);
//        $translations = $this->translationTransformer->transformMultiple($translations);
//        foreach ($translations as $translation) {
//            $this->writeLn(sprintf('  Working with domain %s', $translation->getDomain()), OutputInterface::VERBOSITY_VERBOSE);
//            $this->writeLn(sprintf('    Working with key %s', $translation->getKey()), OutputInterface::VERBOSITY_VERY_VERBOSE);
//            foreach ($translation->getValues() as $translationValue) {
//                $this->writeLn(sprintf('      Working with locale %s', $translationValue->getLocaleCode()), OutputInterface::VERBOSITY_DEBUG);
//                $this->writeLn(sprintf('      Working with value %s', $translationValue->getValue()), OutputInterface::VERBOSITY_DEBUG);
//                $this->translationValueSaver->saveTranslationValue($translationValue);
//            }
//        }
//
//        $this->writeln('Dumped translations', OutputInterface::VERBOSITY_NORMAL);

        return 0;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $this->output = $output;
    }

    protected function writeLn(string $message, int $level = OutputInterface::OUTPUT_NORMAL): void
    {
        $this->output->writeln(sprintf('[%s] %s', date('Y-m-d H:i:s'), $message), $level);
    }
}
