<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Model;

use Doctrine\Common\Collections\Collection;

interface TranslationThemeInterface
{
    public function getName(): string;

    public function setName(string $name): void;

    /**
     * @return Collection|TranslationDomainInterface[]
     */
    public function getDomains(): Collection;

    public function hasDomains(): bool;

    public function hasDomain(TranslationDomainInterface $domain): bool;

    public function addDomain(TranslationDomainInterface $domain): void;

    public function removeDomain(TranslationDomainInterface $domain): void;
}
