<?php

class HTMLPurifier_AttrDef_EnumTest extends HTMLPurifier_AttrDefHarness
{

    function testCaseInsensitive() {
        $this->def = new HTMLPurifier_AttrDef_Enum(array('one', 'two'));
        $this->assertDef('one');
        $this->assertDef('ONE', 'one');
    }

    function testCaseSensitive() {
        $this->def = new HTMLPurifier_AttrDef_Enum(array('one', 'two'), true);
        $this->assertDef('one');
        $this->assertDef('ONE', false);
    }

    function testFixing() {
        $this->def = new HTMLPurifier_AttrDef_Enum(array('one'));
        $this->assertDef(' one ', 'one');
    }

    function test_make() {
        $factory = new HTMLPurifier_AttrDef_Enum();

        $def = $factory->make('foo,bar');
        $def2 = new HTMLPurifier_AttrDef_Enum(array('foo', 'bar'));
        $this->assertIdentical($def, $def2);

        $def = $factory->make('s:foo,BAR');
        $def2 = new HTMLPurifier_AttrDef_Enum(array('foo', 'BAR'), true);
        $this->assertIdentical($def, $def2);
    }

}

// vim: et sw=4 sts=4
