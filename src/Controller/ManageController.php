<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Competition;
use App\Entity\Status;

class ManageController extends Controller
{

    /**
     *
     * @Route("/competition/manage/{id}", name="manage_competition", methods={"GET", "POST"})
     */
    public function manageCompetition(Request $request, ObjectManager $manager, string $id)
    {
        $competition = $manager->getRepository(Competition::class)->findOneById($id);
        if ($this->getUser() != $competition->getUser()) {
            return $this->redirectToRoute('homepage');
        }
        $finished = true;
        $started = false;
        if ($request->request->count() > 0) {
            $matchDays = $competition->getMatchDays();
            for ($y = 0; $y < sizeof($matchDays) - 1; $y ++) {
                for ($i = $y + 1; $i < sizeof($matchDays); $i ++) {
                    if (intval(substr($matchDays[$y]->getLabel(), strlen($matchDays[$y]->getLabel()) - 1)) > intval(substr($matchDays[$i]->getLabel(), strlen($matchDays[$i]->getLabel()) - 1))) {
                        $temp = $matchDays[$y];
                        $matchDays[$y] = $matchDays[$i];
                        $matchDays[$i] = $temp;
                    }
                }
            }

            for ($i = 1; $i <= sizeof($matchDays); $i ++) {
                $encounters = $matchDays[$i - 1]->getEncounter();
                for ($j = 0; $j < sizeof($encounters); $j ++) {
                    $scores = $encounters[$j]->getScores();
                    $nameNb = $scores[0]->getCompetitorId()->getName() . $i;
                    $get0 = $request->request->get($nameNb);
                    if ($get0 == '') {
                        $get0 = null;
                    } else {
                        $get0 = intval($get0);
                    }
                    $nameNb = $scores[1]->getCompetitorId()->getName() . $i;
                    $get1 = $request->request->get($nameNb);
                    if ($get1 == '') {
                        $get1 = null;
                    } else {
                        $get1 = intval($get1);
                    }
                    if (is_int($get0) && is_int($get1)) {
                        $scores[0]->setScore($get0);
                        $scores[1]->setScore($get1);
                        $started = true;
                    } else {
                        $scores[0]->setScore(null);
                        $scores[1]->setScore(null);
                        $finished = false;
                    }
                }
            }
            if ($finished) {
                $status = $manager->getRepository(Status::class)->findOneByLabel('Finished');
                $competition->setStatusId($status);
            } elseif ($started) {
                $status = $manager->getRepository(Status::class)->findOneByLabel('Ongoing');
                $competition->setStatusId($status);
            } else {
                $status = $manager->getRepository(Status::class)->findOneByLabel('Futur');
                $competition->setStatusId($status);
            }
            $manager->flush();
        }

        $matchDays = $competition->getMatchDays();
        for ($y = 0; $y < sizeof($matchDays) - 1; $y ++) {
            $explodedMatchday = explode(" ", $matchDays[$y]->getLabel());
            $dayToCompare = intval($explodedMatchday[1]);
            for ($i = $y + 1; $i < sizeof($matchDays); $i ++) {
                $explodedMatchday = explode(" ", $matchDays[$i]->getLabel());
                $day = intval($explodedMatchday[1]);

                if ($dayToCompare > $day) {
                    $temp = $matchDays[$y];
                    $matchDays[$y] = $matchDays[$i];
                    $matchDays[$i] = $temp;
                }
            }
        }

        $table2 = [];
        for ($i = 0; $i < sizeof($matchDays); $i ++) {
            array_push($table2, [
                $matchDays[$i]->getLabel()
            ]);
            $encounters = $matchDays[$i]->getEncounter();
            $encountersArray = [];
            for ($j = 0; $j < sizeof($encounters); $j ++) {
                $scores = $encounters[$j]->getScores();
                $encounter = [
                    $scores[0]->getCompetitorId()->getName(),
                    $scores[0]->getScore(),
                    $scores[1]->getScore(),
                    $scores[1]->getCompetitorId()->getName()
                ];
                array_push($encountersArray, $encounter);
            }
            array_push($table2[$i], $encountersArray);
        }

        return $this->render('Competition/manage.html.twig', [
            'table2' => $table2
        ]);
    }
}
