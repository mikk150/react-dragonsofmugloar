<?php

class DefeaterThread extends Collectable
{
    protected $defeatKnights;
    protected $thread;

    public function __construct($thread, $defeatKnights)
    {
        $this->defeatKnights = $defeatKnights;
        $this->thread = $thread;
    }

    public function run()
    {
        try {
            
            $loop = \React\EventLoop\Factory::create();
            $handler = new \WyriHaximus\React\GuzzlePsr7\HttpClientAdapter($loop);

            $client = new \GuzzleHttp\Client([
                'handler' => \GuzzleHttp\HandlerStack::create($handler),
            ]);
            $defeatedKnights = 0;
            for ($knight = 0; $knight < $this->defeatKnights; $knight++) {
                $client->getAsync('http://www.dragonsofmugloar.com/api/game')->then(function ($gameResponse) use ($client, &$defeatedKnights) {
                    $game = new Game(json_decode($gameResponse->getBody()->getContents(), true));

                    $client->getAsync('http://www.dragonsofmugloar.com/weather/api/report/' . $game->gameId)->then(function ($weatherResponse) use ($client, $game, &$defeatedKnights) {
                        $weather = $weatherResponse->getBody()->getContents();
                        $weather=simplexml_load_string($weather);

                        $dragon = new Dragon();
                        $dragon->setKnightSkills($game->getKnight());

                        $dragonSkills = $dragon->attack((string) $weather->code);
                        
                        $client->putAsync('http://www.dragonsofmugloar.com/api/game/'.$game->gameId.'/solution', [
                            'json' => $dragonSkills,
                        ])->then(function ($battleResponse) use ($client, $game, &$defeatedKnights) {
                            echo $this->thread.' knight slaughtering reason: '.$battleResponse->getBody()->getContents() . PHP_EOL;
                            $defeatedKnights++;
                            echo $this->thread.' knights slaughtered: '.$defeatedKnights . PHP_EOL;
                            if ($defeatedKnights == $this->defeatKnights) {
                                $this->setGarbage(); //set current thread as garbage, because all knights in this universe are slaughtered
                            }
                        });
                    });
                });
                #echo 'created game '. $i.PHP_EOL;
            }

        $loop->run();
        } catch (Exception $e) {
            var_dump($e);
            $this->setGarbage(); //set this thread as garbage, because it basically died anyway
        }
    }
}