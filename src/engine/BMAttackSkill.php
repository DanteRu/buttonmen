<?php
/**
 * BMAttackSkill: Code specific to skill attacks
 *
 * @author Julian
 */

/**
 * This class contains code specific to skill attacks
 */
class BMAttackSkill extends BMAttack {
    public $type = "Skill";

    // Especially once you take stinger and constant into account,
    // searching the full attack space is slow and complex
    //
    // Building a hit table once trades some increased setup cost for
    // a much reduced search cost
    //
    // Fire still makes life more complex than it might be.
    //
    // "Premature optimization is the root of all evil." -- Knuth
    protected $hitTable = NULL;

    protected function generate_hit_table($includeOptional = TRUE) {
        if ($includeOptional) {
            $validDice = $this->validDice;
        } else {
            $validDice = array();

            foreach ($this->validDice as &$die) {
                if ($die->has_skill('Warrior')) {
                    continue;
                }
                $validDice[] = $die;
            }

        }

        $this->hitTable = new BMUtilityHitTable($validDice);
    }

    /**
     * Determine if there is at least one valid attack of this type from
     * the set of all possible attackers and defenders.
     *
     * If $includeOptional is FALSE, then optional attacks are excluded.
     * These include skill attacks involving warrior dice.
     *
     * @param BMGame $game
     * @param boolean $includeOptional
     * @return boolean
     */
    public function find_attack($game, $includeOptional = TRUE) {
        $targets = $game->defenderAllDieArray;

        if (count($targets) < 1) {
            return FALSE;
        }

        $this->generate_hit_table($includeOptional);
        $hits = $this->hitTable->list_hits();

        foreach ($targets as $t) {
            // Sort the multidimensional array by distance from target
            $this->sort_distance($hits, $t->value);

            foreach ($hits as $hit) {
                $def = array($t);
                $combos = $this->hitTable->find_hit($hit);
                foreach ($combos as $att) {
                    if ($this->validate_attack($game, $att, $def)) {
                        return TRUE;
                    }
                }
            }
        }

        return FALSE;
    }

    protected function sort_distance(array &$values, $targetValue) {
        // centre values around $targetValue
        array_walk(
            $values,
            function (&$val, $key, $centre) {
                $val = $val - $centre;
            },
            $targetValue
        );

        // sort according to absolute distance from centre
        usort($values, array($this, 'absolute_distance_cmp'));

        // remove centration
        array_walk(
            $values,
            function (&$val, $key, $centre) {
                $val = $val + $centre;
            },
            $targetValue
        );
    }

    protected function absolute_distance_cmp($aVal, $bVal) {
        return abs($aVal) > abs($bVal);
    }

    public function validate_attack($game, array $attackers, array $defenders, $helpValue = NULL) {
        $this->validationMessage = '';

        if (count($attackers) < 1) {
            $this->validationMessage = 'There must be at least one attacking die for a skill attack.';
            return FALSE;
        }

        if (count($defenders) != 1) {
            $this->validationMessage = 'There must be exactly one target die for a skill attack.';
            return FALSE;
        }

        if ($this->has_dizzy_attackers($attackers)) {
            // validation message set within $this->has_dizzy_attackers()
            return FALSE;
        }

        if (!$this->are_skills_compatible($attackers, $defenders)) {
            // validation message set within $this->are_skills_compatible()
            return FALSE;
        }

        $dval = $defenders[0]->defense_value($this->type);

        if (!($this->hitTable instanceof BMUtilityHitTable)) {
            $this->generate_hit_table();
        }

        if ($this->is_direct_attack_valid($attackers, $dval)) {
            return TRUE;
        }

        return $this->is_assisted_attack_valid($game, $attackers, $defenders, $dval, $helpValue);
    }

    // array_intersect tries to convert to strings, so we use array_uintersect,
    // which needs a comparison function
    protected static function cmp($var1, $var2) {
        if ($var1===$var2) {
            return 0;
        }
        if ($var1 > $var2) {
            return 1;
        }
        return -1;
    }

