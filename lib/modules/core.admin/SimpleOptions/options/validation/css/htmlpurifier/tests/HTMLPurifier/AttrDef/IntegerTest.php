<?php

class HTMLPurifier_AttrDef_IntegerTest extends HTMLPurifier_AttrDefHarness
{

    function test() {

        $this->def = new HTMLPurifier_AttrDef_Integer();

        $this->assertDef('0');
        $this->assertDef('1');
        $this->assertDef('-1');
        $this->assertDef('-10');
        $this->assertDef('14');
        $this->assertDef('+24', '24');
        $this->assertDef(' 14 ', '14');
        $this->assertDef('-0', '0');

        $this->assertDef('-1.4', false);
        $this->assertDef('3.4', false);
        $this->assertDef('asdf', false); // must not return zero
        $this->assertDef('2in', false); // must not return zero

    }

    function assertRange($negative, $zero, $positive) {
        $this->assertDef('-100', $negative);
        $this->assertDef('-1', $negative);
        $this->assertDef('0', $zero);
        $this->assertDef('1', $positive);
        $this->assertDef('42', $positive);
    }

    function testRange() {

        $this->def = new HTMLPurifier_AttrDef_Integer(false);
        $this->assertRange(false, true, true); // non-negative

        $this->def = new HTMLPurifier_AttrDef_Integer(false, false);
        $this->assertRange(false, false, true); // positive


        // fringe cases

        $this->def = new HTMLPurifier_AttrDef_Integer(false, false, false);
        $this->assertRange(false, false, false); // allow none

        $this->def = new HTMLPurifier_AttrDef_Integer(true, false, false);
        $this->assertRange(true, false, false); // negative

        $this->def = new HTMLPurifier_AttrDef_Integer(false, true, false);
        $this->assertRange(false, true, false); // zero

        $this->def = new HTMLPurifier_AttrDef_Integer(true, true, false);
        $this->assertRange(true, true, false); // non-positive

    }

}

// vim: et sw=4 sts=4
