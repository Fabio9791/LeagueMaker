<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Competition;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\LeagueDisplayer;

class ViewController extends Controller
{

    /**
     *
     * @Route("/competition/view/{id}", name="view_competition", methods={"GET", "POST"})
     */
    public function viewCompetition(Request $request, ObjectManager $manager, LeagueDisplayer $displayer, string $id)
    {
        $competition = $manager->getRepository(Competition::class)->findOneById($id); 
        $competitors = $competition->getCompetitors();
        
        
        $rankingTable = $displayer->initiateRanking($competitors);

        $encounters = $competition->getEncounters();
        $rankingTable = $displayer->prepareRankingTable($encounters, $rankingTable);
        
        $rankingTable = $displayer->orderRanking($rankingTable);
        
        $matchDays = $displayer->orderByMatchDays($competition->getMatchDays());
        $matchDayTable = $displayer->prepareMatchDayTable($matchDays);
        
        return $this->render('Competition/view.html.twig', [
            'rankingTable' => $rankingTable,
            'matchDayTable' => $matchDayTable,
            'location' => $competition->getLocation()
        ]);
    }
}
