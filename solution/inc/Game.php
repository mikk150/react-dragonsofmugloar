<?php


class Game extends Configurable
{
    public $gameId;
    public $knight;

    private $_knight;

    public function getKnight()
    {
        if (!$this->_knight) {
            $this->_knight = new Knight($this->knight);
        }
        return $this->_knight;
    }
}
