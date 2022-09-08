<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
class Site
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $abbreviation = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $contact = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\OneToMany(mappedBy: 'site', targetEntity: Operateur::class, orphanRemoval: true)]
    private Collection $operateurs;

    #[ORM\OneToMany(mappedBy: 'site', targetEntity: Equipement::class, orphanRemoval: true)]
    private Collection $equipements;

    public function __construct()
    {
        $this->operateurs = new ArrayCollection();
        $this->equipements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(string $abbreviation): self
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, Operateur>
     */
    public function getOperateurs(): Collection
    {
        return $this->operateurs;
    }

    public function addOperateur(Operateur $operateur): self
    {
        if (!$this->operateurs->contains($operateur)) {
            $this->operateurs->add($operateur);
            $operateur->setSite($this);
        }

        return $this;
    }

    public function removeOperateur(Operateur $operateur): self
    {
        if ($this->operateurs->removeElement($operateur)) {
            // set the owning side to null (unless already changed)
            if ($operateur->getSite() === $this) {
                $operateur->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Equipement>
     */
    public function getEquipements(): Collection
    {
        return $this->equipements;
    }

    public function addEquipement(Equipement $equipement): self
    {
        if (!$this->equipements->contains($equipement)) {
            $this->equipements->add($equipement);
            $equipement->setSite($this);
        }

        return $this;
    }

    public function removeEquipement(Equipement $equipement): self
    {
        if ($this->equipements->removeElement($equipement)) {
            // set the owning side to null (unless already changed)
            if ($equipement->getSite() === $this) {
                $equipement->setSite(null);
            }
        }

        return $this;
    }
}
