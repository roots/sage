<?php

class HTMLPurifier_AttrTransform_BorderTest extends HTMLPurifier_AttrTransformHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_Border();
    }

    function testEmptyInput() {
        $this->assertResult( array() );
    }

    function testBasicTransform() {
        $this->assertResult(
            array('border' => '1'),
            array('style' => 'border:1px solid;')
        );
    }

    function testLenientTreatmentOfInvalidInput() {
        $this->assertResult(
            array('border' => '10%'),
            array('style' => 'border:10%px solid;')
        );
    }

    function testPrependNewCSS() {
        $this->assertResult(
            array('border' => '23', 'style' => 'font-weight:bold;'),
            array('style' => 'border:23px solid;font-weight:bold;')
        );
    }

}

// vim: et sw=4 sts=4
