<?php

class BMSkillSlow extends BMSkill {
    public static $hooked_methods = array('initiative_value');

    public static function initiative_value(&$args) {
        if (!is_array($args)) {
            return;
        }

        // stinger dice don't contribute to initiative
        $args['initiativeValue'] = 0;
    }
}
