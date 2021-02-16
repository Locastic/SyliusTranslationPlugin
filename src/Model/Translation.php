<?php

declare(strict_types=1);

namespace Locastic\SyliusTranslationPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class Translation implements TranslationInterface
{
    private ?TranslationDomainInterface $domain = null;

    private ?string $key = null;

    /** @var Collection|TranslationValueInterface[] */
    private Collection $values;

    public function __construct()
    {
        $this->values = new ArrayCollection();
    }

    public function getDomain(): ?TranslationDomainInterface
    {
        return $this->domain;
    }

    public function setDomain(?TranslationDomainInterface $domain): void
    {
        $this->domain = $domain;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): void
    {
        $this->key = $key;
    }

    public function getValues(): Collection
    {
        return $this->values;
    }

    public function hasValues(): bool
    {
        return !$this->getValues()->isEmpty();
    }

    public function hasValue(TranslationValueInterface $value): bool
    {
        return $this->getValues()->contains($value);
    }

    public function addValue(TranslationValueInterface $value): void
    {
        if (!$this->hasValue($value)) {
            $this->values->add($value);
            $value->setTranslation($this);
        }
    }

    public function removeValue(TranslationValueInterface $value): void
    {
        if ($this->hasValue($value)) {
            $this->values->removeElement($value);
            $value->setTranslation(null);
        }
    }
}
