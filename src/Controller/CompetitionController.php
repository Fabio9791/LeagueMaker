<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Competition;
use App\Form\CompetitionFormType;
use Symfony\Component\Routing\Annotation\Route;

class CompetitionController extends Controller
{
    /**
     * Creation of the form to create a competition
     * 
     * @Route("/competition/create", name="create_competition", methods={"GET", "POST"})
     */
    public function createCompetition(Request $request, Session $session)
    {
        $competition = new Competition();
        $form = $this->createForm(CompetitionFormType::class, $competition, ['standalone' => true]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($competition);
            $manager->flush();
            
            $session->getFlashBag()->add('success', 'The competition was created');
            
            return $this->redirectToRoute('homepage');
        }
        
        return $this->render('Competition/create.html.twig', ['formObj' => $form->createView()]);
    }
}

