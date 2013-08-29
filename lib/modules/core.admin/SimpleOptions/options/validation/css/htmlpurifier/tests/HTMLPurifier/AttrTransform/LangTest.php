<?php

class HTMLPurifier_AttrTransform_LangTest
    extends HTMLPurifier_AttrTransformHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_Lang();
    }

    function testEmptyInput() {
        $this->assertResult(array());
    }

    function testCopyLangToXMLLang() {
        $this->assertResult(
            array('lang' => 'en'),
            array('lang' => 'en', 'xml:lang' => 'en')
        );
    }

    function testPreserveAttributes() {
        $this->assertResult(
            array('src' => 'vert.png', 'lang' => 'fr'),
            array('src' => 'vert.png', 'lang' => 'fr', 'xml:lang' => 'fr')
        );
    }

    function testCopyXMLLangToLang() {
        $this->assertResult(
            array('xml:lang' => 'en'),
            array('xml:lang' => 'en', 'lang' => 'en')
        );
    }

    function testXMLLangOverridesLang() {
        $this->assertResult(
            array('lang' => 'fr', 'xml:lang' => 'de'),
            array('lang' => 'de', 'xml:lang' => 'de')
        );
    }

}

// vim: et sw=4 sts=4
