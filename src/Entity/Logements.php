<?php

namespace App\Entity;

use App\Repository\LogementsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogementsRepository::class)]
class Logements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $id_appart = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $residence = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $batiment = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $appt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $code_postal = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $SIRET = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $num_chambre = null;

    #[ORM\OneToOne(mappedBy: 'LogementsID', cascade: ['persist', 'remove'])]
    private ?Locataires $LocatairesID = null;

    #[ORM\OneToOne(mappedBy: 'LogementsID', cascade: ['persist', 'remove'])]
    private ?Commentaires $commentaires = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAppart(): ?string
    {
        return $this->id_appart;
    }

    public function setIdAppart(?string $id_appart): static
    {
        $this->id_appart = $id_appart;

        return $this;
    }

    public function getResidence(): ?string
    {
        return $this->residence;
    }

    public function setResidence(?string $residence): static
    {
        $this->residence = $residence;

        return $this;
    }

    public function getBatiment(): ?string
    {
        return $this->batiment;
    }

    public function setBatiment(?string $batiment): static
    {
        $this->batiment = $batiment;

        return $this;
    }

    public function getAppt(): ?string
    {
        return $this->appt;
    }

    public function setAppt(?string $appt): static
    {
        $this->appt = $appt;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(?int $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getSIRET(): ?string
    {
        return $this->SIRET;
    }

    public function setSIRET(?string $SIRET): static
    {
        $this->SIRET = $SIRET;

        return $this;
    }

    public function getNumChambre(): ?int
    {
        return $this->num_chambre;
    }

    public function setNumChambre(?int $num_chambre): static
    {
        $this->num_chambre = $num_chambre;

        return $this;
    }

    public function getLocatairesID(): ?Locataires
    {
        return $this->LocatairesID;
    }

    public function setLocatairesID(Locataires $LocatairesID): static
    {
        // set the owning side of the relation if necessary
        if ($LocatairesID->getLogementsID() !== $this) {
            $LocatairesID->setLogementsID($this);
        }

        $this->LocatairesID = $LocatairesID;

        return $this;
    }

    public function getCommentaires(): ?Commentaires
    {
        return $this->commentaires;
    }

    public function setCommentaires(?Commentaires $commentaires): static
    {
        // unset the owning side of the relation if necessary
        if ($commentaires === null && $this->commentaires !== null) {
            $this->commentaires->setLogementsID(null);
        }

        // set the owning side of the relation if necessary
        if ($commentaires !== null && $commentaires->getLogementsID() !== $this) {
            $commentaires->setLogementsID($this);
        }

        $this->commentaires = $commentaires;

        return $this;
    }
}
