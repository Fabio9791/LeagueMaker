<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Competition;
use App\Entity\Status;
use App\Service\LeagueDisplayer;
use Doctrine\Common\Collections\Collection;

class ManageController extends Controller
{
    /**
     *
     * @Route("/competition/manage/{id}", name="manage_competition", methods={"GET", "POST"})
     */
    public function manageCompetition(Request $request, ObjectManager $manager, LeagueDisplayer $displayer, string $id)
    {
        $competition = $manager->getRepository(Competition::class)->findOneById($id);
        if ($this->getUser() != $competition->getUser())
        {
            return $this->redirectToRoute('homepage');
        }
        
        $matchDays = $displayer->orderByMatchDays($competition->getMatchDays());
        if ($request->request->count() > 0)
        {
            $this->requestScores($request, $manager, $competition, $matchDays);
        }
        
        $matchDayTable = $displayer->prepareMatchDayTable($matchDays);
    
        return $this->render('Competition/manage.html.twig', [
            'matchDayTable' => $matchDayTable
        ]);
    }
    
    private function requestScores(Request $request, ObjectManager $manager, Competition $competition, Collection $matchDays)
    {
        $finished = true;
        $started = false;
        
        for ($i = 1; $i <= sizeof($matchDays); $i ++)
        {
            $encounters = $matchDays[$i - 1]->getEncounter();
            for ($j = 0; $j < sizeof($encounters); $j ++)
            {
                $scores = $encounters[$j]->getScores();
                $nameNb = $scores[0]->getCompetitorId()->getName() . $i;
                $scoreOfHomeTeam = $request->request->get($nameNb);
                if ($scoreOfHomeTeam == '')
                {
                    $scoreOfHomeTeam = null;
                } else
                {
                    $scoreOfHomeTeam = intval($scoreOfHomeTeam);
                }
                $nameNb = $scores[1]->getCompetitorId()->getName() . $i;
                $scoreOfAwayTeam = $request->request->get($nameNb);
                if ($scoreOfAwayTeam == '')
                {
                    $scoreOfAwayTeam = null;
                } else
                {
                    $scoreOfAwayTeam = intval($scoreOfAwayTeam);
                }
                
                if (is_int($scoreOfHomeTeam) && is_int($scoreOfAwayTeam))
                {
                    $scores[0]->setScore($scoreOfHomeTeam);
                    $scores[1]->setScore($scoreOfAwayTeam);
                    $started = true;
                } else
                {
                    $scores[0]->setScore(null);
                    $scores[1]->setScore(null);
                    $finished = false;
                }
            }
        }
        $this->setStatus($manager, $competition, $finished, $started);
        $manager->flush();
    }
    
    private function setStatus(ObjectManager $manager, Competition $competition, bool $finished, bool $started)
    {
        if ($finished)
        {
            $status = $manager->getRepository(Status::class)->findOneByLabel('Finished');
            $competition->setStatusId($status);
        } elseif ($started)
        {
            $status = $manager->getRepository(Status::class)->findOneByLabel('Ongoing');
            $competition->setStatusId($status);
        } else
        {
            $status = $manager->getRepository(Status::class)->findOneByLabel('Futur');
            $competition->setStatusId($status);
        }
    }
}
