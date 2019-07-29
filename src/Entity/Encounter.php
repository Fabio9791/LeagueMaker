<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EncounterRepository")
 */
class Encounter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Competition", inversedBy="encounters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $competitionId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Score", mappedBy="encounterId", orphanRemoval=true)
     */
    private $scores;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MatchDay", inversedBy="encounter")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matchDay;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getCompetitionId(): ?Competition
    {
        return $this->competitionId;
    }

    public function setCompetitionId(?Competition $competitionId): self
    {
        $this->competitionId = $competitionId;

        return $this;
    }

    /**
     * @return Collection|Score[]
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): self
    {
        if (!$this->scores->contains($score)) {
            $this->scores[] = $score;
            $score->setEncounterId($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->contains($score)) {
            $this->scores->removeElement($score);
            // set the owning side to null (unless already changed)
            if ($score->getEncounterId() === $this) {
                $score->setEncounterId(null);
            }
        }

        return $this;
    }

    public function getMatchDay(): ?MatchDay
    {
        return $this->matchDay;
    }

    public function setMatchDay(?MatchDay $matchDay): self
    {
        $this->matchDay = $matchDay;

        return $this;
    }
}
