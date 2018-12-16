<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MatchupGenerator;

class AboutController extends Controller
{
 
    public function theGenerator(MatchupGenerator $theMachine)
    {
        $encouters=[];
        $competitorList = ['safir','alves','ricardo','fabio'];
        $encounters=$theMachine->createLeague($competitorList);
        return $encounters;
        
    }

    /**
     * About page of our Application
     *
     * @Route("/about", name="about")
     */
    public function about()
    {
        $theMachine = new MatchupGenerator();
        $league = $this->theGenerator($theMachine); 
        $request='fabio is the best';
        return $this->render('about.html.twig',[
            'league' => $league,
            'request' => $request
        ]);
    }
}
