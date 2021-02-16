<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Model;

use Doctrine\Common\Collections\Collection;

interface TranslationDomainInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    /**
     * @return Collection|TranslationInterface[]
     */
    public function getTranslations(): Collection;

    public function hasTranslations(): bool;

    public function hasTranslation(TranslationInterface $translation): bool;

    public function addTranslation(TranslationInterface $translation): void;

    public function removeTranslation(TranslationInterface $translation): void;

    public function getTheme(): ?TranslationThemeInterface;

    public function setTheme(?TranslationThemeInterface $theme): void;
}
