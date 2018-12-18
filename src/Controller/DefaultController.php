<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Competition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Tag;

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
    public function homepage(Request $request, ObjectManager $manager)
    {
        dump($request);
        $user = $this->getUser();
        $myCompetitions = $manager->getRepository(Competition::class)->findByUser($user);
        
        $getTag = $request->request->get('tagSearch');
        $tag = $manager->getRepository(Tag::class)->findOneByLabel($getTag);
        dump($tag);
        if ($tag != null) {
            $tagCompetitions = $tag->getCompetitions();
            dump($tagCompetitions);
        } else {
            $tagCompetitions = [];
        }
        
        return new Response($this->twig->render('homepage.html.twig', ['myCompetitions' => $myCompetitions, 'tagCompetitions' => $tagCompetitions]));
    }
}
