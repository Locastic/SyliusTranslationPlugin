<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\MenuBuilder;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addTranslationMenuItem(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menu->getChild('configuration')
            ->addChild('translations', ['route' => 'locastic_sylius_translations_admin_index'])
            ->setLabel('locastic_sylius_translation.ui.menu.translations')
            ->setLabelAttribute('icon', 'language')
        ;
    }
}
