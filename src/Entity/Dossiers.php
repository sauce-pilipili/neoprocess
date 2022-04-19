<?php

namespace App\Entity;

use App\Repository\DossiersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DossiersRepository::class)
 */
class Dossiers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\ManyToOne(targetEntity=Controls::class, inversedBy="dossiers")
     */
    private $pieces;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $piece_0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $piece_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $piece_2;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $valid_0 =0;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $valid_1=0;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $valid_2=0;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPieces(): ?Controls
    {
        return $this->pieces;
    }

    public function setPieces(?Controls $pieces): self
    {
        $this->pieces = $pieces;

        return $this;
    }

    public function getPiece0(): ?string
    {
        return $this->piece_0;
    }

    public function setPiece0(?string $piece_0): self
    {
        $this->piece_0 = $piece_0;

        return $this;
    }

    public function getPiece1(): ?string
    {
        return $this->piece_1;
    }

    public function setPiece1(?string $piece_1): self
    {
        $this->piece_1 = $piece_1;

        return $this;
    }

    public function getPiece2(): ?string
    {
        return $this->piece_2;
    }

    public function setPiece2(?string $piece_2): self
    {
        $this->piece_2 = $piece_2;

        return $this;
    }

    public function getValid0(): ?bool
    {
        return $this->valid_0;
    }

    public function setValid0(?bool $valid_0): self
    {
        $this->valid_0 = $valid_0;

        return $this;
    }

    public function getValid1(): ?bool
    {
        return $this->valid_1;
    }

    public function setValid1(?bool $valid_1): self
    {
        $this->valid_1 = $valid_1;

        return $this;
    }
    public function getValid2(): ?bool
    {
        return $this->valid_2;
    }

    public function setValid2(?bool $valid_2): self
    {
        $this->valid_2 = $valid_2;

        return $this;
    }
}
