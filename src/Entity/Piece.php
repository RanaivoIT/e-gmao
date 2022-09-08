<?php

namespace App\Entity;

use App\Repository\PieceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PieceRepository::class)]
class Piece
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
    private ?string $reference = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\ManyToOne(inversedBy: 'pieces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organe $organe = null;

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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getOrgane(): ?Organe
    {
        return $this->organe;
    }

    public function setOrgane(?Organe $organe): self
    {
        $this->organe = $organe;

        return $this;
    }
}
