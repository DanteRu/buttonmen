<?php
/**
 * BMAttackDefault: Code allowing automatic choice of a default attack
 *
 * @author james
 */

/**
 * This class contains the code required to enable a default attack, when
 * there is no ambiguity about the type of attack that is desired
 */
class BMAttackDefault extends BMAttack {
    public $type = 'Default';
    protected $resolvedType = '';

    public function find_attack($game) {
        return $this->validate_attack(
            $game,
            $this->attackerAttackDieArray,
            $game->defenderAttackDieArray
        );
    }

    public function validate_attack($game, array $attackers, array $defenders) {
        $this->validationMessage = '';
        $this->resolvedType = '';

        $possibleAttackTypeArray = $game->valid_attack_types();
        $validAttackTypeArray = array();

        foreach ($possibleAttackTypeArray as $attackType) {
            $attack = BMAttack::get_instance($attackType);
            if (!empty($this->validDice)) {
                foreach ($this->validDice as &$die) {
                    $attack->add_die($die);
                }
            }
            if ($attack->validate_attack($game, $attackers, $defenders)) {
                $validAttackTypeArray[] = $attackType;
            }
        }

        switch (count($validAttackTypeArray)) {
            case 1:
                $this->resolvedType = $validAttackTypeArray[0];
                return TRUE;
            case 0:
                $this->validationMessage = 'There is no valid attack corresponding to the dice selected.';
                return FALSE;
            default:
                $this->validationMessage = 'Default attack is ambiguous.';
                return FALSE;
        }
    }

    public function type_for_log() {
        return $this->resolvedType;
    }

    protected function are_skills_compatible(array $attArray, array $defArray) {
        return TRUE;
    }
}
