<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface TranslationControllerInterface
{
    public function indexAction(Request $request): Response;

    public function saveAction(Request $request): Response;
}
