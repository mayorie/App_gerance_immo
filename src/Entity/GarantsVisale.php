<?php

namespace App\Entity;

use App\Repository\GarantsVisaleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GarantsVisaleRepository::class)]
class GarantsVisale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $texte = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $date_anniversaire = null;

    #[ORM\OneToOne(inversedBy: 'Garants_visale', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Garants $GarantsID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(?string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }

    public function getDateAnniversaire(): ?\DateTime
    {
        return $this->date_anniversaire;
    }

    public function setDateAnniversaire(?\DateTime $date_anniversaire): static
    {
        $this->date_anniversaire = $date_anniversaire;

        return $this;
    }

    public function getGarantsID(): ?Garants
    {
        return $this->GarantsID;
    }

    public function setGarantsID(Garants $GarantsID): static
    {
        $this->GarantsID = $GarantsID;

        return $this;
    }
}
