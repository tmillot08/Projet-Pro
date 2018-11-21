<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *  fields= {"mail"},
 *  message= "L'email que vous avez indiqué est déjà utilisé!"
 * )
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message= "Le champs doit contenir seulement des lettres"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message= "Le champs doit contenir seulement des lettres"
     * )
     */
    private $fName;

    /**
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(
     *     value = 12,
     *     message= "Votre age est incorrect"
     * )
     * @Assert\LessThan(
     *     value = 100,
     *     message= "Votre age est incorrect"
     * )
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message= "Le champs doit contenir seulement des lettres"
     * 
     * )
     */
    private $town;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message= "Le champs doit contenir seulement des lettres"
     * )
     */
    private $nationality;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Email(
     *     message = "l\'email '{{ value }}' n\'est pas valide.",
     * )
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 10,
     *      max = 12,
     *      minMessage = "Votre numero de téléphone est trop court",
     *      maxMessage = "Votre numero de téléphone est trop long"
     * )
     */
    private $number;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Folder", mappedBy="user")
     */
    private $folder;

    public function __construct()
    {
        $this->folder = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFName(): ?string
    {
        return $this->fName;
    }

    public function setFName(string $fName): self
    {
        $this->fName = $fName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }


    


    /**
     * @return Collection|Folder[]
     */
    public function getFolder(): Collection
    {
        return $this->folder;
    }

    public function addFolder(Folder $folder): self
    {
        if (!$this->folder->contains($folder)) {
            $this->folder[] = $folder;
            $folder->setUser($this);
        }

        return $this;
    }

    public function removeFolder(Folder $folder): self
    {
        if ($this->folder->contains($folder)) {
            $this->folder->removeElement($folder);
            // set the owning side to null (unless already changed)
            if ($folder->getUser() === $this) {
                $folder->setUser(null);
            }
        }

        return $this;
    }
}
