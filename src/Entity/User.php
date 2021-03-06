<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $roleId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Competition", mappedBy="creatorId")
     */
    private $competitions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Competitor", mappedBy="userId")
     */
    private $competitors;

    public function __construct()
    {
        $this->competitions = new ArrayCollection();
        $this->competitors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

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

    public function getRoleId(): ?Role
    {
        return $this->roleId;
    }

    public function setRoleId(?Role $roleId): self
    {
        $this->roleId = $roleId;

        return $this;
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
            $competition->setCreatorId($this);
        }

        return $this;
    }

    public function removeCompetition(Competition $competition): self
    {
        if ($this->competitions->contains($competition)) {
            $this->competitions->removeElement($competition);
            // set the owning side to null (unless already changed)
            if ($competition->getCreatorId() === $this) {
                $competition->setCreatorId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Competitor[]
     */
    public function getCompetitors(): Collection
    {
        return $this->competitors;
    }

    public function addCompetitor(Competitor $competitor): self
    {
        if (!$this->competitors->contains($competitor)) {
            $this->competitors[] = $competitor;
            $competitor->setUserId($this);
        }

        return $this;
    }

    public function removeCompetitor(Competitor $competitor): self
    {
        if ($this->competitors->contains($competitor)) {
            $this->competitors->removeElement($competitor);
            // set the owning side to null (unless already changed)
            if ($competitor->getUserId() === $this) {
                $competitor->setUserId(null);
            }
        }

        return $this;
    }
}
