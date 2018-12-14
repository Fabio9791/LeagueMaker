<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Competition", mappedBy="user", orphanRemoval=true)
     */
    private $competitions;
    
    public function __construct()
    {
        parent::__construct();
        $this->competitions = new ArrayCollection();
        $this->competitors = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }
    
    public function getUsername(): ?string
    {
        return $this->username;
    }
    
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return Collection|Competition[]
     */
    public function getCompetitions(): Collection
    {
        return $this->competitions;
    }

    public function addCompetition(Competition $competition): self
    {
        if (!$this->competitions->contains($competition)) {
            $this->competitions[] = $competition;
            $competition->setUser($this);
        }

        return $this;
    }

    public function removeCompetition(Competition $competition): self
    {
        if ($this->competitions->contains($competition)) {
            $this->competitions->removeElement($competition);
            // set the owning side to null (unless already changed)
            if ($competition->getUser() === $this) {
                $competition->setUser(null);
            }
        }

        return $this;
    }

}
