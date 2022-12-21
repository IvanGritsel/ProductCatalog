<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: ProductService::class, orphanRemoval: true)]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

    /**
     * @return Collection<int, ProductService>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(ProductService $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setService($this);
        }

        return $this;
    }

    public function removeProduct(ProductService $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getService() === $this) {
                $product->setService(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}
