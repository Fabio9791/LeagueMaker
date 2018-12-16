<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Competition;

class ViewController extends AbstractController
{

    /**
     *
     * @Route("/competition/view", name="view_competition")
     */
    public function viewCompetition(Competition $competition)
    {
        $table = [];
        $competitors = $competition->getCompetitors();
        foreach ($competitors as $competitor) {
            array_push($table, [
                $competitor,
                0,
                0,
                0
            ]);
        }
        $encounters = $competition->getEncounters();
        foreach ($encounters as $encounter) {
            $scores = $encounter->scores;
            if ($scores[0]->score > $scores[1]->score) {
                $points0 = 3;
                $points1 = 0;
            } elseif ($scores[0]->score < $scores[1]->score) {
                $points0 = 0;
                $points1 = 3;
            } elseif ($scores[0]->score === $scores[1]->score && $scores[1]->score != null) {
                $points0 = 1;
                $points1 = 1;
            } else {
                $points0 = 0;
                $points1 = 0;
            }
            $comp0 = [
                $scores[0]->competitorId,
                $points0,
                $scores[0]->score,
                $scores[1]->score
            ];
            $comp1 = [
                $scores[1]->competitorId,
                $points1,
                $scores[1]->score,
                $scores[0]->score
            ];

            for ($i = 0; $i < sizeof($table); $i ++) {
                if ($table[$i][0] == $comp0[0]) {
                    $table[$i][1] += $comp0[1];
                    $table[$i][2] += $comp0[2];
                    $table[$i][3] += $comp0[3];
                }
                if ($table[$i][0] == $comp1[0]) {
                    $table[$i][1] += $comp1[1];
                    $table[$i][2] += $comp1[2];
                    $table[$i][3] += $comp1[3];
                }
            }
        }

        for ($i = 0; $i < sizeof($table) - 1; $i ++) {
            for ($j = $i + 1; $j < sizeof($table); $j ++) {
                if ($table[$i][1] < $table[$j][1] || ($table[$i][1] === $table[$j][1] && ($table[$i][2] - $table[$i][3] < $table[$j][2] - $table[$j][3] || ($table[$i][2] - $table[$i][3] === $table[$j][2] - $table[$j][3] && $table[$i][2] === $table[$j][2])))) {
                    $temp = $table[$i];
                    $table[$i] = $table[$j];
                    $table[$j] = $temp;
                }
            }
        }

        $matchDays = $competition->getMatchDays();
        for ($y = 0; $y < sizeof($matchDays) - 1; $y ++) {
            for ($i = $y + 1; $i < sizeof($matchDays); $i ++) {
                if (intval(substr($matchDays[$y]->getLabel, sizeof($matchDays[$y]->getLabel) - 1)) > intval(substr($matchDays[$i]->getLabel, sizeof($matchDays[$i]->getLabel) - 1))) {
                    $temp = $matchDays[$y];
                    $matchDays[$y] = $matchDays[$i];
                    $matchDays[$i] = $temp;
                }
            }
        }

        $table2 = [];
        for ($i = 0; $i < sizeof($matchDays); $i ++) {
            array_push($table2, [
                $matchDays[$i]->getLabel
            ]);
            $encounters = $matchDays[$i]->encounter;
            for ($j = 0; $j < sizeof($encounters); $j ++) {
                $scores = $encounters[$j]->scores;
                $encounter = [$scores[0]->getCompetitorId, $scores[0]->score , $scores[1]->score, $scores[1]->getCompetitorId];
            }
            array_push($table2[$i],[$encounter]);
        }

        return $this->render('Competition/view.html.twig', [
            'table' => $table,
            'table2' => $table2
        ]);
    }
}
