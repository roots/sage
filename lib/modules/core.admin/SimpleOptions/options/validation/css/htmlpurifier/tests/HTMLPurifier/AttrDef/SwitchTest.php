<?php

class HTMLPurifier_AttrDef_SwitchTest extends HTMLPurifier_AttrDefHarness
{

    protected $with, $without;

    function setUp() {
        parent::setUp();
        generate_mock_once('HTMLPurifier_AttrDef');
        $this->with = new HTMLPurifier_AttrDefMock();
        $this->without = new HTMLPurifier_AttrDefMock();
        $this->def = new HTMLPurifier_AttrDef_Switch('tag', $this->with, $this->without);
    }

    function testWith() {
        $token = new HTMLPurifier_Token_Start('tag');
        $this->context->register('CurrentToken', $token);
        $this->with->expectOnce('validate');
        $this->with->setReturnValue('validate', 'foo');
        $this->assertDef('bar', 'foo');
    }

    function testWithout() {
        $token = new HTMLPurifier_Token_Start('other-tag');
        $this->context->register('CurrentToken', $token);
        $this->without->expectOnce('validate');
        $this->without->setReturnValue('validate', 'foo');
        $this->assertDef('bar', 'foo');
    }

}

// vim: et sw=4 sts=4
