<?php
require_once(__DIR__.'/inc/DefeaterThread.php');
require_once(__DIR__.'/inc/Universe.php');

// Spawn 4 workers for universes
$p = new Pool(4, Universe::class, ["vendor/autoload.php"]);

$tasks = array(
    new DefeaterThread(1, 150),
    new DefeaterThread(2, 150),
    new DefeaterThread(3, 150),
    new DefeaterThread(4, 150),
    new DefeaterThread(5, 150),
    new DefeaterThread(6, 150),
    new DefeaterThread(7, 150),
    new DefeaterThread(8, 150),
    new DefeaterThread(9, 150),
    new DefeaterThread(10, 150),
    new DefeaterThread(11, 150),
    new DefeaterThread(12, 150),
    new DefeaterThread(13, 150),
    new DefeaterThread(14, 150),
    new DefeaterThread(15, 150),
);
// Add tasks to pool queue
foreach ($tasks as $task) {
    $p->submit($task);
}

// shutdown will wait for current queue to be completed
$p->shutdown();
// garbage collection check / read results
$p->collect(function($checkingTask){
    return $checkingTask->isGarbage(); //collect all universes where there are no knights any more
});
