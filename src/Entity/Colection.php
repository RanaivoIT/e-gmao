<?php

namespace App\Entity;

use App\Repository\ColectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColectionRepository::class)]
class Colection
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
    private ?string $maker = null;

    #[ORM\Column(length: 255)]
    private ?string $origin = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\OneToMany(mappedBy: 'colection', targetEntity: Organe::class, orphanRemoval: true)]
    private Collection $organes;

    #[ORM\OneToMany(mappedBy: 'colection', targetEntity: Equipement::class, orphanRemoval: true)]
    private Collection $equipements;

    #[ORM\OneToMany(mappedBy: 'colection', targetEntity: Document::class, orphanRemoval: true)]
    private Collection $documents;

    public function __construct()
    {
        $this->organes = new ArrayCollection();
        $this->equipements = new ArrayCollection();
        $this->documents = new ArrayCollection();
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

    public function getMaker(): ?string
    {
        return $this->maker;
    }

    public function setMaker(string $maker): self
    {
        $this->maker = $maker;

        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
     * @return Collection<int, Organe>
     */
    public function getOrganes(): Collection
    {
        return $this->organes;
    }

    public function addOrgane(Organe $organe): self
    {
        if (!$this->organes->contains($organe)) {
            $this->organes->add($organe);
            $organe->setColection($this);
        }

        return $this;
    }

    public function removeOrgane(Organe $organe): self
    {
        if ($this->organes->removeElement($organe)) {
            // set the owning side to null (unless already changed)
            if ($organe->getColection() === $this) {
                $organe->setColection(null);
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
            $equipement->setColection($this);
        }

        return $this;
    }

    public function removeEquipement(Equipement $equipement): self
    {
        if ($this->equipements->removeElement($equipement)) {
            // set the owning side to null (unless already changed)
            if ($equipement->getColection() === $this) {
                $equipement->setColection(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setColection($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getColection() === $this) {
                $document->setColection(null);
            }
        }

        return $this;
    }
}
