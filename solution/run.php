<?php
require_once(__DIR__.'/inc/DefeaterThread.php');
require_once(__DIR__.'/inc/Universe.php');

// Spawn 4 workers for universes
$p = new Pool(4, Universe::class, ["vendor/autoload.php"]);

$tasks = array(
    new DefeaterThread(1, 1000),
    new DefeaterThread(2, 1000),
    new DefeaterThread(3, 1000),
    new DefeaterThread(4, 1000),
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
