<?php

require 'vendor/autoload.php';
require 'inc/Configurable.php';
require 'inc/Dragon.php';
require 'inc/Knight.php';
require 'inc/Game.php';

class workerThread extends Thread {
    public function __construct($i){
        $this->i=$i;
    }

    public function run(){
        
    }
}

for($i=0;$i<50;$i++){
    $workers[$i] = new workerThread($i);
    $workers[$i]->start();
}
