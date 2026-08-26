<?php

namespace App\Entity;

use App\Repository\NoteDeFraisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteDeFraisRepository::class)]
#[ORM\Table(name: 'notes_de_frais')]
class NoteDeFrais
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\ManyToOne(inversedBy: 'notesDeFrais')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Pcg $pcg = null;

    #[ORM\Column(length: 255)]
    private ?string $motif = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $distance = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $peage = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $parking = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $fraisTotal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPcg(): ?Pcg
    {
        return $this->pcg;
    }

    public function setPcg(?Pcg $pcg): static
    {
        $this->pcg = $pcg;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(?float $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    public function getPeage(): ?float
    {
        return $this->peage;
    }

    public function setPeage(?float $peage): static
    {
        $this->peage = $peage;

        return $this;
    }

    public function getParking(): ?float
    {
        return $this->parking;
    }

    public function setParking(?float $parking): static
    {
        $this->parking = $parking;

        return $this;
    }

    public function getFraisTotal(): ?float
    {
        return $this->fraisTotal;
    }

    public function setFraisTotal(?float $fraisTotal): static
    {
        $this->fraisTotal = $fraisTotal;

        return $this;
    }
}
