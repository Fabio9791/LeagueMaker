<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatchDayRepository")
 */
class MatchDay
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
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Encounter", mappedBy="matchDay", orphanRemoval=true)
     */
    private $encounter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Competition", inversedBy="matchDays")
     * @ORM\JoinColumn(nullable=false)
     */
    private $competition;

    public function __construct()
    {
        $this->encounter = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|Encounter[]
     */
    public function getEncounter(): Collection
    {
        return $this->encounter;
    }

    public function addEncounter(Encounter $encounter): self
    {
        if (!$this->encounter->contains($encounter)) {
            $this->encounter[] = $encounter;
            $encounter->setMatchDay($this);
        }

        return $this;
    }

    public function removeEncounter(Encounter $encounter): self
    {
        if ($this->encounter->contains($encounter)) {
            $this->encounter->removeElement($encounter);
            // set the owning side to null (unless already changed)
            if ($encounter->getMatchDay() === $this) {
                $encounter->setMatchDay(null);
            }
        }

        return $this;
    }

    public function getCompetition(): ?Competition
    {
        return $this->competition;
    }

    public function setCompetition(?Competition $competition): self
    {
        $this->competition = $competition;

        return $this;
    }
}
