<?php

class HTMLPurifier_Strategy_CoreTest extends HTMLPurifier_StrategyHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_Core();
    }

    function testBlankInput() {
        $this->assertResult('');
    }

    function testMakeWellFormed() {
        $this->assertResult(
            '<b>Make well formed.',
            '<b>Make well formed.</b>'
        );
    }

    function testFixNesting() {
        $this->assertResult(
            '<b><div>Fix nesting.</div></b>',
            '<b></b><div><b>Fix nesting.</b></div><b></b>'
        );
    }

    function testRemoveForeignElements() {
        $this->assertResult(
            '<asdf>Foreign element removal.</asdf>',
            'Foreign element removal.'
        );
    }

    function testFirstThree() {
        $this->assertResult(
            '<foo><b><div>All three.</div></b>',
            '<b></b><div><b>All three.</b></div><b></b>'
        );
    }

}

// vim: et sw=4 sts=4
