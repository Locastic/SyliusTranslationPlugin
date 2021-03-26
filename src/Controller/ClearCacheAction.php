<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ClearCacheAction
{
    private FlashBagInterface $flashBag;

    private UrlGeneratorInterface $router;

    public function __construct(FlashBagInterface $flashBag, UrlGeneratorInterface $router)
    {
        $this->flashBag = $flashBag;
        $this->router = $router;
    }

    public function __invoke(Request $request, KernelInterface $kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'cache:clear',
        ]);

        $output = new NullOutput();
        $application->run($input, $output);

        $this->flashBag->add('success', 'locastic_sylius_translation.cache_cleared');

        return new RedirectResponse($this->router->generate('locastic_sylius_translations_admin_index'));
    }
}
