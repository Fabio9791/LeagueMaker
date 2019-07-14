<?php
namespace App\Service;

use Doctrine\Common\Collections\Collection;

class LeagueDisplayer
{
    public function orderByMatchDays(Collection $matchDays)
    {
    
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
        return $matchDays;
    }
    
    public function prepareMatchDayTable(Collection $matchDays)
    {
        $matchDayTable = [];
        for ($i = 0; $i < sizeof($matchDays); $i ++)
        {
            array_push($matchDayTable, [
                $matchDays[$i]->getLabel()
            ]);
            $encounters = $matchDays[$i]->getEncounter();
            $encountersArray = [];
            for ($j = 0; $j < sizeof($encounters); $j ++)
            {
                $scores = $encounters[$j]->getScores();
                $encounter = [
                    'HomeTeam' => $scores[0]->getCompetitorId()->getName(),
                    'HomeScore' => $scores[0]->getScore(),
                    'AwayScore' => $scores[1]->getScore(),
                    'AwayTeam'=> $scores[1]->getCompetitorId()->getName()
                ];
                array_push($encountersArray, $encounter);
            }
            array_push($matchDayTable[$i], $encountersArray);
        }
        return $matchDayTable;
    }
}

