<?php

namespace App\Entity;

use DateTime;
use ORM\PrePersist;
use DateTimeImmutable;
use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdvertRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ORM\PreUpdate;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: AdvertRepository::class)]
class Advert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $cp = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\Column(length: 30)]
    private ?string $country = null;

    #[ORM\ManyToOne(inversedBy: 'advert')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'advert', targetEntity: Reservation::class, orphanRemoval: true)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'advert', targetEntity: Notice::class, orphanRemoval: true)]
    private Collection $notices;

    #[ORM\OneToMany(targetEntity: Images::class, mappedBy: "adverts", orphanRemoval: true, cascade: ['persist'])]
    private Collection $images;

    #[ORM\ManyToOne(inversedBy: 'advert')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lodge $lodge = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'adverts')]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Accessory::class, inversedBy: 'adverts')]
    private Collection $accessories;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isReported = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ReportedBy = null;


    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->notices = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->accessories = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->isReported = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setAdvert($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getAdvert() === $this) {
                $reservation->setAdvert(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notice>
     */
    public function getNotices(): Collection
    {
        return $this->notices;
    }

    public function addNotice(Notice $notice): static
    {
        if (!$this->notices->contains($notice)) {
            $this->notices->add($notice);
            $notice->setAdvert($this);
        }

        return $this;
    }

    public function removeNotice(Notice $notice): static
    {
        if ($this->notices->removeElement($notice)) {
            // set the owning side to null (unless already changed)
            if ($notice->getAdvert() === $this) {
                $notice->setAdvert(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setAdvert($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAdvert() === $this) {
                $image->setAdvert(null);
            }
        }

        return $this;
    }


    public function getLodge(): ?Lodge
    {
        return $this->lodge;
    }

    public function setLodge(?Lodge $lodge): static
    {
        $this->lodge = $lodge;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Accessory>
     */
    public function getAccessories(): Collection
    {
        return $this->accessories;
    }

    public function addAccessory(Accessory $accessory): static
    {
        if (!$this->accessories->contains($accessory)) {
            $this->accessories->add($accessory);
        }

        return $this;
    }

    public function removeAccessory(Accessory $accessory): static
    {
        $this->accessories->removeElement($accessory);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAtString(): ?string
    {
        return $this->createdAt->format('d-m-Y');
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateSlug(): void
    {
        if ($this->title) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }

    public function isIsReported(): ?bool
    {
        return $this->isReported;
    }

    public function setIsReported(?bool $isReported): static
    {
        $this->isReported = $isReported;

        return $this;
    }

    public function getReportedBy(): ?string
    {
        return $this->ReportedBy;
    }

    public function setReportedBy(?string $ReportedBy): static
    {
        $this->ReportedBy = $ReportedBy;

        return $this;
    }
}
