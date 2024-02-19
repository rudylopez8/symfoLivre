<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api'])]
    private ?string $titreLivre = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api'])]
    private ?string $fichierLivre = null;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['api'])]
    private ?User $auteurLivre = null;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['api'])]
    private ?Categorie $categorieLivre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $resumeLivre = null;

    #[ORM\Column]
    #[Groups(['api'])]
    private ?float $prixLivre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['api'])]
    private ?\DateTimeInterface $dateUploadLivre = null;

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

    public function getResumeLivre(): ?string
    {
        return $this->resumeLivre;
    }

    public function setResumeLivre(?string $resumeLivre): static
    {
        $this->resumeLivre = $resumeLivre;

        return $this;
    }

    public function getPrixLivre(): ?float
    {
        return $this->prixLivre;
    }

    public function setPrixLivre(float $prixLivre): static
    {
        $this->prixLivre = $prixLivre;

        return $this;
    }

    public function getDateUploadLivre(): ?\DateTimeInterface
    {
        return $this->dateUploadLivre;
    }

    public function setDateUploadLivre(\DateTimeInterface $dateUploadLivre): static
    {
        $this->dateUploadLivre = $dateUploadLivre;

        return $this;
    }
}
