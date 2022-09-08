<?php

namespace App\Entity;

use App\Repository\EquipementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipementRepository::class)]
class Equipement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'equipements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Colection $colection = null;

    #[ORM\ManyToOne(inversedBy: 'equipements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site = null;

    #[ORM\Column(length: 255)]
    private ?string $service = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;
    
    #[ORM\Column]
    private ?\DateTimeImmutable $usedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\OneToMany(mappedBy: 'equipement', targetEntity: Demande::class, orphanRemoval: true)]
    private Collection $demandes;

    #[ORM\OneToMany(mappedBy: 'equipement', targetEntity: Intervention::class, orphanRemoval: true)]
    private Collection $interventions;


    public function __construct()
    {
        $this->demandes = new ArrayCollection();
        $this->interventions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColection(): ?Colection
    {
        return $this->colection;
    }

    public function setColection(?Colection $colection): self
    {
        $this->colection = $colection;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(string $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getUsedAt(): ?\DateTimeImmutable
    {
        return $this->usedAt;
    }

    public function setUsedAt(\DateTimeImmutable $usedAt): self
    {
        $this->usedAt = $usedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->setEquipement($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getEquipement() === $this) {
                $demande->setEquipement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Intervention>
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): self
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions->add($intervention);
            $intervention->setEquipement($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning side to null (unless already changed)
            if ($intervention->getEquipement() === $this) {
                $intervention->setEquipement(null);
            }
        }

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
}
