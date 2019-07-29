<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompetitionRepository")
 */
class Competition
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
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="competitions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $statusId;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="competitions")
     */
    private $tagId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Encounter", mappedBy="competitionId", orphanRemoval=true)
     */
    private $encounters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Competitor", mappedBy="competitionId", orphanRemoval=true)
     */
    private $competitors;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="competitions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=1023, nullable=true)
     */
    private $location;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MatchDay", mappedBy="competition", orphanRemoval=true)
     */
    private $matchDays;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->tagId = new ArrayCollection();
        $this->encounters = new ArrayCollection();
        $this->competitors = new ArrayCollection();
        $this->matchDays = new ArrayCollection();
    }

    public function getId(): ?string
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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getStatusId(): ?Status
    {
        return $this->statusId;
    }

    public function setStatusId(?Status $statusId): self
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTagId(): Collection
    {
        return $this->tagId;
    }

    public function addTagId(Tag $tagId): self
    {
        if (!$this->tagId->contains($tagId)) {
            $this->tagId[] = $tagId;
        }

        return $this;
    }

    public function removeTagId(Tag $tagId): self
    {
        if ($this->tagId->contains($tagId)) {
            $this->tagId->removeElement($tagId);
        }

        return $this;
    }

    /**
     * @return Collection|Encounter[]
     */
    public function getEncounters(): Collection
    {
        return $this->encounters;
    }

    public function addEncounter(Encounter $encounter): self
    {
        if (!$this->encounters->contains($encounter)) {
            $this->encounters[] = $encounter;
            $encounter->setCompetitionId($this);
        }

        return $this;
    }

    public function removeEncounter(Encounter $encounter): self
    {
        if ($this->encounters->contains($encounter)) {
            $this->encounters->removeElement($encounter);
            // set the owning side to null (unless already changed)
            if ($encounter->getCompetitionId() === $this) {
                $encounter->setCompetitionId(null);
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
            $competitor->setCompetitionId($this);
        }

        return $this;
    }

    public function removeCompetitor(Competitor $competitor): self
    {
        if ($this->competitors->contains($competitor)) {
            $this->competitors->removeElement($competitor);
            // set the owning side to null (unless already changed)
            if ($competitor->getCompetitionId() === $this) {
                $competitor->setCompetitionId(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection|MatchDay[]
     */
    public function getMatchDays(): Collection
    {
        return $this->matchDays;
    }

    public function addMatchDay(MatchDay $matchDay): self
    {
        if (!$this->matchDays->contains($matchDay)) {
            $this->matchDays[] = $matchDay;
            $matchDay->setCompetition($this);
        }

        return $this;
    }

    public function removeMatchDay(MatchDay $matchDay): self
    {
        if ($this->matchDays->contains($matchDay)) {
            $this->matchDays->removeElement($matchDay);
            // set the owning side to null (unless already changed)
            if ($matchDay->getCompetition() === $this) {
                $matchDay->setCompetition(null);
            }
        }

        return $this;
    }
    
    public function __toString()
    {
        return $this->name;
    }
}
