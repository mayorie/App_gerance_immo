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

    #[ORM\ManyToOne(inversedBy: 'factureFourns2')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Pcg $pcg2 = null;

    #[ORM\Column(length: 255)]
    private ?string $motif = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $Montant = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $Montant2 = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $montantPaiement = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $remise = null;

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

    public function getPcg2(): ?Pcg
    {
        return $this->pcg2;
    }

    public function setPcg2(?Pcg $pcg2): static
    {
        $this->pcg2 = $pcg2;

        return $this;
    }

    public function getMontant2(): ?float
    {
        return $this->Montant2;
    }

    public function setMontant2(?float $Montant2): static
    {
        $this->Montant2 = $Montant2;

        return $this;
    }

    public function getMontantPaiement(): ?float
    {
        return $this->montantPaiement;
    }

    public function setMontantPaiement(?float $montantPaiement): static
    {
        $this->montantPaiement = $montantPaiement;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(?float $remise): static
    {
        $this->remise = $remise;

        return $this;
    }
}
