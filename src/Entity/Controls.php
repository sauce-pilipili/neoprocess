<?php

namespace App\Entity;

use App\Repository\ControlsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ControlsRepository::class)
 */
class Controls
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
    private $name;

    /**
     * @Assert\Valid()
     * @Assert\Count(min = 1, max= 4)
     * @ORM\OneToMany(targetEntity=Documents::class, mappedBy="Controls", orphanRemoval=true, cascade={"persist"})
     */
    private $documents;

    /**
     * @ORM\OneToMany(targetEntity=Dossiers::class, mappedBy="pieces")
     */
    private $dossiers;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->dossiers = new ArrayCollection();
    }

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }


    /**
     * @return Collection<int, Documents>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Documents $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
        }

        return $this;
    }

    public function removeDocument(Documents $document): self
    {
        $this->documents->removeElement($document);

        return $this;
    }

    public function __toString(){
        return $this->getName();
    }

    /**
     * @return Collection<int, Dossiers>
     */
    public function getDossiers(): Collection
    {
        return $this->dossiers;
    }

    public function addDossier(Dossiers $dossier): self
    {
        if (!$this->dossiers->contains($dossier)) {
            $this->dossiers[] = $dossier;
            $dossier->setPieces($this);
        }

        return $this;
    }

    public function removeDossier(Dossiers $dossier): self
    {
        if ($this->dossiers->removeElement($dossier)) {
            // set the owning side to null (unless already changed)
            if ($dossier->getPieces() === $this) {
                $dossier->setPieces(null);
            }
        }

        return $this;
    }

}
