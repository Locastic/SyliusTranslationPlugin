<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Controller;

use Locastic\SyliusTranslationPlugin\Form\Type\SearchTranslationType;
use Locastic\SyliusTranslationPlugin\Model\SearchTranslation;
use Locastic\SyliusTranslationPlugin\Model\TranslationInterface;
use Locastic\SyliusTranslationPlugin\Provider\TranslationsProviderInterface;
use Locastic\SyliusTranslationPlugin\Saver\TranslationValueSaverInterface;
use Locastic\SyliusTranslationPlugin\Transformer\TranslationKeyToTranslationTransformerInterface;
use Locastic\SyliusTranslationPlugin\Transformer\TranslationValueToFormFieldTransformerInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormFactoryInterface;
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

    private FormFactoryInterface $formFactory;

    public function __construct(
        Environment $twig,
        TranslationsProviderInterface $translationsProvider,
        TranslationKeyToTranslationTransformerInterface $translationTransformer,
        TranslationValueToFormFieldTransformerInterface $translationValueToFormTransformer,
        string $localeCode,
        array $locales,
        TranslationValueSaverInterface $translationValueSaver,
        FormFactoryInterface $formFactory
    ) {
        $this->twig = $twig;
        $this->translationsProvider = $translationsProvider;
        $this->translationTransformer = $translationTransformer;
        $this->localeCode = $localeCode;
        $this->locales = $locales;
        $this->translationValueToFormTransformer = $translationValueToFormTransformer;
        $this->translationValueSaver = $translationValueSaver;
        $this->formFactory = $formFactory;
    }

    public function indexAction(Request $request): Response
    {
        $search = new SearchTranslation();
        $searchForm = $this->formFactory->create(SearchTranslationType::class, $search);
        $searchForm->handleRequest($request);

        $translations = $this->translationsProvider->getTranslations($this->localeCode, $this->locales);
        $translations = $this->translationTransformer->transformMultiple($translations);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $translations = \array_filter($translations, function (TranslationInterface $translation) use ($search): bool {
                if (\preg_match('/'. $search->getSearch() . '/i', $translation->getKey())) {
                    return true;
                }
                foreach ($translation->getValues() as $translationValue) {
                    if (\preg_match('/'. $search->getSearch() . '/i', $translationValue->getValue())) {
                        return true;
                    }
                }

                return false;
            });
        }

        $adapter = new ArrayAdapter($translations);
        $pagerFanta = new Pagerfanta($adapter);

        if (null !== $search->getSearch() && $pagerFanta->getNbResults() > 0) {
            $pagerFanta->setMaxPerPage($pagerFanta->getNbResults());
            $request->query->remove('page');
        } else {
            $pagerFanta->setMaxPerPage(50);
        }
        $pagerFanta->setCurrentPage($request->query->get('page', 1));

        return new Response($this->twig->render('@LocasticSyliusTranslationPlugin/Admin/Translations/index.html.twig', [
            'translations' => $pagerFanta,
            'resources' => $pagerFanta,
            'searchForm' => $searchForm->createView(),
            'searching' => null !== $search->getSearch(),
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
