<?php


class Knight extends Configurable
{
    public $name;
    public $attack;
    public $armor;
    public $agility;
    public $endurance;

    public function __toString()
    {
        return strtr('{name} (attack: {attack}, armor: {armor}, agility: {agility}, endurance: {endurance})', [
            '{name}' => $this->name,
            '{attack}' => $this->attack,
            '{armor}' => $this->armor,
            '{agility}' => $this->agility,
            '{endurance}' => $this->endurance,
        ]);
    }
}
