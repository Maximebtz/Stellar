<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $name = null;


    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    #[ORM\ManyToMany(targetEntity: Advert::class, mappedBy: 'categories')]
    private Collection $adverts;

    public function __construct()
    {
        $this->adverts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return Collection<int, Advert>
     */
    public function getAdverts(): Collection
    {
        return $this->adverts;
    }

    public function addAdvert(Advert $advert): static
    {
        if (!$this->adverts->contains($advert)) {
            $this->adverts->add($advert);
            $advert->addCategory($this);
        }

        return $this;
    }

    public function removeAdvert(Advert $advert): static
    {
        if ($this->adverts->removeElement($advert)) {
            $advert->removeCategory($this);
        }

        return $this;
    }
}
