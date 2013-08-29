<?php

class HTMLPurifier_Strategy_ValidateAttributes_IDTest extends HTMLPurifier_StrategyHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_ValidateAttributes();
        $this->config->set('Attr.EnableID', true);
    }


    function testPreserveIDWhenEnabled() {
        $this->assertResult('<div id="valid">Preserve the ID.</div>');
    }

    function testRemoveInvalidID() {
        $this->assertResult(
            '<div id="0invalid">Kill the ID.</div>',
            '<div>Kill the ID.</div>'
        );
    }

    function testRemoveDuplicateID() {
        $this->assertResult(
            '<div id="valid">Valid</div><div id="valid">Invalid</div>',
            '<div id="valid">Valid</div><div>Invalid</div>'
        );
    }

    function testAttributeKeyCaseInsensitivity() {
        $this->assertResult(
            '<div ID="valid">Convert ID to lowercase.</div>',
            '<div id="valid">Convert ID to lowercase.</div>'
        );
    }

    function testTrimWhitespace() {
        $this->assertResult(
            '<div id=" valid ">Trim whitespace.</div>',
            '<div id="valid">Trim whitespace.</div>'
        );
    }

    function testIDBlacklist() {
        $this->config->set('Attr.IDBlacklist', array('invalid'));
        $this->assertResult(
            '<div id="invalid">Invalid</div>',
            '<div>Invalid</div>'
        );
    }

    function testNameConvertedToID() {
        $this->config->set('HTML.TidyLevel', 'heavy');
        $this->assertResult(
            '<a name="foobar" />',
            '<a id="foobar" />'
        );
    }

}

// vim: et sw=4 sts=4
