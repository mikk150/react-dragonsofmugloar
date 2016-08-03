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

for ($i = 0; $i < 1000; $i++) {
    $client->getAsync('http://www.dragonsofmugloar.com/api/game')->then(function ($gameResponse) use ($client) {
        $game = new Game(json_decode($gameResponse->getBody()->getContents(), true));

        $client->getAsync('http://www.dragonsofmugloar.com/weather/api/report/' . $game->gameId)->then(function ($weatherResponse) use ($client, $game) {
            $weather = $weatherResponse->getBody()->getContents();
            $weather=simplexml_load_string($weather);

            $dragon = new Dragon();
            $dragon->setKnightSkills($game->getKnight());

            $dragonSkills = $dragon->attack((string) $weather->code);

            if ($dragonSkills === false) {
                // No dragon flies in storm! No knight fights in storm. My neighbor even doesn't send the dog out.
                return;
            }

            $client->putAsync('http://www.dragonsofmugloar.com/api/game/'.$game->gameId.'/solution', [
                'json' => ['dragon' => $dragonSkills],
            ])->then(function ($battleResponse) use ($client, $game) {
                echo $battleResponse->getBody()->getContents() . PHP_EOL;
            });
        });
    });
    #echo 'created game '. $i.PHP_EOL;
}


$loop->run();

