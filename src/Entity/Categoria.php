<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriaRepository::class)]
class Categoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    #[ORM\Column]
    private bool $activo = true;

    /**
     * @var Collection<int, JugadorCategoria>
     */
    #[ORM\OneToMany(targetEntity: JugadorCategoria::class, mappedBy: 'categoria')]
    private Collection $jugadorCategorias;

    /**
     * @var Collection<int, RankingGeneral>
     */
    #[ORM\OneToMany(targetEntity: RankingGeneral::class, mappedBy: 'categoria')]
    private Collection $rankingGenerals;

    public function __construct()
    {
        $this->jugadorCategorias = new ArrayCollection();
        $this->rankingGenerals = new ArrayCollection();
        $this->activo = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

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
     * @return Collection<int, JugadorCategoria>
     */
    public function getJugadorCategorias(): Collection
    {
        return $this->jugadorCategorias;
    }

    public function addJugadorCategoria(JugadorCategoria $jugadorCategoria): static
    {
        if (!$this->jugadorCategorias->contains($jugadorCategoria)) {
            $this->jugadorCategorias->add($jugadorCategoria);
            $jugadorCategoria->setCategoria($this);
        }

        return $this;
    }

    public function removeJugadorCategoria(JugadorCategoria $jugadorCategoria): static
    {
        if ($this->jugadorCategorias->removeElement($jugadorCategoria)) {
            // set the owning side to null (unless already changed)
            if ($jugadorCategoria->getCategoria() === $this) {
                $jugadorCategoria->setCategoria(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RankingGeneral>
     */
    public function getRankingGenerals(): Collection
    {
        return $this->rankingGenerals;
    }

    public function addRankingGeneral(RankingGeneral $rankingGeneral): static
    {
        if (!$this->rankingGenerals->contains($rankingGeneral)) {
            $this->rankingGenerals->add($rankingGeneral);
            $rankingGeneral->setCategoria($this);
        }

        return $this;
    }

    public function removeRankingGeneral(RankingGeneral $rankingGeneral): static
    {
        if ($this->rankingGenerals->removeElement($rankingGeneral)) {
            // set the owning side to null (unless already changed)
            if ($rankingGeneral->getCategoria() === $this) {
                $rankingGeneral->setCategoria(null);
            }
        }

        return $this;
    }
}
