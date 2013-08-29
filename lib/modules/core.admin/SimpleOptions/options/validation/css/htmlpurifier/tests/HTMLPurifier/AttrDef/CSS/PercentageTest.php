<?php

class HTMLPurifier_AttrDef_CSS_PercentageTest extends HTMLPurifier_AttrDefHarness
{

    function test() {

        $this->def = new HTMLPurifier_AttrDef_CSS_Percentage();

        $this->assertDef('10%');
        $this->assertDef('1.607%');
        $this->assertDef('-567%');

        $this->assertDef(' 100% ', '100%');

        $this->assertDef('5', false);
        $this->assertDef('asdf', false);
        $this->assertDef('%', false);

    }

}

// vim: et sw=4 sts=4
