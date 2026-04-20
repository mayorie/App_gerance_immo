<?php

namespace App\Entity;

use App\Repository\GarantsPhysiquesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GarantsPhysiquesRepository::class)]
class GarantsPhysiques
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(nullable: true)]
    private ?int $tel = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $mail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(nullable: true)]
    private ?int $code_postal = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_de_naissance = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $lieu_de_naissance = null;

    #[ORM\OneToOne(inversedBy: 'Garants_physiques', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Garants $GarantsID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(?int $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): static
    {
        $this->mail = $mail;

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

    public function getDateDeNaissance(): ?\DateTime
    {
        return $this->date_de_naissance;
    }

    public function setDateDeNaissance(?\DateTime $date_de_naissance): static
    {
        $this->date_de_naissance = $date_de_naissance;

        return $this;
    }

    public function getLieuDeNaissance(): ?string
    {
        return $this->lieu_de_naissance;
    }

    public function setLieuDeNaissance(?string $lieu_de_naissance): static
    {
        $this->lieu_de_naissance = $lieu_de_naissance;

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
