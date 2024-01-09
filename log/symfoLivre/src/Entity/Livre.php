<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titreLivre = null;

    #[ORM\Column(length: 255)]
    private ?string $fichierLivre = null;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $auteurLivre = null;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorieLivre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreLivre(): ?string
    {
        return $this->titreLivre;
    }

    public function setTitreLivre(string $titreLivre): static
    {
        $this->titreLivre = $titreLivre;

        return $this;
    }

    public function getFichierLivre(): ?string
    {
        return $this->fichierLivre;
    }

    public function setFichierLivre(string $fichierLivre): static
    {
        $this->fichierLivre = $fichierLivre;

        return $this;
    }

    public function getAuteurLivre(): ?User
    {
        return $this->auteurLivre;
    }

    public function setAuteurLivre(?User $auteurLivre): static
    {
        $this->auteurLivre = $auteurLivre;

        return $this;
    }

    public function getCategorieLivre(): ?Categorie
    {
        return $this->categorieLivre;
    }

    public function setCategorieLivre(?Categorie $categorieLivre): static
    {
        $this->categorieLivre = $categorieLivre;

        return $this;
    }
}
