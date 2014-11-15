<?php
/**
 * BMSkillDoppelganger: Code specific to the doppelganger die skill
 *
 * @author james
 */

/**
 * This class contains code specific to the doppelganger die skill
 */
class BMSkillDoppelganger extends BMSkillMorphing {
    public static $hooked_methods = array('capture');

    public static function capture(&$args) {
        if (!self::are_dice_in_attack_valid($args)) {
            return;
        }

        if (!('Power' == $args['type'])) {
            return;
        }

        // replace the attacking die here in place to allow radioactive to trigger correctly
        $attacker = $args['caller'];
        $game = $attacker->ownerObject;
        $activeDieArrayArray = $game->activeDieArrayArray;

        $newAttackDie = self::create_morphing_clone_target($args['caller'], $args['defenders'][0]);

        $activeDieArrayArray[$attacker->playerIdx][$attacker->activeDieIdx] = $newAttackDie;
        $args['attackers'][0] = $newAttackDie;
        $game->activeDieArrayArray = $activeDieArrayArray;
    }

    protected static function get_description() {
        return 'When a Doppelganger Die performs a Power Attack on ' .
               'another die, the Doppelganger Die becomes an exact copy of ' .
               'the die it captured. The newly copied die is then rerolled, ' .
               'and has all the abilities of the captured die. For instance, ' .
               'if a Doppelganger Die copies a Turbo Swing Die, then it may ' .
               'change its size as per the rules of Turbo Swing Dice. Usually ' .
               'a Doppelganger Die will lose its Doppelganger ability when ' .
               'it copies another die, unless that die is itself a Doppelganger ' .
               'Die.';
    }

    protected static function get_interaction_descriptions() {
        return array(
            'Radioactive' => 'Dice with both Radioactive and Doppelganger first decay, then ' .
                             'each of the "decay products" are replaced by exact copies of the ' .
                             'die they captured',
        );
    }

    public static function prevents_win_determination() {
        return TRUE;
    }
}
