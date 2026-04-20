<?php

namespace App\Entity;

use App\Repository\CommentairesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentairesRepository::class)]
class Commentaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 512)]
    private ?string $texte = null;

    #[ORM\OneToOne(inversedBy: 'commentaires', cascade: ['persist', 'remove'])]
    private ?Logements $LogementsID = null;

    #[ORM\OneToOne(inversedBy: 'commentaires', cascade: ['persist', 'remove'])]
    private ?Locataires $LocatairesID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }

    public function getLogementsID(): ?Logements
    {
        return $this->LogementsID;
    }

    public function setLogementsID(?Logements $LogementsID): static
    {
        $this->LogementsID = $LogementsID;

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
