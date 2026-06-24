<?php

namespace App\Entity;

use App\Repository\FactureFournRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureFournRepository::class)]
class FactureFourn
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_facture = null;

    #[ORM\Column(length: 255)]
    private ?string $fournisseur = null;

    #[ORM\ManyToOne(inversedBy: 'factureFourns')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pcg $pcg = null;

    #[ORM\Column(length: 255)]
    private ?string $type_achat = null;

    #[ORM\Column(length: 255)]
    private ?string $motif = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $Montant = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_paiement = null;

    #[ORM\Column(length: 255)]
    private ?string $mode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFacture(): ?\DateTimeImmutable
    {
        return $this->date_facture;
    }

    public function setDateFacture(\DateTimeImmutable $date_facture): static
    {
        $this->date_facture = $date_facture;

        return $this;
    }

    public function getFournisseur(): ?string
    {
        return $this->fournisseur;
    }

    public function setFournisseur(string $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

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

    public function getTypeAchat(): ?string
    {
        return $this->type_achat;
    }

    public function setTypeAchat(string $type_achat): static
    {
        $this->type_achat = $type_achat;

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

    public function getMontant(): ?float
    {
        return $this->Montant;
    }

    public function setMontant(float $Montant): static
    {
        $this->Montant = $Montant;

        return $this;
    }

    public function getDatePaiement(): ?\DateTimeImmutable
    {
        return $this->date_paiement;
    }

    public function setDatePaiement(\DateTimeImmutable $date_paiement): static
    {
        $this->date_paiement = $date_paiement;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): static
    {
        $this->mode = $mode;

        return $this;
    }
}
