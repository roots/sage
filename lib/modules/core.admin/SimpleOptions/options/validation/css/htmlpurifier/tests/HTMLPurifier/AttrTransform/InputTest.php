<?php

class HTMLPurifier_AttrTransform_InputTest extends HTMLPurifier_AttrTransformHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_Input();
    }

    function testEmptyInput() {
        $this->assertResult(array());
    }

    function testInvalidCheckedWithEmpty() {
        $this->assertResult(array('checked' => 'checked'), array());
    }

    function testInvalidCheckedWithPassword() {
        $this->assertResult(array(
            'checked' => 'checked',
            'type' => 'password'
        ), array(
            'type' => 'password'
        ));
    }

    function testValidCheckedWithUcCheckbox() {
        $this->assertResult(array(
            'checked' => 'checked',
            'type' => 'CHECKBOX',
            'value' => 'bar',
        ));
    }

    function testInvalidMaxlength() {
        $this->assertResult(array(
            'maxlength' => '10',
            'type' => 'checkbox',
            'value' => 'foo',
        ), array(
            'type' => 'checkbox',
            'value' => 'foo',
        ));
    }

    function testValidMaxLength() {
        $this->assertResult(array(
            'maxlength' => '10',
        ));
    }

    // these two are really bad test-cases

    function testSizeWithCheckbox() {
        $this->assertResult(array(
            'type' => 'checkbox',
            'value' => 'foo',
            'size' => '100px',
        ), array(
            'type' => 'checkbox',
            'value' => 'foo',
            'size' => '100',
        ));
    }

    function testSizeWithText() {
        $this->assertResult(array(
            'type' => 'password',
            'size' => '100px', // spurious value, to indicate no validation takes place
        ), array(
            'type' => 'password',
            'size' => '100px',
        ));
    }

    function testInvalidSrc() {
        $this->assertResult(array(
            'src' => 'img.png',
        ), array());
    }

    function testMissingValue() {
        $this->assertResult(array(
            'type' => 'checkbox',
        ), array(
            'type' => 'checkbox',
            'value' => '',
        ));
    }

}

// vim: et sw=4 sts=4
