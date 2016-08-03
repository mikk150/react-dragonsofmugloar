<?php
class Universe extends \Worker {

    public function __construct($loader) {
        $this->loader = $loader;
    }
    
    /* include autoloader for Tasks */
    public function run()   {

        require_once($this->loader);
        require_once(__DIR__.'/../vendor/autoload.php');
        require_once(__DIR__.'/../inc/Configurable.php');
        require_once(__DIR__.'/../inc/Dragon.php');
        require_once(__DIR__.'/../inc/Knight.php');
        require_once(__DIR__.'/../inc/Game.php');
        require_once(__DIR__.'/../inc/DefeaterThread.php');
    }
    
    /* override default inheritance behaviour for the new threaded context */
    public function start() {
        return parent::start(PTHREADS_INHERIT_NONE);
    }
    
    protected $loader;
}