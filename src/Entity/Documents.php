<?php

namespace App\Entity;

use App\Repository\DocumentsRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=DocumentsRepository::class)
 */
class Documents
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
    // document lie a lui meme alors quil faut le lier sur le controle
    /**
     * @ORM\ManyToOne(targetEntity=Documents::class, inversedBy="Controls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $controls;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }
    public function getControls(): ?Controls
    {
        return $this->controls;
    }

    /**
     * @param Controls $controls
     */
    public function setControls(?Controls $controls): self
    {
        $this->controls = $controls;

        return $this;
    }




    public function __toString()
    {
        return $this->getName();
    }

}
