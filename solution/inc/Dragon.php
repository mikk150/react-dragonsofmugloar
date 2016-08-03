<?php


class Dragon extends Configurable
{
    public $scaleThickness;
    public $clawSharpness;
    public $wingStrength;
    public $fireBreath;

    public function setKnightSkills(Knight $knight)
    {
        $this->scaleThickness = $knight->attack;
        $this->clawSharpness = $knight->armor;
        $this->wingStrength = $knight->agility;
        $this->fireBreath = $knight->endurance;
    }

    public function attack($weatherCode)
    {
        return [];
    }
}
