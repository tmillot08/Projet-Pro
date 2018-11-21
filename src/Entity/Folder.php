<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FolderRepository")
 */
class Folder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $hero;

    /**
     * @ORM\Column(type="text")
     */
    private $hacks;

    /**
     * @ORM\Column(type="text")
     */
    private $why;

    /**
     * @ORM\Column(type="text")
     */
    private $nextYear;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(
     *    message = "l\'url '{{ value }}' n'est pas valide",
     * )
     */
    private $soloLink;

    /**
     * @ORM\Column(type="integer")
     */
    private $soloBadge;

    /**
     * @Assert\Url(
     *    message = "l\'url '{{ value }}' n'est pas valide",
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $codeLink;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeBadge;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $english;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastDiplome;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="folder")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="folder")
     */
    private $note;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $finalNote;

    public function __construct()
    {
        $this->note = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHero(): ?string
    {
        return $this->hero;
    }

    public function setHero(string $hero): self
    {
        $this->hero = $hero;

        return $this;
    }

    public function getHacks(): ?string
    {
        return $this->hacks;
    }

    public function setHacks(string $hacks): self
    {
        $this->hacks = $hacks;

        return $this;
    }

    public function getWhy(): ?string
    {
        return $this->why;
    }

    public function setWhy(string $why): self
    {
        $this->why = $why;

        return $this;
    }

    public function getNextYear(): ?string
    {
        return $this->nextYear;
    }

    public function setNextYear(string $nextYear): self
    {
        $this->nextYear = $nextYear;

        return $this;
    }

    public function getSoloLink(): ?string
    {
        return $this->soloLink;
    }

    public function setSoloLink(string $soloLink): self
    {
        $this->soloLink = $soloLink;

        return $this;
    }

    public function getSoloBadge(): ?int
    {
        return $this->soloBadge;
    }

    public function setSoloBadge(int $soloBadge): self
    {
        $this->soloBadge = $soloBadge;

        return $this;
    }

    public function getCodeLink(): ?string
    {
        return $this->codeLink;
    }

    public function setCodeLink(string $codeLink): self
    {
        $this->codeLink = $codeLink;

        return $this;
    }

    public function getCodeBadge(): ?int
    {
        return $this->codeBadge;
    }

    public function setCodeBadge(int $codeBadge): self
    {
        $this->codeBadge = $codeBadge;

        return $this;
    }

    public function getEnglish(): ?string
    {
        return $this->english;
    }

    public function setEnglish(string $english): self
    {
        $this->english = $english;

        return $this;
    }

    public function getLastDiplome(): ?string
    {
        return $this->lastDiplome;
    }

    public function setLastDiplome(string $lastDiplome): self
    {
        $this->lastDiplome = $lastDiplome;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNote(): Collection
    {
        return $this->note;
    }

    public function addNote(Note $note): self
    {
        if (!$this->note->contains($note)) {
            $this->note[] = $note;
            $note->setFolder($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->note->contains($note)) {
            $this->note->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getFolder() === $this) {
                $note->setFolder(null);
            }
        }

        return $this;
    }

    public function getFinalNote(): ?int
    {
        return $this->finalNote;
    }

    public function setFinalNote(?int $finalNote): self
    {
        $this->finalNote = $finalNote;

        return $this;
    }
}
