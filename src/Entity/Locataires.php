<?php

namespace App\Entity;

use App\Repository\LocatairesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocatairesRepository::class)]
class Locataires
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

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_de_naissance = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $lieu_de_naissance = null;

    #[ORM\ManyToOne(inversedBy: 'LocatairesID')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Logements $LogementsID = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $debut_bail = null;

    #[ORM\Column(nullable: true)]
    private ?float $montant_caution = null;

    #[ORM\Column(nullable: true)]
    private ?float $loyer_TCC = null;

    #[ORM\Column(nullable: true)]
    private ?float $restant_du_trop_percu = 0;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_EDL_entree = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $preavis_recu_le = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $debut_du_preavis = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_EDL_sortie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_de_sortie = null;

    #[ORM\Column(nullable: true)]
    private ?float $montant_solde_de_tout_compte = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_solde_de_tout_compte = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $mode_paiement_solde_de_tout_compte = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $banque_solde_de_tout_compte = null;

    #[ORM\Column(nullable: true)]
    private ?bool $cloture_contrat_visale = null;

    #[ORM\Column(nullable: true)]
    private ?bool $a_quitte_le_logement = false;

    #[ORM\OneToOne(mappedBy: 'LocatairesID', cascade: ['persist', 'remove'])]
    private ?Commentaires $commentaires = null;

    /**
     * @var Collection<int, ProvisionsPourCharges>
     */
    #[ORM\OneToMany(targetEntity: ProvisionsPourCharges::class, mappedBy: 'LocatairesID', orphanRemoval: true)]
    private Collection $Provisions_pour_charges;

    /**
     * @var Collection<int, LoyersHC>
     */
    #[ORM\OneToMany(targetEntity: LoyersHC::class, mappedBy: 'LocatairesID', orphanRemoval: true)]
    private Collection $Loyers_HC;

    /**
     * @var Collection<int, PacksServices>
     */
    #[ORM\OneToMany(targetEntity: PacksServices::class, mappedBy: 'LocatairesID', orphanRemoval: true)]
    private Collection $Packs_services;

    /**
     * @var Collection<int, Garants>
     */
    #[ORM\OneToMany(targetEntity: Garants::class, mappedBy: 'LocatairesID', orphanRemoval: true)]
    private Collection $Garants;

    /**
     * @var Collection<int, PaiementsMensuels>
     */
    #[ORM\OneToMany(targetEntity: PaiementsMensuels::class, mappedBy: 'LocatairesID', orphanRemoval: true)]
    private Collection $Paiements_mensuels;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column(nullable: true)]
    private ?int $num_comptable = null;

    public function __construct()
    {
        $this->Provisions_pour_charges = new ArrayCollection();
        $this->Loyers_HC = new ArrayCollection();
        $this->Packs_services = new ArrayCollection();
        $this->Garants = new ArrayCollection();
        $this->Paiements_mensuels = new ArrayCollection();
    }

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

    public function getLogementsID(): ?Logements
    {
        return $this->LogementsID;
    }

    public function setLogementsID(Logements $LogementsID): static
    {
        $this->LogementsID = $LogementsID;

        return $this;
    }

    public function getDebutBail(): ?\DateTime
    {
        return $this->debut_bail;
    }

    public function setDebutBail(?\DateTime $debut_bail): static
    {
        $this->debut_bail = $debut_bail;

        return $this;
    }

    public function getMontantCaution(): ?float
    {
        return $this->montant_caution;
    }

    public function setMontantCaution(?float $montant_caution): static
    {
        $this->montant_caution = $montant_caution;

        return $this;
    }

    public function getLoyerTCC(): ?float
    {
        return $this->loyer_TCC;
    }

    public function setLoyerTCC(?float $loyer_TCC): static
    {
        $this->loyer_TCC = $loyer_TCC;

        return $this;
    }

    public function getRestantDuTropPercu(): ?float
    {
        return $this->restant_du_trop_percu;
    }

    public function setRestantDuTropPercu(?float $restant_du_trop_percu): static
    {
        $this->restant_du_trop_percu = $restant_du_trop_percu;

        return $this;
    }

    public function getDateEDLEntree(): ?\DateTime
    {
        return $this->date_EDL_entree;
    }

    public function setDateEDLEntree(?\DateTime $date_EDL_entree): static
    {
        $this->date_EDL_entree = $date_EDL_entree;

        return $this;
    }

    public function getPreavisRecuLe(): ?\DateTime
    {
        return $this->preavis_recu_le;
    }

    public function setPreavisRecuLe(?\DateTime $preavis_recu_le): static
    {
        $this->preavis_recu_le = $preavis_recu_le;

        return $this;
    }

    public function getDebutDuPreavis(): ?\DateTime
    {
        return $this->debut_du_preavis;
    }

    public function setDebutDuPreavis(?\DateTime $debut_du_preavis): static
    {
        $this->debut_du_preavis = $debut_du_preavis;

        return $this;
    }

    public function getDateEDLSortie(): ?\DateTime
    {
        return $this->date_EDL_sortie;
    }

    public function setDateEDLSortie(?\DateTime $date_EDL_sortie): static
    {
        $this->date_EDL_sortie = $date_EDL_sortie;

        return $this;
    }

    public function getDateDeSortie(): ?\DateTime
    {
        return $this->date_de_sortie;
    }

    public function setDateDeSortie(?\DateTime $date_de_sortie): static
    {
        $this->date_de_sortie = $date_de_sortie;

        return $this;
    }

    public function getMontantSoldeDeToutCompte(): ?float
    {
        return $this->montant_solde_de_tout_compte;
    }

    public function setMontantSoldeDeToutCompte(?float $montant_solde_de_tout_compte): static
    {
        $this->montant_solde_de_tout_compte = $montant_solde_de_tout_compte;

        return $this;
    }

    public function getDateSoldeDeToutCompte(): ?\DateTime
    {
        return $this->date_solde_de_tout_compte;
    }

    public function setDateSoldeDeToutCompte(?\DateTime $date_solde_de_tout_compte): static
    {
        $this->date_solde_de_tout_compte = $date_solde_de_tout_compte;

        return $this;
    }

    public function getModePaiementSoldeDeToutCompte(): ?string
    {
        return $this->mode_paiement_solde_de_tout_compte;
    }

    public function setModePaiementSoldeDeToutCompte(?string $mode_paiement_solde_de_tout_compte): static
    {
        $this->mode_paiement_solde_de_tout_compte = $mode_paiement_solde_de_tout_compte;

        return $this;
    }

    public function getBanqueSoldeDeToutCompte(): ?string
    {
        return $this->banque_solde_de_tout_compte;
    }

    public function setBanqueSoldeDeToutCompte(?string $banque_solde_de_tout_compte): static
    {
        $this->banque_solde_de_tout_compte = $banque_solde_de_tout_compte;

        return $this;
    }

    public function isClotureContratVisale(): ?bool
    {
        return $this->cloture_contrat_visale;
    }

    public function setClotureContratVisale(?bool $cloture_contrat_visale): static
    {
        $this->cloture_contrat_visale = $cloture_contrat_visale;

        return $this;
    }

    public function isAQuitteLeLogement(): ?bool
    {
        return $this->a_quitte_le_logement;
    }

    public function setAQuitteLeLogement(?bool $a_quitte_le_logement): static
    {
        $this->a_quitte_le_logement = $a_quitte_le_logement;

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
            $this->commentaires->setLocatairesID(null);
        }

        // set the owning side of the relation if necessary
        if ($commentaires !== null && $commentaires->getLocatairesID() !== $this) {
            $commentaires->setLocatairesID($this);
        }

        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * @return Collection<int, ProvisionsPourCharges>
     */
    public function getProvisionsPourCharges(): Collection
    {
        return $this->Provisions_pour_charges;
    }

    public function getLatestCharge()
    {
        $charges = $this->getProvisionsPourCharges();

        if ($charges->isEmpty()) {
            return null;
        }

        $charges = $charges->toArray();

        usort($charges, fn($a, $b) =>
            $b->getDateMES() <=> $a->getDateMES()
        );

        return $charges[0];
    }

    public function addProvisionsPourCharge(ProvisionsPourCharges $provisionsPourCharge): static
    {
        if (!$this->Provisions_pour_charges->contains($provisionsPourCharge)) {
            $this->Provisions_pour_charges->add($provisionsPourCharge);
            $provisionsPourCharge->setLocatairesID($this);
        }

        return $this;
    }

    public function removeProvisionsPourCharge(ProvisionsPourCharges $provisionsPourCharge): static
    {
        if ($this->Provisions_pour_charges->removeElement($provisionsPourCharge)) {
            // set the owning side to null (unless already changed)
            if ($provisionsPourCharge->getLocatairesID() === $this) {
                $provisionsPourCharge->setLocatairesID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LoyersHC>
     */
    public function getLoyersHC(): Collection
    {
        return $this->Loyers_HC;
    }

    public function addLoyersHC(LoyersHC $loyersHC): static
    {
        if (!$this->Loyers_HC->contains($loyersHC)) {
            $this->Loyers_HC->add($loyersHC);
            $loyersHC->setLocatairesID($this);
        }

        return $this;
    }

    public function removeLoyersHC(LoyersHC $loyersHC): static
    {
        if ($this->Loyers_HC->removeElement($loyersHC)) {
            // set the owning side to null (unless already changed)
            if ($loyersHC->getLocatairesID() === $this) {
                $loyersHC->setLocatairesID(null);
            }
        }

        return $this;
    }

    public function getLatestLoyer()
    {
        $loyers = $this->getLoyersHC();

        if ($loyers->isEmpty()) {
            return null;
        }

        $loyers = $loyers->toArray();

        usort($loyers, fn($a, $b) =>
            $b->getDateMES() <=> $a->getDateMES()
        );

        return $loyers[0];
    }

    /**
     * @return Collection<int, PacksServices>
     */
    public function getPacksServices(): Collection
    {
        return $this->Packs_services;
    }

    public function getLatestPackServices()
    {
        $packsservices = $this->getPacksServices();

        if ($packsservices->isEmpty()) {
            return null;
        }

        $packsservices = $packsservices->toArray();

        usort($packsservices, fn($a, $b) =>
            $b->getDateMES() <=> $a->getDateMES()
        );

        return $packsservices[0];
    }

    public function addPacksService(PacksServices $packsService): static
    {
        if (!$this->Packs_services->contains($packsService)) {
            $this->Packs_services->add($packsService);
            $packsService->setLocatairesID($this);
        }

        return $this;
    }

    public function removePacksService(PacksServices $packsService): static
    {
        if ($this->Packs_services->removeElement($packsService)) {
            // set the owning side to null (unless already changed)
            if ($packsService->getLocatairesID() === $this) {
                $packsService->setLocatairesID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Garants>
     */
    public function getGarants(): Collection
    {
        return $this->Garants;
    }

    public function addGarant(Garants $garant): static
    {
        if (!$this->Garants->contains($garant)) {
            $this->Garants->add($garant);
            $garant->setLocatairesID($this);
        }

        return $this;
    }

    public function removeGarant(Garants $garant): static
    {
        if ($this->Garants->removeElement($garant)) {
            // set the owning side to null (unless already changed)
            if ($garant->getLocatairesID() === $this) {
                $garant->setLocatairesID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PaiementsMensuels>
     */
    public function getPaiementsMensuels(): Collection
    {
        return $this->Paiements_mensuels;
    }

    public function addPaiementsMensuel(PaiementsMensuels $paiementsMensuel): static
    {
        if (!$this->Paiements_mensuels->contains($paiementsMensuel)) {
            $this->Paiements_mensuels->add($paiementsMensuel);
            $paiementsMensuel->setLocatairesID($this);
        }

        return $this;
    }

    public function removePaiementsMensuel(PaiementsMensuels $paiementsMensuel): static
    {
        if ($this->Paiements_mensuels->removeElement($paiementsMensuel)) {
            // set the owning side to null (unless already changed)
            if ($paiementsMensuel->getLocatairesID() === $this) {
                $paiementsMensuel->setLocatairesID(null);
            }
        }

        return $this;
    }

    public function getLatestPaiement(): ?PaiementsMensuels
    {
        $paiements = $this->getPaiementsMensuels();

        if ($paiements->isEmpty()) {
            return null;
        }

        $paiements = $paiements->toArray();

        usort($paiements, fn($a, $b) =>
            $b->getDate() <=> $a->getDate()
        );

        return $paiements[0];
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getNumComptable(): ?int
    {
        return $this->num_comptable;
    }

    public function setNumComptable(?int $num_comptable): static
    {
        $this->num_comptable = $num_comptable;

        return $this;
    }
}
