<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class TranslationTheme implements TranslationThemeInterface
{
    private string $name;

    private Collection $domains;

    public function __construct()
    {
        $this->domains = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDomains(): Collection
    {
        return $this->domains;
    }

    public function hasDomains(): bool
    {
        return !$this->getDomains()->isEmpty();
    }

    public function hasDomain(TranslationDomainInterface $domain): bool
    {
        return $this->getDomains()->contains($domain);
    }

    public function addDomain(TranslationDomainInterface $domain): void
    {
        if (!$this->hasDomain($domain)) {
            $this->domains->add($domain);
            $domain->setTheme($this);
        }
    }

    public function removeDomain(TranslationDomainInterface $domain): void
    {
        if ($this->hasDomain($domain)) {
            $this->domains->removeElement($domain);
            $domain->setTheme(null);
        }
    }
}
