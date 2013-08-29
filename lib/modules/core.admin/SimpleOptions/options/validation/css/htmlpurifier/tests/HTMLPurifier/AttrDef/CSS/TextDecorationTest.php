<?php

class HTMLPurifier_AttrDef_CSS_TextDecorationTest extends HTMLPurifier_AttrDefHarness
{

    function testCaseInsensitive() {

        $this->def = new HTMLPurifier_AttrDef_CSS_TextDecoration();

        $this->assertDef('none');
        $this->assertDef('none underline', 'underline');

        $this->assertDef('underline');
        $this->assertDef('overline');
        $this->assertDef('line-through overline underline');
        $this->assertDef('overline line-through');
        $this->assertDef('UNDERLINE', 'underline');
        $this->assertDef('  underline line-through ', 'underline line-through');

        $this->assertDef('foobar underline', 'underline');
        $this->assertDef('blink', false);

    }

}

// vim: et sw=4 sts=4
