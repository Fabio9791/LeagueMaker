<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Competition;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Status;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Competitor;
use App\Service\MatchupGenerator;
use App\Entity\MatchDay;
use App\Entity\Encounter;
use App\Entity\Score;
use App\Entity\Tag;

class CompetitionController extends Controller
{

    /**
     * Creation of the form to create a competition
     *
     * @Route("/competition/create", name="create_competition", methods={"GET", "POST"})
     */
    public function createCompetition(Request $request, ObjectManager $manager, MatchupGenerator $generator)
    {
        if($this->getUser()==null)
        {
            return $this->redirectToRoute('homepage');
        }
        
        if ($request->request->count() == 0) 
        {
            $error = '';
            return $this->render('Competition/create.html.twig', [
                'request' => $request,
                'error' => $error
            ]);
        }

        if ($request->request->count() > 0) 
        {
            // //////////////////////////////////////////////
            // validation on these
            $error = '';
            $competitorData = intval($request->request->get('competitorData'));
            $error = $this->validateCompetitorNames($request, $generator, $competitorData, $error);
            $error = $this->validateNumberOfCompetitors($competitorData, $error);
            $name = $request->request->get('name');
            $error = $this->validateCompetitionName($manager, $name, $error);
            // ///////////////////////////////////////////////
            
            if ($error === '') 
            {
                $location = $this->prepareLocation($request);
                
                $competition = $this->persistCompetition($manager, $location, $name);
                $this->persistNameTag($manager, $competition, $name);
                $this->persistLocation($manager, $competition, $location);
                $this->persistTagData($request, $manager, $competition);
                $competitors = $this->persisteCompetitors($request, $manager, $competition, $competitorData);
                
                $league = $generator->createLeague($competitors);
                if ($request->request->get('homeVisitor') != null) 
                {
                    $league = $generator->homeVisitor($league);
                }
                $this->persisteLeague($manager, $competition, $league);

                $manager->flush();
                return $this->redirectToRoute('homepage');
            }

            return $this->render('Competition/create.html.twig', [
                'request' => $request,
                'error' => $error
            ]);
        }
    }
    
    private function validateCompetitionName(ObjectManager $manager, string $name ,string $error)
    {
        $getName = $manager->getRepository(Competition::class)->findOneByName($name);
        if ($name === '' || $getName != null)
        {
            $error = 'invalide name';
        }
        return $error;
    }
    
    private function validateNumberOfCompetitors(int $competitorData, string $error)
    {
        if($competitorData > 100)
        {
            $error='To many competitors, the limit is 100';
        }
        return $error;
    }
    
    private function validateCompetitorNames(Request $request, MatchupGenerator $generator, int $competitorData, string $error)
    {
        $competitorNames = [];
        for ($i = 1; $i <= $competitorData; $i ++)
        {
            $competitorInput = 'competitor' . $i;
            $competitorName = $request->request->get($competitorInput);
            if ($competitorName === '') {
                $error = 'All competitors must have a unique name';
            }
            array_push($competitorNames, $competitorName);
        }
        $result = $generator->allElementsUnique($competitorNames);
        if (! $result)
        {
            $error = 'All competitors must have a unique name';
        }
        
        return $error;
    }
    
    private function prepareLocation(Request $request)
    {
        $location = $request->request->get('location');
        if ($location === '')
        {
            $location = null;
        }
        return $location;
    }
    
    private function persistCompetition(ObjectManager $manager, string $location, string $name)
    {
        $date = new \DateTime();
        $status = $manager->getRepository(Status::class)->findOneByLabel('Futur');
        $competition = new Competition();
        $competition->setStatusId($status);
        $user = $this->getUser();
        $competition->setUser($user);
        $competition->setLocation($location);
        $competition->setName($name);
        $competition->setCreationDate($date);
        $manager->persist($competition);
        
        return $competition;
    }
    
    private function persistNameTag(ObjectManager $manager, Competition $competition, string $name)
    {
        $nameTag = $manager->getRepository(Tag::class)->findOneByLabel($name);
        if ($nameTag == null) {
            $nameTag = new Tag();
            $nameTag->setLabel($name);
            $nameTag->addCompetition($competition);
            $manager->persist($nameTag);
        }
        else
        {
            $competition->addTagId($nameTag);
            $manager->persist($competition);
        }
    }
    
    private function persistLocation(ObjectManager $manager, Competition $competition, string $location)
    {
        if ($location != null) {
            $LocationTag = $manager->getRepository(Tag::class)->findOneByLabel($location);
            if ($LocationTag == null)
            {
                $LocationTag = new Tag();
                $LocationTag->setLabel($location);
                $LocationTag->addCompetition($competition);
                $manager->persist($LocationTag);
            }
            else
            {
                $competition->addTagId($LocationTag);
                $manager->persist($competition);
            }
        }
    }
    
    private function persistTagData(Request $request, ObjectManager $manager, Competition $competition)
    {
        $tagData = intval($request->request->get('competitorData'));
        for ($i = 1; $i <= $tagData; $i ++)
        {
            $tagInput = 'tag' . $i;
            if ($request->request->get($tagInput) != '')
            {
                $tagLabel = $request->request->get($tagInput);
                if ($tagLabel !== '')
                {
                    $tag = $manager->getRepository(Tag::class)->findOneByLabel($tagLabel);
                    if ($tag == null)
                    {
                        $tag = new Tag();
                        $tag->setLabel($tagLabel);
                        $tag->addCompetition($competition);
                        $manager->persist($tag);
                    }
                    else
                    {
                        $competition->addTagId($tag);
                        $manager->persist($competition);
                    }
                }
            }
        }
    }
    
    private function persisteCompetitors(Request $request, ObjectManager $manager, Competition $competition, int $competitorData)
    {
        $competitors = [];
        for ($i = 1; $i <= $competitorData; $i ++)
        {
            $competitor = new Competitor();
            $competitorInput = 'competitor' . $i;
            $name = $request->request->get($competitorInput);
            $competitor->setName($name);
            $competitor->setCompetitionId($competition);
            $manager->persist($competitor);
            array_push($competitors, $competitor);
        }
        
        return $competitors;
    }
    
    private function persisteLeague(ObjectManager $manager, Competition $competition, array $league)
    {
        for ($i = 1; $i <= sizeof($league); $i ++)
        {
            $matchDay = new MatchDay();
            $matchDay->setLabel('Match Day ' . $i);
            $matchDay->setCompetition($competition);
            $manager->persist($matchDay);
            
            for ($j = 0; $j < sizeof($league[$i - 1]); $j ++)
            {
                $encounter = new Encounter();
                $encounter->setMatchDay($matchDay);
                $encounter->setCompetitionId($competition);
                
                $score1 = new Score();
                $score1->setEncounterId($encounter);
                $score1->setCompetitorId($league[$i - 1][$j][0]);
                
                $score2 = new Score();
                $score2->setEncounterId($encounter);
                $score2->setCompetitorId($league[$i - 1][$j][1]);
                
                if ($league[$i - 1][$j][1] != null && $league[$i - 1][$j][0] != null)
                {
                    $manager->persist($encounter);
                    $manager->persist($score1);
                    $manager->persist($score2);
                }
            }
        }
    }
}

