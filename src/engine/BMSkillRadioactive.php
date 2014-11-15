<?php
/**
 * BMSkillRadioactive: Code specific to the radioactive die skill
 *
 * @author james
 */

/**
 * This class contains code specific to the radioactive die skill
 */
class BMSkillRadioactive extends BMSkill {
    public static $hooked_methods = array('capture', 'be_captured');

    public static function capture(&$args) {
        self::radioactive_split($args);
    }

    public static function be_captured(&$args) {
        self::radioactive_split($args);
    }

    protected static function radioactive_split(&$args) {
        if (!is_array($args)) {
            return;
        }

        if (!array_key_exists('attackers', $args)) {
            return;
        }

        if (!array_key_exists('defenders', $args)) {
            return;
        }

        if (count($args['attackers']) != 1) {
            return;
        }

        if (count($args['defenders']) != 1) {
            return;
        }

        $attacker = &$args['attackers'][0];
        $game = $attacker->ownerObject;
        $activeDieArrayArray = $game->activeDieArrayArray;

        $attacker->remove_skill('Radioactive');
        $attacker->remove_skill('Turbo');
        $attacker->remove_skill('Mood');
        $attacker->remove_skill('Mad');
        $attacker->remove_skill('Jolt');
        $attacker->remove_skill('TimeAndSpace');

        $newAttackerDieArray = $attacker->split();

        array_splice(
            $activeDieArrayArray[$attacker->playerIdx],
            $attacker->activeDieIdx,
            1,
            $newAttackerDieArray
        );

        array_splice(
            $args['attackers'],
            0,
            1,
            $newAttackerDieArray
        );

        $game->activeDieArrayArray = $activeDieArrayArray;
    }

    protected static function get_description() {
        return 'These dice split, or "decay", when attacking another single die. ' .
               'A radioactive die will then decay into two as-close-to-equal-sized-as-possible ' .
               'dice that add up to its original size. If a radioactive die is attacked by a ' .
               'single die, then the die that attacked it decays in the same way. All dice that ' .
               'decay lose the following skills: Radioactive (%), Turbo Swing(!), Mood Swing(?), ' .
               '[and, not yet implemented: Jolt(J), and Time and Space(^)]. For example, a sX! ' .
               '(Shadow Turbo X Swing) with 15 sides that shadow attacked a radioactive die would ' .
               'decay into a s7 and a s8 sided die losing the turbo skill. A %p(7,13) on a power ' .
               'attack would decay into a p(3,7) and a p(4,6) losing the radioactive skill.';
    }

    protected static function get_interaction_descriptions() {
        return array(
            'Berserk' => 'Dice with both Radioactive and Berserk skills making a berserk attack ' .
                         'targeting a SINGLE die are first replaced with non-berserk dice with half ' .
                         'their previous number of sides, rounding up, and then decay',
            'Morphing' => 'Dice with both Radioactive and Morphing skills first morph into the ' .
                          'size of the captured die, and then decay',
            'Doppelganger' => 'Dice with both Radioactive and Doppelganger first decay, then ' .
                              'each of the "decay products" are replaced by exact copies of the ' .
                              'die they captured',
        );
    }
}
