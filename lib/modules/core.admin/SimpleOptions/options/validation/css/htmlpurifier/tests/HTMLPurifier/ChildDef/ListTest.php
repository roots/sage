<?php

class HTMLPurifier_ChildDef_ListTest extends HTMLPurifier_ChildDefHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_ChildDef_List();
    }

    function testEmptyInput() {
        $this->assertResult('', false);
    }

    function testSingleLi() {
        $this->assertResult('<li />');
    }

    function testSomeLi() {
        $this->assertResult('<li>asdf</li><li />');
    }

    function testIllegal() {
        // XXX actually this never gets triggered in practice
        $this->assertResult('<li /><b />', '<li /><li><b /></li>');
    }

    function testOlAtBeginning() {
        $this->assertResult('<ol />', '<li><ol /></li>');
    }

    function testOlAtBeginningWithOtherJunk() {
        $this->assertResult('<ol /><li />', '<li><ol /></li><li />');
    }

    function testOlInMiddle() {
        $this->assertResult('<li>Foo</li><ol><li>Bar</li></ol>', '<li>Foo<ol><li>Bar</li></ol></li>');
    }

    function testMultipleOl() {
        $this->assertResult('<li /><ol /><ol />', '<li><ol /><ol /></li>');
    }

    function testUlAtBeginning() {
        $this->assertResult('<ul />', '<li><ul /></li>');
    }

}

// vim: et sw=4 sts=4
