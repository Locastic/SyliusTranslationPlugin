<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Controller;

use Locastic\SyliusTranslationPlugin\Provider\TranslationsProviderInterface;
use Locastic\SyliusTranslationPlugin\Saver\TranslationValueSaverInterface;
use Locastic\SyliusTranslationPlugin\Transformer\TranslationKeyToTranslationTransformerInterface;
use Locastic\SyliusTranslationPlugin\Transformer\TranslationValueToFormFieldTransformerInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class TranslationController implements TranslationControllerInterface
{
    private Environment $twig;

    private TranslationsProviderInterface $translationsProvider;

    private TranslationKeyToTranslationTransformerInterface $translationTransformer;

    private TranslationValueToFormFieldTransformerInterface $translationValueToFormTransformer;

    private string $localeCode;

    private array $locales;

    private TranslationValueSaverInterface $translationValueSaver;

    public function __construct(
        Environment $twig,
        TranslationsProviderInterface $translationsProvider,
        TranslationKeyToTranslationTransformerInterface $translationTransformer,
        TranslationValueToFormFieldTransformerInterface $translationValueToFormTransformer,
        string $localeCode,
        array $locales,
        TranslationValueSaverInterface $translationValueSaver
    ) {
        $this->twig = $twig;
        $this->translationsProvider = $translationsProvider;
        $this->translationTransformer = $translationTransformer;
        $this->localeCode = $localeCode;
        $this->locales = $locales;
        $this->translationValueToFormTransformer = $translationValueToFormTransformer;
        $this->translationValueSaver = $translationValueSaver;
    }

    public function indexAction(Request $request): Response
    {
        $translations = $this->translationsProvider->getTranslations($this->localeCode, $this->locales);
        $translations = $this->translationTransformer->transformMultiple($translations);

        $adapter = new ArrayAdapter($translations);
        $pagerFanta = new Pagerfanta($adapter);

        $pagerFanta->setMaxPerPage(10);
        $pagerFanta->setCurrentPage($request->query->get('page', 1));

        return new Response($this->twig->render('@LocasticSyliusTranslationPlugin/Admin/Translations/index.html.twig', [
            'translations' => $pagerFanta,
            'resources' => $pagerFanta,
        ]));
    }

    public function saveAction(Request $request): Response
    {
        $translations = $request->request->get('translations');
        $translationValue = $this->translationValueToFormTransformer->reverseTransform($translations);

        $this->translationValueSaver->saveTranslationValue($translationValue);

        return new Response('<html><body>OK</body>');
    }
}
