<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class LocasticSyliusTranslationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('locastic_sylius_translation.default_locale', $config['default_locale']);
        $container->setParameter('locastic_sylius_translation.locales', $config['locales']);

        $loader->load('services.xml');
    }
}
