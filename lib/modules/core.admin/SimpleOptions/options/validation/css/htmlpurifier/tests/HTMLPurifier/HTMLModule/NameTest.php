<?php

class HTMLPurifier_HTMLModule_NameTest extends HTMLPurifier_HTMLModuleHarness
{

    function setUp() {
        parent::setUp();
    }

    function testBasicUse() {
        $this->config->set('Attr.EnableID', true);
        $this->assertResult(
            '<a name="foo">bar</a>'
        );
    }

    function testCDATA() {
        $this->config->set('HTML.Attr.Name.UseCDATA', true);
        $this->assertResult(
            '<a name="2">Baz</a><a name="2">Bar</a>'
        );
    }

    function testCDATAWithHeavyTidy() {
        $this->config->set('HTML.Attr.Name.UseCDATA', true);
        $this->config->set('HTML.TidyLevel', 'heavy');
        $this->assertResult('<a name="2">Baz</a>');
    }

}

// vim: et sw=4 sts=4
