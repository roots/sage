<?php

class HTMLPurifier_AttrTransform_BoolToCSSTest extends HTMLPurifier_AttrTransformHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_BoolToCSS('foo', 'bar:3in;');
    }

    function testEmptyInput() {
        $this->assertResult( array() );
    }

    function testBasicTransform() {
        $this->assertResult(
            array('foo' => 'foo'),
            array('style' => 'bar:3in;')
        );
    }

    function testIgnoreValueOfBooleanAttribute() {
        $this->assertResult(
            array('foo' => 'no'),
            array('style' => 'bar:3in;')
        );
    }

    function testPrependCSS() {
        $this->assertResult(
            array('foo' => 'foo', 'style' => 'background-color:#F00;'),
            array('style' => 'bar:3in;background-color:#F00;')
        );
    }

}

// vim: et sw=4 sts=4
