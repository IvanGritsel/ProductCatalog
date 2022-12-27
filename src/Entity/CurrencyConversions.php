<?php

namespace App\Entity;

use App\Repository\CurrencyConversionsRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyConversionsRepository::class)]
class CurrencyConversions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::JSON)]
    private array $rates = [];

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRates(): array
    {
        return $this->rates;
    }

    public function setRates(array $rates): self
    {
        $this->rates = $rates;

        return $this;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
