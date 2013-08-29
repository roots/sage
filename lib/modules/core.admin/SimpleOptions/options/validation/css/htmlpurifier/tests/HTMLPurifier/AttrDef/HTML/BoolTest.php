<?php

class HTMLPurifier_AttrDef_HTML_BoolTest extends HTMLPurifier_AttrDefHarness
{

    function test() {
        $this->def = new HTMLPurifier_AttrDef_HTML_Bool('foo');
        $this->assertDef('foo');
        $this->assertDef('', false);
        $this->assertDef('bar', 'foo');
    }

    function test_make() {
        $factory = new HTMLPurifier_AttrDef_HTML_Bool();
        $def = $factory->make('foo');
        $def2 = new HTMLPurifier_AttrDef_HTML_Bool('foo');
        $this->assertIdentical($def, $def2);
    }

}

// vim: et sw=4 sts=4
