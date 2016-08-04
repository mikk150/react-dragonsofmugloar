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
        switch ($weatherCode) {
            case 'HVA':
                $this->scaleThickness = 5;
                $this->clawSharpness = 10;
                $this->wingStrength = 5;
                $this->fireBreath = 0;
                break;
            case 'T E':
                $this->scaleThickness = 5;
                $this->clawSharpness = 5;
                $this->wingStrength = 5;
                $this->fireBreath = 5;
                break;
            case 'SRO':
                // No dragon flies in storm! No knight fights in storm. My neighbor even doesn't send the dog out.
                return null;
            default:

                // get initial skills equal to knights
                $skills = (array) $this;

                $highestSkill = array_search(max($skills), $skills);

                $skills[$highestSkill] += 2;

                // remove highest skill from equation
                $otherSkills = $skills;
                unset($otherSkills[$highestSkill]);

                // remove skills with 0 points aswell
                $otherSkills = array_filter($otherSkills, function ($val) {
                    if ($val == 0) {
                        return false;
                    }
                    return true;
                });

                uasort($otherSkills, function ($a, $b) {
                    if ($a == $b) {
                        return 0;
                    }
                    if ($a > $b) {
                        return 1;
                    }
                    return -1;
                });

                if (count($otherSkills) > 1) {
                    $skills[array_search(array_shift($otherSkills), $skills)]--;
                    $skills[array_search(array_shift($otherSkills), $skills)]--;
                } else {
                    $skills[array_search(array_shift($otherSkills), $skills)]-=2;
                }

                $this->configure($skills);
                break;
        }
        return ['dragon' => (array) $this];
    }
}
