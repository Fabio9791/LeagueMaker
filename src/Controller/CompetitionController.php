<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Competition;
use App\Form\CompetitionFormType;
use Symfony\Component\Routing\Annotation\Route;
use App\DTO\createCompetition;

class CompetitionController extends Controller
{

    /**
     * Creation of the form to create a competition
     *
     * @Route("/competition/create", name="create_competition", methods={"GET", "POST"})
     */
    public function createCompetition(Request $request, Session $session)
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST')
        return $this->render('Competition/create.html.twig', [
            'request' => $request
        ]);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $encounters = 'fabio is the best';
            $competition = new createCompetition();
            $competition->name = $request->request->get('name');
            $competition->name = $request->request->get('location');
            $solution = $_POST;
            return $this->render('about.html.twig', [
                'request' => $request,
                'encounters' => $encounters,
                'solution' => $solution
            ]);
        }
    }
}

