<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Competition;
use App\Form\CompetitionFormType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CompetitionController extends Controller
{
    /**
     * Creation of the form to create a competition
     * 
     * @Route("/competition/create", name="create_competition", methods={"GET", "POST"})
     */
    public function createCompetition(Request $request) : Response
    {
        $form = $this->createForm(CompetitionFormType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $competitionDTO = $form->getData();
            var_dump($competitionDTO);
        }
        
        return $this->render('Competition/create.html.twig', [
            'formObj' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/competition/list", name="competition_list")
     */
    public function competitionList()
    {
        $manager = $this->getDoctrine()->getManager();
        $competitionList = $manager->getRepository(Competition::class)->findAll();
        
        return $this->render('Competition/list.html.twig', ['competitions' => $competitionList]);
    }
}

