<?php

namespace App\Entity;

use App\Repository\LodgeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LodgeRepository::class)]
class Lodge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'lodge', targetEntity: Advert::class, orphanRemoval: true)]
    private Collection $advert;

    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    public function __construct()
    {
        $this->advert = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Advert>
     */
    public function getAdvert(): Collection
    {
        return $this->advert;
    }

    public function addAdvert(Advert $advert): static
    {
        if (!$this->advert->contains($advert)) {
            $this->advert->add($advert);
            $advert->setLodge($this);
        }

        return $this;
    }

    public function removeAdvert(Advert $advert): static
    {
        if ($this->advert->removeElement($advert)) {
            // set the owning side to null (unless already changed)
            if ($advert->getLodge() === $this) {
                $advert->setLodge(null);
            }
        }

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
}
