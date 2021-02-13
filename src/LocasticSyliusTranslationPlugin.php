<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class LocasticSyliusTranslationPlugin extends Bundle
{
    use SyliusPluginTrait;
}
