<?php

class HTMLPurifier_AttrTransform_ImgRequiredTest extends HTMLPurifier_AttrTransformHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_ImgRequired();
    }

    function testAddMissingAttr() {
        $this->config->set('Core.RemoveInvalidImg', false);
        $this->assertResult(
            array(),
            array('src' => '', 'alt' => 'Invalid image')
        );
    }

    function testAlternateDefaults() {
        $this->config->set('Attr.DefaultInvalidImage', 'blank.png');
        $this->config->set('Attr.DefaultInvalidImageAlt', 'Pawned!');
        $this->config->set('Attr.DefaultImageAlt', 'not pawned');
        $this->config->set('Core.RemoveInvalidImg', false);
        $this->assertResult(
            array(),
            array('src' => 'blank.png', 'alt' => 'Pawned!')
        );
    }

    function testGenerateAlt() {
        $this->assertResult(
            array('src' => '/path/to/foobar.png'),
            array('src' => '/path/to/foobar.png', 'alt' => 'foobar.png')
        );
    }

    function testAddDefaultSrc() {
        $this->config->set('Core.RemoveInvalidImg', false);
        $this->assertResult(
            array('alt' => 'intrigue'),
            array('alt' => 'intrigue', 'src' => '')
        );
    }

    function testAddDefaultAlt() {
        $this->config->set('Attr.DefaultImageAlt', 'default');
        $this->assertResult(
            array('src' => ''),
            array('src' => '', 'alt' => 'default')
        );
    }

}

// vim: et sw=4 sts=4
