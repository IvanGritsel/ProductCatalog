<?php

namespace App\Entity;

use App\Entity\Enum\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 45)]
    private ?string $manufacturer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\Column]
    private ?int $priceByn = null;

    #[ORM\Column(enumType: ProductType::class)]
    private ?ProductType $productType = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductService::class, cascade: ['ALL'], fetch: 'EAGER', orphanRemoval: true)]
    private Collection $services;

    public function __construct()
    {
        $this->services = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getPriceByn(): ?int
    {
        return $this->priceByn;
    }

    public function setPriceByn(int $priceByn): self
    {
        $this->priceByn = $priceByn;

        return $this;
    }

    public function getProductType(): ?ProductType
    {
        return $this->productType;
    }

    public function setProductType(ProductType $productType): self
    {
        $this->productType = $productType;

        return $this;
    }

    /**
     * @return Collection<int, ProductService>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(ProductService $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setProduct($this);
        }

        return $this;
    }

    public function removeService(ProductService $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getProduct() === $this) {
                $service->setProduct(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        $services = [];
        foreach ($this->services as $service) {
            $services[] = $service;
        }
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'manufacturer' => $this->getManufacturer(),
            'releaseDate' => $this->getReleaseDate(),
            'productType' => $this->getProductType()->name,
            'priceByn' => $this->getPriceByn(),
            'services' => $services,
        ];
    }
}
