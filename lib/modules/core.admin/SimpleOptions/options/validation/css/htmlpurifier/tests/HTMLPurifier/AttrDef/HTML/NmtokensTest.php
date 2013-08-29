<?php

class HTMLPurifier_AttrDef_HTML_NmtokensTest extends HTMLPurifier_AttrDefHarness
{

    function setUp() {
        parent::setUp();
        $this->def = new HTMLPurifier_AttrDef_HTML_Nmtokens();
    }

    function testDefault() {

        $this->assertDef('valid');
        $this->assertDef('a0-_');
        $this->assertDef('-valid');
        $this->assertDef('_valid');
        $this->assertDef('double valid');

        $this->assertDef('0invalid', false);
        $this->assertDef('-0', false);

        // test conditional replacement
        $this->assertDef('validassoc 0invalid', 'validassoc');

        // test whitespace leniency
        $this->assertDef(" double\nvalid\r", 'double valid');

        // test case sensitivity
        $this->assertDef('VALID');

    }

}

// vim: et sw=4 sts=4
