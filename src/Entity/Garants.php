<?php

namespace App\Entity;

use App\Repository\GarantsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GarantsRepository::class)]
class Garants
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'Garants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Locataires $LocatairesID = null;

    #[ORM\OneToOne(mappedBy: 'GarantsID', cascade: ['persist', 'remove'])]
    private ?GarantsPhysiques $Garants_physiques = null;

    #[ORM\OneToOne(mappedBy: 'GarantsID', cascade: ['persist', 'remove'])]
    private ?GarantsVisale $Garants_visale = null;

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

    public function getLocatairesID(): ?Locataires
    {
        return $this->LocatairesID;
    }

    public function setLocatairesID(?Locataires $LocatairesID): static
    {
        $this->LocatairesID = $LocatairesID;

        return $this;
    }

    public function getGarantsPhysiques(): ?GarantsPhysiques
    {
        return $this->Garants_physiques;
    }

    public function setGarantsPhysiques(GarantsPhysiques $Garants_physiques): static
    {
        // set the owning side of the relation if necessary
        if ($Garants_physiques->getGarantsID() !== $this) {
            $Garants_physiques->setGarantsID($this);
        }

        $this->Garants_physiques = $Garants_physiques;

        return $this;
    }

    public function getGarantsVisale(): ?GarantsVisale
    {
        return $this->Garants_visale;
    }

    public function setGarantsVisale(GarantsVisale $Garants_visale): static
    {
        // set the owning side of the relation if necessary
        if ($Garants_visale->getGarantsID() !== $this) {
            $Garants_visale->setGarantsID($this);
        }

        $this->Garants_visale = $Garants_visale;

        return $this;
    }
}
