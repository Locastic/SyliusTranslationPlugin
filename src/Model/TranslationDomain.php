<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class TranslationDomain implements TranslationDomainInterface
{
    private ?string $name = null;

    /** @var Collection|TranslationInterface[] */
    private Collection $translations;

    private ?TranslationThemeInterface $theme = null;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function hasTranslations(): bool
    {
        return !$this->getTranslations()->isEmpty();
    }

    public function hasTranslation(TranslationInterface $translation): bool
    {
        return $this->getTranslations()->contains($translation);
    }

    public function addTranslation(TranslationInterface $translation): void
    {
        if (!$this->hasTranslation($translation)) {
            $this->translations->add($translation);
            $translation->setDomain($this);
        }
    }

    public function removeTranslation(TranslationInterface $translation): void
    {
        if ($this->hasTranslation($translation)) {
            $this->translations->removeElement($translation);
            $translation->setDomain(null);
        }
    }

    public function getTheme(): ?TranslationThemeInterface
    {
        return $this->theme;
    }

    public function setTheme(?TranslationThemeInterface $theme): void
    {
        $this->theme = $theme;
    }
}
