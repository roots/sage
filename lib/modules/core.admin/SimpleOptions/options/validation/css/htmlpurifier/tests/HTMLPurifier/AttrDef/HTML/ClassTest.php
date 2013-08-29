<?php

class HTMLPurifier_AttrDef_HTML_ClassTest extends HTMLPurifier_AttrDef_HTML_NmtokensTest
{
    function setUp() {
        parent::setUp();
        $this->def = new HTMLPurifier_AttrDef_HTML_Class();
    }
    function testAllowedClasses() {
        $this->config->set('Attr.AllowedClasses', array('foo'));
        $this->assertDef('foo');
        $this->assertDef('bar', false);
        $this->assertDef('foo bar', 'foo');
    }
    function testForbiddenClasses() {
        $this->config->set('Attr.ForbiddenClasses', array('bar'));
        $this->assertDef('foo');
        $this->assertDef('bar', false);
        $this->assertDef('foo bar', 'foo');
    }
    function testDefault() {
        $this->assertDef('valid');
        $this->assertDef('a0-_');
        $this->assertDef('-valid');
        $this->assertDef('_valid');
        $this->assertDef('double valid');

        $this->assertDef('0stillvalid');
        $this->assertDef('-0');

        // test conditional replacement
        $this->assertDef('validassoc 0valid', 'validassoc 0valid');

        // test whitespace leniency
        $this->assertDef(" double\nvalid\r", 'double valid');

        // test case sensitivity
        $this->assertDef('VALID');

        // test duplicate removal
        $this->assertDef('valid valid', 'valid');
    }
    function testXHTML11Behavior() {
        $this->config->set('HTML.Doctype', 'XHTML 1.1');
        $this->assertDef('0invalid', false);
        $this->assertDef('valid valid', 'valid');
    }
}
