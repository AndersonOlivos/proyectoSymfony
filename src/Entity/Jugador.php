<?php

namespace App\Entity;

use App\Repository\JugadorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JugadorRepository::class)]
class Jugador
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'id_api')]
    private ?int $idApi = null;

    #[ORM\Column(name:'nombre', length: 250, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(name:'sexo', length: 2, nullable: true)]
    private ?string $sexo = null;

    #[ORM\Column(name:'altura', nullable: true)]
    private ?int $altura = null;

    #[ORM\Column(name:'puntos', nullable: true)]
    private ?int $puntos = null;

    #[ORM\Column(name:'imagen_url', length: 500, nullable: true)]
    private ?string $imagenUrl = null;

    #[ORM\Column]
    private ?bool $activo = null;

    /**
     * @var Collection<int, JugadorCategoria>
     */
    #[ORM\OneToMany(targetEntity: JugadorCategoria::class, mappedBy: 'jugador')]
    private Collection $jugadorCategorias;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'jugador')]
    private Collection $reviews;

    public function __construct()
    {
        $this->jugadorCategorias = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdApi(): ?int
    {
        return $this->idApi;
    }

    public function setIdApi(int $idApi): static
    {
        $this->idApi = $idApi;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getSexo(): ?string
    {
        return $this->sexo;
    }

    public function setSexo(?string $sexo): static
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getAltura(): ?int
    {
        return $this->altura;
    }

    public function setAltura(?int $altura): static
    {
        $this->altura = $altura;

        return $this;
    }

    public function getPuntos(): ?int
    {
        return $this->puntos;
    }

    public function setPuntos(?int $puntos): static
    {
        $this->puntos = $puntos;

        return $this;
    }

    public function getImagenUrl(): ?string
    {
        return $this->imagenUrl;
    }

    public function setImagenUrl(?string $imagenUrl): static
    {
        $this->imagenUrl = $imagenUrl;

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
            $jugadorCategoria->setJugador($this);
        }

        return $this;
    }

    public function removeJugadorCategoria(JugadorCategoria $jugadorCategoria): static
    {
        if ($this->jugadorCategorias->removeElement($jugadorCategoria)) {
            // set the owning side to null (unless already changed)
            if ($jugadorCategoria->getJugador() === $this) {
                $jugadorCategoria->setJugador(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setJugador($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getJugador() === $this) {
                $review->setJugador(null);
            }
        }

        return $this;
    }
}
