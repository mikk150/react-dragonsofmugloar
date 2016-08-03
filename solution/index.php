<?php

require 'vendor/autoload.php';
require 'inc/Configurable.php';
require 'inc/Dragon.php';
require 'inc/Knight.php';
require 'inc/Game.php';

$loop = \React\EventLoop\Factory::create();
$handler = new \WyriHaximus\React\GuzzlePsr7\HttpClientAdapter($loop);

$client = new \GuzzleHttp\Client([
    'handler' => \GuzzleHttp\HandlerStack::create($handler),
]);

for ($i = 0; $i < 100; $i++) {
    $client->getAsync('http://www.dragonsofmugloar.com/api/game')->then(function ($gameResponse) use ($client) {
        $game = new Game(json_decode($gameResponse->getBody()->getContents(), true));

        $client->getAsync('http://www.dragonsofmugloar.com/weather/api/report/' . $game->gameId)->then(function ($weatherResponse) use ($client, $game) {
            $weather = $weatherResponse->getBody()->getContents();
            $weather=simplexml_load_string($weather);

            $dragon = new Dragon();
            $dragon->setKnightSkills($game->getKnight());

            $client->putAsync('http://www.dragonsofmugloar.com/api/game/'.$game->gameId.'/solution', [
                'json' => ['dragon' => $dragon->attack((string) $weather->code)],
            ])->then(function ($battleResponse) use ($client, $game) {
                #echo $battleResponse->getBody()->getContents() . PHP_EOL;
            });
        });
    });
    #echo 'created game '. $i.PHP_EOL;
}

function getSolution ($knightObj, $weatherCode) {
    $solution = [
        "scaleThickness" =>  $knightObj->knight->attack,
        "clawSharpness" =>  $knightObj->knight->armor,
        "wingStrength" =>  $knightObj->knight->agility,
        "fireBreath" =>  $knightObj->knight->endurance,
    ];

    switch ($weatherCode) {
        case 'HVA':
            $solution = [
                'scaleThickness' => 5,
                'clawSharpness' => 10,
                'wingStrength' => 5,
                'fireBreath' => 0,
            ];
            break;
        case 'T E':
            $solution = [
                'scaleThickness' => 5,
                'clawSharpness' => 5,
                'wingStrength' => 5,
                'fireBreath' => 5,
            ];
            break;
        default:
            $knightArray = (array) $knightObj->knight;
            unset($knightArray['name']);

            $highestSkill = array_search(max($knightArray), $knightArray);
            #$lowestSkill = array_search(min($knightArray), $knightArray);

            $solution[mapKnightSkillToDragon($highestSkill)] = max($knightArray) + 2;

            $remainingSkills = $solution;
            unset($remainingSkills[mapKnightSkillToDragon($highestSkill)]);

            uasort($knightArray, function($a, $b) {
                if ($a == $b) {
                    return 0;
                }
                if ($a > $b) {
                    return 1;
                }
                return -1;
            });
var_dump($knightArray);

    }

    return $solution;
}

$loop->run();

