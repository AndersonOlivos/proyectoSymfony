<?php

namespace App\Entity;

use App\Repository\RankingPersonalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RankingPersonalRepository::class)]
class RankingPersonal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rankingPersonals')]
    #[ORM\JoinColumn(name:'id_usuario', nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(inversedBy: 'rankingPersonals')]
    #[ORM\JoinColumn(name:'id_ranking_general', nullable: false)]
    private ?RankingGeneral $rankingGeneral = null;

    #[ORM\Column]
    private ?bool $activo = null;

    /**
     * @var Collection<int, OrdenRankingPersonal>
     */
    #[ORM\OneToMany(targetEntity: OrdenRankingPersonal::class, mappedBy: 'rankingPersonal')]
    private Collection $ordenRankingPersonals;

    public function __construct()
    {
        $this->ordenRankingPersonals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getRankingGeneral(): ?RankingGeneral
    {
        return $this->rankingGeneral;
    }

    public function setRankingGeneral(?RankingGeneral $rankingGeneral): static
    {
        $this->rankingGeneral = $rankingGeneral;

        return $this;
    }

    public function isActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): static
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * @return Collection<int, OrdenRankingPersonal>
     */
    public function getOrdenRankingPersonals(): Collection
    {
        return $this->ordenRankingPersonals;
    }

    public function addOrdenRankingPersonal(OrdenRankingPersonal $ordenRankingPersonal): static
    {
        if (!$this->ordenRankingPersonals->contains($ordenRankingPersonal)) {
            $this->ordenRankingPersonals->add($ordenRankingPersonal);
            $ordenRankingPersonal->setRankingPersonal($this);
        }

        return $this;
    }

    public function removeOrdenRankingPersonal(OrdenRankingPersonal $ordenRankingPersonal): static
    {
        if ($this->ordenRankingPersonals->removeElement($ordenRankingPersonal)) {
            // set the owning side to null (unless already changed)
            if ($ordenRankingPersonal->getRankingPersonal() === $this) {
                $ordenRankingPersonal->setRankingPersonal(null);
            }
        }

        return $this;
    }
}
