<?php

class HTMLPurifier_Strategy_MakeWellFormed_EndInsertInjectorTest extends HTMLPurifier_StrategyHarness
{
    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_MakeWellFormed();
        $this->config->set('AutoFormat.Custom', array(
            new HTMLPurifier_Strategy_MakeWellFormed_EndInsertInjector()
        ));
    }
    function testEmpty() {
        $this->assertResult('');
    }
    function testNormal() {
        $this->assertResult('<i>Foo</i>', '<i>Foo<b>Comment</b></i>');
    }
    function testEndOfDocumentProcessing() {
        $this->assertResult('<i>Foo', '<i>Foo<b>Comment</b></i>');
    }
    function testDoubleEndOfDocumentProcessing() {
        $this->assertResult('<i><i>Foo', '<i><i>Foo<b>Comment</b></i><b>Comment</b></i>');
    }
    function testEndOfNodeProcessing() {
        $this->assertResult('<div><i>Foo</div>asdf', '<div><i>Foo<b>Comment</b></i></div><i>asdf<b>Comment</b></i>');
    }
    function testEmptyToStartEndProcessing() {
        $this->assertResult('<i />', '<i><b>Comment</b></i>');
    }
    function testSpuriousEndTag() {
        $this->assertResult('</i>', '');
    }
    function testLessButStillSpuriousEndTag() {
        $this->assertResult('<div></i></div>', '<div></div>');
    }
}

// vim: et sw=4 sts=4
