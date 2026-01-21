<?php

namespace App\Entity;

use App\Repository\JugadoresFavoritosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JugadoresFavoritosRepository::class)]
class JugadoresFavoritos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'jugadoresFavoritos')]
    #[ORM\JoinColumn(name:'id_usuario', nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_jugador', nullable: false)]
    private ?Jugador $jugador = null;

    #[ORM\Column]
    private ?bool $favorito = null;

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

    public function getJugador(): ?Jugador
    {
        return $this->jugador;
    }

    public function setJugador(?Jugador $jugador): static
    {
        $this->jugador = $jugador;

        return $this;
    }

    public function isFavorito(): ?bool
    {
        return $this->favorito;
    }

    public function setFavorito(bool $favorito): static
    {
        $this->favorito = $favorito;

        return $this;
    }
}
