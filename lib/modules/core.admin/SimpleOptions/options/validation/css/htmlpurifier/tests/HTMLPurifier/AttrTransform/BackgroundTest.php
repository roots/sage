<?php

class HTMLPurifier_AttrTransform_BackgroundTest extends HTMLPurifier_AttrTransformHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_Background();
    }

    function testEmptyInput() {
        $this->assertResult( array() );
    }

    function testBasicTransform() {
        $this->assertResult(
            array('background' => 'logo.png'),
            array('style' => 'background-image:url(logo.png);')
        );
    }

    function testPrependNewCSS() {
        $this->assertResult(
            array('background' => 'logo.png', 'style' => 'font-weight:bold'),
            array('style' => 'background-image:url(logo.png);font-weight:bold')
        );
    }

    function testLenientTreatmentOfInvalidInput() {
        // notice that we rely on the CSS validator later to fix this invalid
        // stuff
        $this->assertResult(
            array('background' => 'logo.png);foo:('),
            array('style' => 'background-image:url(logo.png);foo:();')
        );
    }

}

// vim: et sw=4 sts=4
