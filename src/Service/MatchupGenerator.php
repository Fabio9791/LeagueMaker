<?php
namespace App\Service;

class MatchupGenerator
{

    public function createLeague(array $array): array
    {
        $encounters = [];
        $matchDay = [];
        if (sizeof($array) % 2 != 0) {
            array_push($array, null);
        }
        shuffle($array);
        $loopMax = sizeof($array) - 1;
        for ($j = 0; $j < $loopMax; $j ++) {
            $matchDay = [];
            if ($j % 2 == 0) {
                for ($i = 0; $i < (($loopMax - 1) / 2); $i ++) {
                    array_push($matchDay, [
                        $array[$i],
                        $array[$loopMax - 1 - $i]
                    ]);
                }
            } else {
                for ($i = $loopMax - 1; $i > (($loopMax - 1) / 2); $i --) {
                    array_push($matchDay, [
                        $array[$i],
                        $array[$loopMax - 1 - $i]
                    ]);
                }
            }
            if ($j > 1) {
                if ($j % 2 == 0) {
                    array_push($matchDay, [
                        $array[($loopMax - 1) / 2],
                        $array[$loopMax]
                    ]);
                } else {
                    array_push($matchDay, [
                        $array[$loopMax],
                        $array[($loopMax - 1) / 2]
                    ]);
                }
            } else {
                array_push($matchDay, [
                    $array[$loopMax],
                    $array[($loopMax - 1) / 2]
                ]);
            }
            $array = $this->carousel($array, $loopMax);
            shuffle($matchDay);
            array_push($encounters, $matchDay);
        }
        return $encounters;
    }

    private function carousel(array $array, int $numberOfElements = null): array
    {
        if ($numberOfElements == null) {
            $numberOfElements = sizeof($array);
        }
        $temp = $array[$numberOfElements - 1];
        for ($i = $numberOfElements - 1; $i > 0; $i --) {
            $array[$i] = $array[$i - 1];
        }
        $array[0] = $temp;

        return $array;
    }

    public function homeVisitor(array $array): array
    {
        $matchDay = [];
        $secondPart = $array;
        for ($i = 0; $i < sizeof($array); $i ++) {
            $matchDay = [];
            for ($j = 0; $j < sizeof($array[$i]); $j ++) {
                array_push($matchDay, [
                    $array[$i][$j][1],
                    $array[$i][$j][0]
                ]);
            }
            array_push($secondPart, $matchDay);
        }
        return $secondPart;
    }

    public function createKnockoutRound(array $array): array
    {
        $encoutners = [];
        $matchDay = [];
        if (sizeof($array) % 2 != 0) {
            array_push($array, 666);
        }
        for ($i = 0; $i < sizeof($array); $i += 2) {
            array_push([
                $array[$i],
                $array[$i + 1]
            ]);
        }
        array_push($encoutners, $matchDay);
        return $encoutners;
    }

    public function howManyGroup(array $array): array
    {
        $grpNmb = 1;
        $grps = [];
        while (sizeof($array) / $grpNmb >= 3) {
            array_push($grps, $grpNmb);
            $grpNmb = $grpNmb * 2;
        }
        return $grps;
    }

    public function howManyQualify($numberOfCompetitors): array
    {
        $qualifyNmb = 1;
        $qualified = [];
        while (sizeof($numberOfCompetitors) / qualifyNmb > 1) {
            array_push($qualified, $qualifyNmb);
            $qualifyNmb = $qualifyNmb * 2;
        }
        return $qualified;
    }

    public function createGroups(array $array, int $grpNmb): array
    {
        $encounter = [];
        $grpEncounters = [];
        $oddGrp = sizeof($array) % $grpNmb;
        $evenGrp = $grpNmb - $oddGrp;
        $index = 0;
        for ($i = 0; $i < $oddGrp; $i ++) {
            $grpEncounters = [];
            for ($j = 0; $j < ceil(sizeof($array) / $grpNmb); $j ++) {
                array_push($grpEncounters, $array[$index]);
                $index ++;
            }
            array_push($encounter, $grpEncounters);
        }
        for ($i = 0; $i < $evenGrp; $i ++) {
            $grpEncounters = [];
            for ($j = 0; $j < floor(sizeof($array) / $grpNmb); $j ++) {
                array_push($grpEncounters, $array[$index]);
                $index ++;
            }
            array_push($encounter, $grpEncounters);
        }
        return $encounter;
    }

    public function allElementsUnique(array $array): bool
    {
        for($i=0;$i<sizeof($array)-1;$i++){
            for($j=$i+1;$j<sizeof($array);$j++){
                if($array[$i]===$array[$j]){
                    return false;
                }
            }
        }
        return true;
    }
}

