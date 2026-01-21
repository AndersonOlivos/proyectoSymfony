<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'username', length: 255)]
    private ?string $username = null;

    #[ORM\Column(name: 'email', length: 255)]
    private ?string $email = null;

    #[ORM\Column(name: 'password', length: 255)]
    private ?string $password = null;

    #[ORM\Column(name: 'rol', type: 'json', length: 100)]
    private ?array $rol = null;

    #[ORM\Column(name: 'activo')]
    private ?bool $activo = null;

    /**
     * @var Collection<int, JugadoresFavoritos>
     */
    #[ORM\OneToMany(targetEntity: JugadoresFavoritos::class, mappedBy: 'usuario')]
    private Collection $jugadoresFavoritos;

    /**
     * @var Collection<int, RankingPersonal>
     */
    #[ORM\OneToMany(targetEntity: RankingPersonal::class, mappedBy: 'usuario')]
    private Collection $rankingPersonals;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'usuario')]
    private Collection $reviews;

    public function __construct()
    {
        $this->jugadoresFavoritos = new ArrayCollection();
        $this->rankingPersonals = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRol(): ?array
    {
        return $this->rol;
    }

    public function setRol(?array $rol): void
    {
        $this->rol = $rol;
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
     * @return Collection<int, JugadoresFavoritos>
     */
    public function getJugadoresFavoritos(): Collection
    {
        return $this->jugadoresFavoritos;
    }

    public function addJugadoresFavorito(JugadoresFavoritos $jugadoresFavorito): static
    {
        if (!$this->jugadoresFavoritos->contains($jugadoresFavorito)) {
            $this->jugadoresFavoritos->add($jugadoresFavorito);
            $jugadoresFavorito->setUsuario($this);
        }

        return $this;
    }

    public function removeJugadoresFavorito(JugadoresFavoritos $jugadoresFavorito): static
    {
        if ($this->jugadoresFavoritos->removeElement($jugadoresFavorito)) {
            // set the owning side to null (unless already changed)
            if ($jugadoresFavorito->getUsuario() === $this) {
                $jugadoresFavorito->setUsuario(null);
            }
        }

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
            $rankingPersonal->setUsuario($this);
        }

        return $this;
    }

    public function removeRankingPersonal(RankingPersonal $rankingPersonal): static
    {
        if ($this->rankingPersonals->removeElement($rankingPersonal)) {
            // set the owning side to null (unless already changed)
            if ($rankingPersonal->getUsuario() === $this) {
                $rankingPersonal->setUsuario(null);
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
            $review->setUsuario($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUsuario() === $this) {
                $review->setUsuario(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}
