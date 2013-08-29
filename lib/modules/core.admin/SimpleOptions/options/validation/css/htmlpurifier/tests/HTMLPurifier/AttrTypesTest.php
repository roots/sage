<?php

class HTMLPurifier_AttrTypesTest extends HTMLPurifier_Harness
{

    function test_get() {
        $types = new HTMLPurifier_AttrTypes();

        $this->assertIdentical(
            $types->get('CDATA'),
            new HTMLPurifier_AttrDef_Text()
        );

        $this->expectError('Cannot retrieve undefined attribute type foobar');
        $types->get('foobar');

        $this->assertIdentical(
            $types->get('Enum#foo,bar'),
            new HTMLPurifier_AttrDef_Enum(array('foo', 'bar'))
        );

    }

}

// vim: et sw=4 sts=4
