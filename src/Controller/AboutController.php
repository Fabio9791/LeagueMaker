<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MatchupGenerator;

class AboutController extends Controller
{
    /**
     * About page of our Application
     *
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('about.html.twig');
    }
}
