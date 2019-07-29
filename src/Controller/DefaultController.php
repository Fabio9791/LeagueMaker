<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Competition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Tag;
use Doctrine\ORM\QueryBuilder;
use App\Entity\Status;

class DefaultController extends Controller
{

    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
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
        $user = $this->getUser();
        $myCompetitions = $manager->getRepository(Competition::class)->findByUser($user);

        $getTag = $request->request->get('tagSearch');
        $tag = $manager->getRepository(Tag::class)->findOneByLabel($getTag);
        if ($tag != null) {
            $tagCompetitions = $tag->getCompetitions();
        } else {
            $tagCompetitions = [];
        }

        $status = $manager->getRepository(Status::class)->findByLabel('Ongoing');
        $onGoingCompetitions = $manager->getRepository(Competition::class)->findByStatus($status);

        $status = $manager->getRepository(Status::class)->findByLabel('Finished');
        $finishedCompetitions = $manager->getRepository(Competition::class)->findByStatus($status);

        $status = $manager->getRepository(Status::class)->findByLabel('Futur');
        $futurCompetitions = $manager->getRepository(Competition::class)->findByStatus($status);

        return new Response($this->twig->render('homepage.html.twig', [
            'myCompetitions' => $myCompetitions,
            'tagCompetitions' => $tagCompetitions,
            'onGoingCompetitions' => $onGoingCompetitions,
            'finishedCompetitions' => $finishedCompetitions,
            'futurCompetitions' => $futurCompetitions
        ]));
    }
}
