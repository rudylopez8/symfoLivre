<?php

namespace App\Entity;

use Assert\EqualTo;
use App\Entity\Livre;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use UserPasswordHasherAwareTrait;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('mailUser', message: "Mail deja utilisé")]

class User implements UserInterface, PasswordAuthenticatedUserInterface

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $nomUser = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $mailUser = null;

    #[ORM\Column(length: 255)]
    private ?string $passwordUser = null;

    #[ORM\Column(length: 255)]
    private ?string $roleUser = null;

    #[ORM\OneToMany(mappedBy: 'auteurLivre', targetEntity: Livre::class)]
    private Collection $livres;

    private ?UserPasswordHasherInterface $passwordHasher = null;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->livres = new ArrayCollection();
        $this->passwordHasher = $passwordHasher;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUser(): ?string
    {
        return $this->nomUser;
    }

    public function setNomUser(string $nomUser): static
    {
        $this->nomUser = $nomUser;

        return $this;
    }

    public function getMailUser(): ?string
    {
        return $this->mailUser;
    }

    public function setMailUser(string $mailUser): static
    {
        $this->mailUser = $mailUser;

        return $this;
    }

    public function getPasswordUser(): ?string
    {
        return $this->passwordUser;
    }

    public function setPasswordUser(string $password): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($this, $password);
        $this->passwordUser = $hashedPassword;
    }

    public function getRoleUser(): ?string
    {
        return $this->roleUser;
    }

    public function setRoleUser(string $roleUser): static
    {
        $this->roleUser = $roleUser;

        return $this;
    }

    /**
     * @return Collection<int, Livre>
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): static
    {
        if (!$this->livres->contains($livre)) {
            $this->livres->add($livre);
            $livre->setAuteurLivre($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): static
    {
        if ($this->livres->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getAuteurLivre() === $this) {
                $livre->setAuteurLivre(null);
            }
        }

        return $this;
    }
    public function getRoles(): array
    {
        return [$this->roleUser];
    }

    public function eraseCredentials(): void
    {
        // Cette méthode est appelée lorsqu'un mot de passe en texte brut n'est plus nécessaire
        // Vous pouvez y ajouter du code pour réinitialiser des informations sensibles du mot de passe
        // Par exemple, si vous avez stocké un mot de passe en texte brut, vous pouvez le réinitialiser ici
    }
    public function getUsername(): string
    {
        // Dans Symfony 5.3 et versions ultérieures, getUserIdentifier remplace getUsername
        // Pour garantir la compatibilité, vous pouvez renvoyer l'e-mail ici
        return $this->mailUser;
    }

    public function getPassword(): ?string
    {
        return $this->passwordUser;
    }

    public function getSalt(): ?string
    {
        // Vous pouvez laisser cette méthode vide, car les mots de passe sont stockés de manière sécurisée
        // Et dans la plupart des cas, vous n'avez pas besoin d'utiliser une "salt" séparée
        return null;
    }
    public function getUserIdentifier(): string
    {
        return $this->mailUser;
    }

}
