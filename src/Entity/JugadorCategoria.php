<?php

namespace App\Entity;

use App\Repository\JugadorCategoriaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JugadorCategoriaRepository::class)]
class JugadorCategoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'jugadorCategorias')]
    #[ORM\JoinColumn(name:'id_jugador', nullable: false)]
    private ?Jugador $jugador = null;

    #[ORM\ManyToOne(inversedBy: 'jugadorCategorias')]
    #[ORM\JoinColumn(name:'id_categoria', nullable: false)]
    private ?Categoria $categoria = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }
}
