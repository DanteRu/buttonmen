<?php

class BMSkillWeakTest extends PHPUnit_Framework_TestCase {
    /**
     * @var BMSkillWeak
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new BMSkillWeak;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testPre_roll()
    {
        $game = new BMGame;
        $game->turnNumberInRound = 1;
        $die = new BMDie;
        $die->init(7);
        $die->ownerObject = $game;
        $args = array('die' => $die);
        $this->object->pre_roll($args);
        $this->assertEquals(7, $die->max);

        $die->value = 2;
        $args = array('die' => $die);
        $this->object->pre_roll($args);
        $this->assertEquals(6, $die->max);
    }
}

