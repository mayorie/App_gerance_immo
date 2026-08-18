<?php

namespace App\Entity;

use App\Repository\PcgRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PcgRepository::class)]
class Pcg
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $compte = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'pcg', targetEntity: FactureFourn::class)]
    private Collection $factureFourns;

    #[ORM\OneToMany(mappedBy: 'pcg2', targetEntity: FactureFourn::class)]
    private Collection $factureFourns2;

    public function __construct()
    {
        $this->factureFourns = new ArrayCollection();
        $this->factureFourns2 = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompte(): ?string
    {
        return $this->compte;
    }

    public function setCompte(string $compte): static
    {
        $this->compte = $compte;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, FactureFourn>
     */
    public function getFactureFourns(): Collection
    {
        return $this->factureFourns;
    }

    public function addFactureFourn(FactureFourn $factureFourn): static
    {
        if (!$this->factureFourns->contains($factureFourn)) {
            $this->factureFourns->add($factureFourn);
            $factureFourn->setPcg($this);
        }

        return $this;
    }

    public function removeFactureFourn(FactureFourn $factureFourn): static
    {
        if ($this->factureFourns->removeElement($factureFourn)) {
            // set the owning side to null (unless already changed)
            if ($factureFourn->getPcg() === $this) {
                $factureFourn->setPcg(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FactureFourn>
     */
    public function getFactureFourns2(): Collection
    {
        return $this->factureFourns2;
    }

    public function addFactureFourn2(FactureFourn $factureFourn): static
    {
        if (!$this->factureFourns2->contains($factureFourn)) {
            $this->factureFourns2->add($factureFourn);
            $factureFourn->setPcg2($this);
        }

        return $this;
    }

    public function removeFactureFourn2(FactureFourn $factureFourn): static
    {
        if ($this->factureFourns2->removeElement($factureFourn)) {
            // set the owning side to null (unless already changed)
            if ($factureFourn->getPcg2() === $this) {
                $factureFourn->setPcg2(null);
            }
        }

        return $this;
    }
}
