<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Status;
use App\Entity\User;
use App\Entity\Competition;
use App\Entity\Tag;
use App\Entity\Competitor;
use App\Entity\Encounter;
use App\Entity\MatchDay;
use App\Entity\Score;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $this->loadStatus($manager);
        $manager->flush();
        $user = new User();
        $user->setPassword('$13$ennsc31n271R0jO78MMYjOH3hVyPWzcmvlfkzfGTskYLO5eXuMXeC');
        $user->setUsername('qwe');
        $user->setEmail('stefano@gmail.com');
        $manager->persist($user);
        $manager->flush();
        $this->loadCompetition($manager);
        $manager->flush();
    }

    public function loadStatus(ObjectManager $manager)
    {
        $statusList = [
            'Ongoing',
            'Finished',
            'Futur'
        ];

        foreach ($statusList as $statusLabel) {
            $status = new Status();
            $status->setLabel($statusLabel);
            $manager->persist($status);
        }
    }
    
    
    public function loadCompetition(ObjectManager $manager)
    {
        // Competition
        $competition = new Competition();
        $competition->setName('Ultimate Tournament');
        $competition->setLocation('Bettembourg');
        $date = new \DateTime();
        $competition->setCreationDate($date);
        $status = $manager->getRepository(Status::class)->findOneByLabel('Finished');
        $competition->setStatusId($status);
        $user = $manager->getRepository(User::class)->findOneByUsername('qwe');
        $competition->setUser($user);
        $manager->persist($competition);
        
        // tags
        $tag = new Tag();
        $tag->setLabel($competition->getName());
        $manager->persist($tag);
        $tag2 = new Tag();
        $tag2->setLabel($competition->getLocation());
        $manager->persist($tag2);
        
        // competitor
        $competitor = new Competitor();
        $competitor->setName('safir');
        $competitor->setCompetitionId($competition);
        $manager->persist($competitor);
        
        $competitor2 = new Competitor();
        $competitor2->setName('fabio');
        $competitor2->setCompetitionId($competition);
        $manager->persist($competitor2);
            
        $competitor3 = new Competitor();
        $competitor3->setName('ricardo');
        $competitor3->setCompetitionId($competition);
        $manager->persist($competitor3);
          
        $competitor4 = new Competitor();
        $competitor4->setName('alves');
        $competitor4->setCompetitionId($competition);
        $manager->persist($competitor3);
        
        // Match day 1
        $matchDay = new MatchDay();
        $matchDay->setLabel('Match Day 1');
        $matchDay->setCompetition($competition);
        $manager->persist($matchDay);

        // Encounter 1
        $encounter = new Encounter();
        $encounter->setMatchDay($matchDay);
        $encounter->setCompetitionId($competition);
        $manager->persist($encounter);
        // Score Competitor2
        $score = new Score();
        $score->setScore(19);
        $score->setEncounterId($encounter);
        $score->setCompetitorId($competitor);
        $manager->persist($score);
        // Score Competitor3
        $score = new Score();
        $score->setScore(2);
        $score->setEncounterId($encounter);
        $score->setCompetitorId($competitor3);
        $manager->persist($score);
        
        
        // Encounter 2
        $encounter = new Encounter();
        $encounter->setMatchDay($matchDay);
        $encounter->setCompetitionId($competition);
        $manager->persist($encounter);
        // Score Competitor2
        $score = new Score();
        $score->setScore(6);
        $score->setEncounterId($encounter);
        $score->setCompetitorId($competitor2);
        $manager->persist($score);
        // Score Competitor4
        $score = new Score();
        $score->setScore(5);
        $score->setEncounterId($encounter);
        $score->setCompetitorId($competitor4);
        $manager->persist($score);
        
        // Match day 2
        $matchDay = new MatchDay();
        $matchDay->setLabel('Match Day 2');
        $matchDay->setCompetition($competition);
        $manager->persist($matchDay);
        
        // Encounter 1
        $encounter = new Encounter();
        $encounter->setMatchDay($matchDay);
        $encounter->setCompetitionId($competition);
        $manager->persist($encounter);
        // Score Competitor2
        $score = new Score();
        $score->setScore(19);
        $score->setEncounterId($encounter);
        $score->setCompetitorId($competitor4);
        $manager->persist($score);
        // Score Competitor3
        $score = new Score();
        $score->setScore(2);
        $score->setEncounterId($encounter);
        $score->setCompetitorId($competitor3);
        $manager->persist($score);
        
        
        // Encounter 2
        $encounter = new Encounter();
        $encounter->setMatchDay($matchDay);
        $encounter->setCompetitionId($competition);
        $manager->persist($encounter);
        // Score Competitor2
        $score = new Score();
        $score->setScore(6);
        $score->setEncounterId($encounter);
        $score->setCompetitorId($competitor2);
        $manager->persist($score);
        // Score Competitor4
        $score = new Score();
        $score->setScore(5);
        $score->setEncounterId($encounter);
        $score->setCompetitorId($competitor);
        $manager->persist($score);
        
        // match day 3
        $matchDay = new MatchDay();
        $matchDay->setLabel('Match Day 3');
        $matchDay->setCompetition($competition);
        $manager->persist($matchDay);
        
        // Encounter 1
        $encounter = new Encounter();
        $encounter->setMatchDay($matchDay);
        $encounter->setCompetitionId($competition);
        $manager->persist($encounter);
        // Score Competitor2
        $score = new Score();
        $score->setScore(19);
        $score->setEncounterId($encounter);
        $score->setCompetitorId($competitor4);
        $manager->persist($score);
        // Score Competitor3
        $score = new Score();
        $score->setScore(2);
        $score->setEncounterId($encounter);
        $score->setCompetitorId($competitor);
        $manager->persist($score);
        
        // Encounter 2
        $encounter = new Encounter();
        $encounter->setMatchDay($matchDay);
        $encounter->setCompetitionId($competition);
        $manager->persist($encounter);
        // Score Competitor2
        $score = new Score();
        $score->setScore(6);
        $score->setEncounterId($encounter);
        $score->setCompetitorId($competitor3);
        $manager->persist($score);
        // Score Competitor4
        $score = new Score();
        $score->setScore(5);
        $score->setEncounterId($encounter);
        $score->setCompetitorId($competitor2);
        $manager->persist($score);
    }
}

