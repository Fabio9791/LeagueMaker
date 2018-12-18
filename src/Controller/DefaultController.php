<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Competition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    private $twig;
    
    public function __construct(
        \Twig_Environment $twig
        ) {
            $this->twig = $twig;
    }
    /**
     * Homepage
     *
     * The homepage of the application
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        $user = $this->getUser();
        $manager = $this->getDoctrine()->getManager();
        $competitions = $manager->getRepository(Competition::class)->findByUser($user);
        dump($competitions);
            
        return new Response($this->twig->render('homepage.html.twig', ['competitions' => $competitions]));
    }
}
