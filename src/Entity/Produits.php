<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getProduits'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "le nom est obligatoire")]
    #[Groups(['getProduits'])]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "mets un prix entier")]
    #[Groups(['getProduits'])]
    private ?int $prix = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getProduits'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getProduits'])]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[Groups(['getProduits'])]
    private ?Categorie $id_categ = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getIdCateg(): ?Categorie
    {
        return $this->id_categ;
    }

    public function setIdCateg(?Categorie $id_categ): self
    {
        $this->id_categ = $id_categ;

        return $this;
    }
}
