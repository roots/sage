<?php

class HTMLPurifier_AttrTransform_EnumToCSSTest extends HTMLPurifier_AttrTransformHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_EnumToCSS('align', array(
            'left'  => 'text-align:left;',
            'right' => 'text-align:right;'
        ));
    }

    function testEmptyInput() {
        $this->assertResult( array() );
    }

    function testPreserveArraysWithoutInterestingAttributes() {
        $this->assertResult( array('style' => 'font-weight:bold;') );
    }

    function testConvertAlignLeft() {
        $this->assertResult(
            array('align' => 'left'),
            array('style' => 'text-align:left;')
        );
    }

    function testConvertAlignRight() {
        $this->assertResult(
            array('align' => 'right'),
            array('style' => 'text-align:right;')
        );
    }

    function testRemoveInvalidAlign() {
        $this->assertResult(
            array('align' => 'invalid'),
            array()
        );
    }

    function testPrependNewCSS() {
        $this->assertResult(
            array('align' => 'left', 'style' => 'font-weight:bold;'),
            array('style' => 'text-align:left;font-weight:bold;')
        );

    }

    function testCaseInsensitive() {
        $this->obj = new HTMLPurifier_AttrTransform_EnumToCSS('align', array(
            'right' => 'text-align:right;'
        ));
        $this->assertResult(
            array('align' => 'RIGHT'),
            array('style' => 'text-align:right;')
        );
    }

    function testCaseSensitive() {
        $this->obj = new HTMLPurifier_AttrTransform_EnumToCSS('align', array(
            'right' => 'text-align:right;'
        ), true);
        $this->assertResult(
            array('align' => 'RIGHT'),
            array()
        );
    }

}

// vim: et sw=4 sts=4
