<?php

class HTMLPurifier_AttrDef_CSS_ImportantDecoratorTest extends HTMLPurifier_AttrDefHarness
{

    /** Mock AttrDef decorator is wrapping */
    protected $mock;

    function setUp() {
        generate_mock_once('HTMLPurifier_AttrDef');
        $this->mock = new HTMLPurifier_AttrDefMock();
        $this->def  = new HTMLPurifier_AttrDef_CSS_ImportantDecorator($this->mock, true);
    }

    protected function setMock($input, $output = null) {
        if ($output === null) $output = $input;
        $this->mock->expectOnce('validate', array($input, $this->config, $this->context));
        $this->mock->setReturnValue('validate', $output);
    }

    function testImportant() {
        $this->setMock('23');
        $this->assertDef('23 !important');
    }

    function testImportantInternalDefChanged() {
        $this->setMock('23', '24');
        $this->assertDef('23 !important', '24 !important');
    }

    function testImportantWithSpace() {
        $this->setMock('23');
        $this->assertDef('23 !  important  ', '23 !important');
    }

    function testFakeImportant() {
        $this->setMock('! foo important');
        $this->assertDef('! foo important');
    }

    function testStrip() {
        $this->def  = new HTMLPurifier_AttrDef_CSS_ImportantDecorator($this->mock, false);
        $this->setMock('23');
        $this->assertDef('23 !  important  ', '23');
    }

}

// vim: et sw=4 sts=4
