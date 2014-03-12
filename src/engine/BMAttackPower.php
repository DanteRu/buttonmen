<?php

class BMAttackPower extends BMAttack {
    public $type = 'Power';

    public function find_attack($game) {
        $targets = $game->defenderAllDieArray;

        return $this->search_onevone($game, $this->validDice, $targets);
    }

    public function validate_attack($game, array $attackers, array $defenders) {
        if (1 != count($attackers) ||
            1 != count($defenders) ||
            $this->has_dizzy_attackers($attackers)) {
            return FALSE;
        }

        if (!$this->are_skills_compatible($attackers, $defenders)) {
            return FALSE;
        }

        $helpers = $this->collect_helpers($game, $attackers, $defenders);

        $bounds = $this->help_bounds($helpers);

        $att = $attackers[0];
        $def = $defenders[0];

        foreach ($att->attack_values($this->type) as $aVal) {
            $isValLargeEnough = $aVal + $bounds[1] >= $def->defense_value($this->type);
            $isValidAttacker = $att->is_valid_attacker($this->type, $attackers);
            $isValidTarget = $def->is_valid_target($this->type, $defenders);

            $isValidAttack = $isValLargeEnough && $isValidAttacker && $isValidTarget;

            if ($isValidAttack) {
                return TRUE;
            }
        }

        return FALSE;
    }

    protected function are_skills_compatible(array $attArray, array $defArray) {
        if (1 != count($attArray)) {
            throw new InvalidArgumentException('attArray must have one element.');
        }

        if (1 != count($defArray)) {
            throw new InvalidArgumentException('defArray must have one element.');
        }

        $att = $attArray[0];
        $def = $defArray[0];

        $returnVal = TRUE;

        if ($att->has_skill('Shadow') ||
            $att->has_skill('Konstant') ||
            $att->has_skill('Stealth') ||
            ($att->has_skill('Queer') && (1 == $att->value % 2))
        ) {
            $returnVal = FALSE;
        }

        if ($def->has_Skill('Stealth')) {
            $returnVal = FALSE;
        }

        return $returnVal;
    }
}
