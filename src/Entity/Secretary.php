<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SecretaryRepository")
 * @UniqueEntity(
 *  fields= {"mail"},
 *  message= "L'email que vous avez indiqué est déjà utilisé!"
 * )
 */
class Secretary implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     message = "l\'email '{{ value }}' n\'est pas valide.",
     * )
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = "8", minMessage= "Votre mot de passe doit contenir au moins 8 caractere")
     */
    private $password;

    /**
     * @ORM\Column(type="smallint")
     */
    private $firstlogin;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vos mot de passe sont différent ")
     */
    public $confirmPassword;

    public function __construct()
    {
        $this->user = new ArrayCollection();
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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->mail;
    }

    public function eraseCredentials()
    {}

    public function getSalt(){}

    public function getRoles(){
        return ['ROLE_SECRETARY'];
    }


    public function getFirstlogin(): ?int
    {
        return $this->firstlogin;
    }

    public function setFirstlogin(int $firstlogin): self
    {
        $this->firstlogin = $firstlogin;

        return $this;
    }

    
}
