<?php

namespace App\Entity;

use App\Repository\LoyersHCRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoyersHCRepository::class)]
class LoyersHC
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $date_MES = null;

    #[ORM\ManyToOne(inversedBy: 'Loyers_HC')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Locataires $LocatairesID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(?float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateMES(): ?\DateTime
    {
        return $this->date_MES;
    }

    public function setDateMES(?\DateTime $date_MES): static
    {
        $this->date_MES = $date_MES;

        return $this;
    }

    public function getLocatairesID(): ?Locataires
    {
        return $this->LocatairesID;
    }

    public function setLocatairesID(?Locataires $LocatairesID): static
    {
        $this->LocatairesID = $LocatairesID;

        return $this;
    }
}
