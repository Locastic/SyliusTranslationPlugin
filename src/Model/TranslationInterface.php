<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Model;

use Doctrine\Common\Collections\Collection;

interface TranslationInterface
{
    public function getDomain(): ?TranslationDomainInterface;

    public function setDomain(?TranslationDomainInterface $domain): void;

    public function getKey(): ?string;

    public function setKey(?string $key): void;

    /**
     * @return Collection|TranslationValueInterface[]
     */
    public function getValues(): Collection;

    public function hasValues(): bool;

    public function hasValue(TranslationValueInterface $value): bool;

    public function addValue(TranslationValueInterface $value): void;

    public function removeValue(TranslationValueInterface $value): void;
}