    protected function is_direct_attack_valid($attackers, $dval) {
        $combos = $this->hitTable->find_hit($dval);
        if ($combos) {
            foreach ($combos as $c) {
                if (count($c) == count($attackers) &&
                    count(array_uintersect($c, $attackers, 'BMAttackSkill::cmp')) ==
                    count($c)) {
                    return TRUE;
                }
            }
        }
    }

    protected function is_assisted_attack_valid($game, $attackers, $defenders, $dval, $helpValue) {
        if (is_null($helpValue)) {
            $bounds = $this->help_bounds(
                $this->collect_helpers($game, $attackers, $defenders),
                $this->collect_firing_maxima($attackers)
            );
        } else {
            $bounds = array($helpValue, $helpValue);
        }

        if ($bounds[0] == 0 && $bounds[1] == 0) {
            $this->validationMessage = 'Attacking die values do not sum up to target die value.';
            return FALSE;
        }
        for ($i = $bounds[0]; $i <= $bounds[1]; $i++) {
            // james: This logic assumes that firing effectively reduces the defence value.
            //        This assumption fails in the case that part of the skill sum comes
            //        from a konstant die that is being subtracted from the total.
            $combos = $this->hitTable->find_hit($dval - $i);
            if ($combos) {
                foreach ($combos as $c) {
                    if (count($c) == count($attackers) &&
                        count(array_uintersect($c, $attackers, 'BMAttackSkill::cmp')) ==
                        count($c)) {
                        return TRUE;
                    }
                }
            }
        }
        $this->validationMessage = 'Attacking die values do not sum up to target die value.';
        return FALSE;
    }

    protected function are_skills_compatible(array $attArray, array $defArray) {
        if (0 == count($attArray)) {
            throw new InvalidArgumentException('attArray must have at least one element.');
        }

        if (1 != count($defArray)) {
            throw new InvalidArgumentException('defArray must have one element.');
        }

        $def = $defArray[0];

        if (1 == count($attArray)) {
            if ($attArray[0]->has_skill('Stealth')) {
                $this->validationMessage = 'Skill attacks involving a single attacking stealth die are invalid.';
                return FALSE;
            }

            if ($attArray[0]->has_skill('Konstant')) {
                $this->validationMessage = 'Skill attacks involving a single attacking konstant die are invalid.';
                return FALSE;
            }
        }

        if (1 == count($attArray) &&
            $def->has_skill('Stealth')) {
            $this->validationMessage = 'Multiple attacking dice are required to skill attack a stealth die.';
            return FALSE;
        }

        if ($def->has_skill('Warrior')) {
            $this->validationMessage = 'Warrior dice cannot be attacked';
            return FALSE;
        }

        foreach ($attArray as $att) {
            if ($att->has_skill('Berserk')) {
                $this->validationMessage = 'Berserk dice cannot perform skill attacks.';
                return FALSE;
            }
        }

        if ($this->is_invalid_warrior_attack($attArray)) {
            // validation message set within $this->is_invalid_warrior_attack()
            return FALSE;
        }

        return TRUE;
    }

    protected function is_invalid_warrior_attack(array $attArray) {
        $nWarrior = 0;
        foreach ($attArray as $attacker) {
            if ($attacker->has_skill('Warrior')) {
                $nWarrior++;
            }
        }

        // check if there are any warrior dice present
        if (0 == $nWarrior) {
            return FALSE;
        }

        // check that there is only one warrior die present
        if ($nWarrior != 1) {
            $this->validationMessage = 'Only one Warrior die can be brought into play at a time';
            return TRUE;
        }

        // check that there is at least one non-warrior die taking part
        if (1 == count($attArray)) {
            $this->validationMessage = 'There must be at least one non-Warrior attacker';
            return TRUE;
        }
    }
}
