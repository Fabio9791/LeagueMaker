<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Tag;

class TagController extends Controller
{
    /**
     * Search by tags
     * 
     * @Route("/competition/search", name="search_competition", methods={"GET", "OPTIONS"})
     */
    public function search(Request $request)
    {
        if (!$request->query->has('pattern')) {
            return new JsonResponse(
                ['errors' => ['Unspecified pattern']],
                400
            );
        }
        
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository(Tag::class);
        $pattern = $request->query->get('pattern');
        
        $tags = $repository->findByNameLike($pattern);
        
        $response = $this->json(
            ['data' => $tags],
            200,
            [],
            ['groups' => ['tag', 'competition.name']]
        );
        
        if ($request->getMethod() == 'OPTIONS') {
            $response->setContent('');
        }
        
        return $response;
    }
}

