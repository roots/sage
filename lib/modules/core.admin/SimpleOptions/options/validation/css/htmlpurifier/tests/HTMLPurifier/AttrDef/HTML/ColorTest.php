<?php

class HTMLPurifier_AttrDef_HTML_ColorTest extends HTMLPurifier_AttrDefHarness
{

    function test() {
        $this->def = new HTMLPurifier_AttrDef_HTML_Color();
        $this->assertDef('', false);
        $this->assertDef('foo', false);
        $this->assertDef('43', false);
        $this->assertDef('red', '#FF0000');
        $this->assertDef('RED', '#FF0000');
        $this->assertDef('#FF0000');
        $this->assertDef('#453443');
        $this->assertDef('453443', '#453443');
        $this->assertDef('#345', '#334455');
        $this->assertDef('120', '#112200');
    }
}

// vim: et sw=4 sts=4
