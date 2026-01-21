<?php

namespace App\Entity;

use App\Repository\OrdenRankingPersonalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdenRankingPersonalRepository::class)]
class OrdenRankingPersonal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ordenRankingPersonals')]
    #[ORM\JoinColumn(name:'id_ranking_personal', nullable: false)]
    private ?RankingPersonal $rankingPersonal = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_jugador', nullable: false)]
    private ?Jugador $jugador = null;

    #[ORM\Column]
    private ?int $posicion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRankingPersonal(): ?RankingPersonal
    {
        return $this->rankingPersonal;
    }

    public function setRankingPersonal(?RankingPersonal $rankingPersonal): static
    {
        $this->rankingPersonal = $rankingPersonal;

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

    public function getPosicion(): ?int
    {
        return $this->posicion;
    }

    public function setPosicion(int $posicion): static
    {
        $this->posicion = $posicion;

        return $this;
    }
}
