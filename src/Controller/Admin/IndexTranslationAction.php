<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Controller\Admin;

use Locastic\SymfonyTranslationBundle\Form\Type\SearchTranslationType;
use Locastic\SymfonyTranslationBundle\Model\SearchTranslation;
use Locastic\SymfonyTranslationBundle\Utils\SearchTranslationsUtilsInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class IndexTranslationAction
{
    private SearchTranslationsUtilsInterface $searchTranslationsUtils;

    private Environment $twig;

    private FormFactoryInterface $formFactory;

    public function __construct(
        SearchTranslationsUtilsInterface $searchTranslationsUtils,
        Environment $twig,
        FormFactoryInterface $formFactory
    ) {
        $this->searchTranslationsUtils = $searchTranslationsUtils;
        $this->twig = $twig;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request): Response
    {
        $search = new SearchTranslation();
        $searchForm = $this->formFactory->create(SearchTranslationType::class, $search);
        $searchForm->handleRequest($request);

        $pagerFanta = $this->searchTranslationsUtils->searchTranslationsFromRequest($request, $search, $searchForm);

        return new Response($this->twig->render('@LocasticSyliusTranslationPlugin/Admin/Translations/index.html.twig', [
            'translations' => $pagerFanta,
            'resources' => $pagerFanta,
            'searchForm' => $searchForm->createView(),
            'searching' => null !== $search->getSearch(),
        ]));
    }
}
