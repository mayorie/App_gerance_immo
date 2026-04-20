<?php

namespace App\Entity;

use App\Repository\PaiementsMensuelsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementsMensuelsRepository::class)]
class PaiementsMensuels
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $part_recue_du_locataire_date = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $part_recue_du_locataire_mode = null;

    #[ORM\Column]
    private ?float $part_recue_du_locataire_montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $part_recue_de_la_CAF_date = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $part_recue_de_la_CAF_mode = null;

    #[ORM\Column(nullable: true)]
    private ?float $part_recue_de_la_CAF_montant = null;

    #[ORM\Column(nullable: true)]
    private ?float $restant_du_trop_percu_fin_de_mois = null;

    #[ORM\ManyToOne(inversedBy: 'Paiements_mensuels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Locataires $LocatairesID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPartRecueDuLocataireDate(): ?\DateTime
    {
        return $this->part_recue_du_locataire_date;
    }

    public function setPartRecueDuLocataireDate(?\DateTime $part_recue_du_locataire_date): static
    {
        $this->part_recue_du_locataire_date = $part_recue_du_locataire_date;

        return $this;
    }

    public function getPartRecueDuLocataireMode(): ?string
    {
        return $this->part_recue_du_locataire_mode;
    }

    public function setPartRecueDuLocataireMode(?string $part_recue_du_locataire_mode): static
    {
        $this->part_recue_du_locataire_mode = $part_recue_du_locataire_mode;

        return $this;
    }

    public function getPartRecueDuLocataireMontant(): ?float
    {
        return $this->part_recue_du_locataire_montant;
    }

    public function setPartRecueDuLocataireMontant(float $part_recue_du_locataire_montant): static
    {
        $this->part_recue_du_locataire_montant = $part_recue_du_locataire_montant;

        return $this;
    }

    public function getPartRecueDeLaCAFDate(): ?\DateTime
    {
        return $this->part_recue_de_la_CAF_date;
    }

    public function setPartRecueDeLaCAFDate(?\DateTime $part_recue_de_la_CAF_date): static
    {
        $this->part_recue_de_la_CAF_date = $part_recue_de_la_CAF_date;

        return $this;
    }

    public function getPartRecueDeLaCAFMode(): ?string
    {
        return $this->part_recue_de_la_CAF_mode;
    }

    public function setPartRecueDeLaCAFMode(?string $part_recue_de_la_CAF_mode): static
    {
        $this->part_recue_de_la_CAF_mode = $part_recue_de_la_CAF_mode;

        return $this;
    }

    public function getPartRecueDeLaCAFMontant(): ?float
    {
        return $this->part_recue_de_la_CAF_montant;
    }

    public function setPartRecueDeLaCAFMontant(?float $part_recue_de_la_CAF_montant): static
    {
        $this->part_recue_de_la_CAF_montant = $part_recue_de_la_CAF_montant;

        return $this;
    }

    public function getRestantDuTropPercuFinDeMois(): ?float
    {
        return $this->restant_du_trop_percu_fin_de_mois;
    }

    public function setRestantDuTropPercuFinDeMois(?float $restant_du_trop_percu_fin_de_mois): static
    {
        $this->restant_du_trop_percu_fin_de_mois = $restant_du_trop_percu_fin_de_mois;

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
