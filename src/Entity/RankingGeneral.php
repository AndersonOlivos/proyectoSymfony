<?php

namespace App\Entity;

use App\Repository\RankingGeneralRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RankingGeneralRepository::class)]
class RankingGeneral
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rankingGenerals')]
    #[ORM\JoinColumn(name:'id_categoria', nullable: false)]
    private ?Categoria $categoria = null;

    #[ORM\Column(length: 200)]
    private ?string $titulo = null;

    #[ORM\Column(length: 600)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?bool $activo = null;

    /**
     * @var Collection<int, RankingPersonal>
     */
    #[ORM\OneToMany(targetEntity: RankingPersonal::class, mappedBy: 'rankingGeneral')]
    private Collection $rankingPersonals;

    public function __construct()
    {
        $this->rankingPersonals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

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
     * @return Collection<int, RankingPersonal>
     */
    public function getRankingPersonals(): Collection
    {
        return $this->rankingPersonals;
    }

    public function addRankingPersonal(RankingPersonal $rankingPersonal): static
    {
        if (!$this->rankingPersonals->contains($rankingPersonal)) {
            $this->rankingPersonals->add($rankingPersonal);
            $rankingPersonal->setRankingGeneral($this);
        }

        return $this;
    }

    public function removeRankingPersonal(RankingPersonal $rankingPersonal): static
    {
        if ($this->rankingPersonals->removeElement($rankingPersonal)) {
            // set the owning side to null (unless already changed)
            if ($rankingPersonal->getRankingGeneral() === $this) {
                $rankingPersonal->setRankingGeneral(null);
            }
        }

        return $this;
    }
}
