<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class LocasticSyliusTranslationExtension extends AbstractResourceExtension
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('locastic_sylius_translation.default_locale', $config['default_locale']);
        $container->setParameter('locastic_sylius_translation.locales', $config['locales']);

        $loader->load('services.xml');

        $this->registerResources('locastic_sylius_translation', $config['driver'], $config['resources'], $container);
    }
}
