<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScoreRepository")
 */
class Score
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Encounter", inversedBy="scores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $encounterId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Competitor", inversedBy="scores", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $competitorId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getEncounterId(): ?Encounter
    {
        return $this->encounterId;
    }

    public function setEncounterId(?Encounter $encounterId): self
    {
        $this->encounterId = $encounterId;

        return $this;
    }

    public function getCompetitorId(): ?Competitor
    {
        return $this->competitorId;
    }

    public function setCompetitorId(?Competitor $competitorId): self
    {
        $this->competitorId = $competitorId;

        return $this;
    }
}
