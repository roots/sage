<?php

class HTMLPurifier_AttrDef_CSS_LengthTest extends HTMLPurifier_AttrDefHarness
{

    function test() {

        $this->def = new HTMLPurifier_AttrDef_CSS_Length();

        $this->assertDef('0');
        $this->assertDef('0px');
        $this->assertDef('4.5px');
        $this->assertDef('-4.5px');
        $this->assertDef('3ex');
        $this->assertDef('3em');
        $this->assertDef('3in');
        $this->assertDef('3cm');
        $this->assertDef('3mm');
        $this->assertDef('3pt');
        $this->assertDef('3pc');

        $this->assertDef('3PX', '3px');

        $this->assertDef('3', false);
        $this->assertDef('3miles', false);

    }

    function testNonNegative() {

        $this->def = new HTMLPurifier_AttrDef_CSS_Length('0');

        $this->assertDef('3cm');
        $this->assertDef('-3mm', false);

    }

    function testBounding() {
        $this->def = new HTMLPurifier_AttrDef_CSS_Length('-1in', '1in');
        $this->assertDef('1cm');
        $this->assertDef('-1cm');
        $this->assertDef('0');
        $this->assertDef('1em', false);
    }

}

// vim: et sw=4 sts=4
