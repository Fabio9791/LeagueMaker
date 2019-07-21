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
                'MatchDayLabel' => $matchDays[$i]->getLabel()
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
            $matchDayTable[$i]['Encounters'] = $encountersArray;
        }
        return $matchDayTable;
    }
    
    public function orderRanking(array $table)
    {
        for ($i = 0; $i < sizeof($table) - 1; $i ++)
        {
            for ($j = $i + 1; $j < sizeof($table); $j ++)
            {
                if ($table[$i]['Points'] < $table[$j]['Points'] ||
                    ($table[$i]['Points'] === $table[$j]['Points'] &&
                        ($table[$i]['ScoredGoals'] - $table[$i]['GoalsConceded'] < $table[$j]['ScoredGoals'] - $table[$j]['GoalsConceded'] ||
                            ($table[$i]['ScoredGoals'] - $table[$i]['GoalsConceded'] === $table[$j]['ScoredGoals'] - $table[$j]['GoalsConceded'] &&
                                $table[$i]['ScoredGoals'] < $table[$j]['ScoredGoals']))))
                {
                    $temp = $table[$i];
                    $table[$i] = $table[$j];
                    $table[$j] = $temp;
                }
            }
        }
        return $table;
    }
    
    public function initiateRanking(Collection $competitors)
    {
        $table = [];
        foreach ($competitors as $competitor)
        {
            array_push($table, [
                'TeamName' => $competitor->getName(),
                'Points' => 0,
                'ScoredGoals' => 0,
                'GoalsConceded' => 0
            ]);
        }
        return $table;
    }
    
    public function prepareRankingTable(Collection $encounters, array $rankingTable)
    {
        foreach ($encounters as $encounter)
        {
            $scores = $encounter->getScores();
            
            if ($scores[0]->getScore() > $scores[1]->getScore())
            {
                $HomeTeamPoints = 3;
                $AwayTeamPoints = 0;
            } elseif ($scores[0]->getScore() < $scores[1]->getScore())
            {
                $HomeTeamPoints = 0;
                $AwayTeamPoints = 3;
            } elseif (($scores[0]->getScore() === $scores[1]->getScore()) && $scores[1]->getScore() !== null)
            {
                $HomeTeamPoints = 1;
                $AwayTeamPoints = 1;
            } else
            {
                $HomeTeamPoints = 0;
                $AwayTeamPoints = 0;
            }
            $homeTeam = [
                'TeamName' => $scores[0]->getCompetitorId()->getName(),
                'Points' => $HomeTeamPoints,
                'ScoredGoals' => $scores[0]->getScore(),
                'GoalsConceded' => $scores[1]->getScore()
            ];
            $awayTeam = [
                'TeamName' => $scores[1]->getCompetitorId()->getName(),
                'Points' => $AwayTeamPoints,
                'ScoredGoals' => $scores[1]->getScore(),
                'GoalsConceded' => $scores[0]->getScore()
            ];
            
            $rankingTable = $this->updateRanking($rankingTable, $homeTeam, $awayTeam);
        }
        return $rankingTable;
    }
    
    private function updateRanking(array $rankingTable, array $homeTeam, array $awayTeam)
    {
        for ($i = 0; $i < sizeof($rankingTable); $i ++)
        {
            if ($rankingTable[$i]['TeamName'] == $homeTeam['TeamName'])
            {
                $rankingTable[$i]['Points'] += $homeTeam['Points'];
                $rankingTable[$i]['ScoredGoals'] += $homeTeam['ScoredGoals'];
                $rankingTable[$i]['GoalsConceded'] += $homeTeam['GoalsConceded'];
            }
            if ($rankingTable[$i]['TeamName'] == $awayTeam['TeamName'])
            {
                $rankingTable[$i]['Points'] += $awayTeam['Points'];
                $rankingTable[$i]['ScoredGoals'] += $awayTeam['ScoredGoals'];
                $rankingTable[$i]['GoalsConceded'] += $awayTeam['GoalsConceded'];
            }
        }
        return $rankingTable;
    }
}

