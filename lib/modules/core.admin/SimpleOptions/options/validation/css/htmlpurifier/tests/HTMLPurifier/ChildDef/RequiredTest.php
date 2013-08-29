<?php

class HTMLPurifier_ChildDef_RequiredTest extends HTMLPurifier_ChildDefHarness
{

    function testPrepareString() {
        $def = new HTMLPurifier_ChildDef_Required('foobar | bang |gizmo');
        $this->assertIdentical($def->elements,
          array(
            'foobar' => true
           ,'bang'   => true
           ,'gizmo'  => true
          ));
    }

    function testPrepareArray() {
        $def = new HTMLPurifier_ChildDef_Required(array('href', 'src'));
        $this->assertIdentical($def->elements,
          array(
            'href' => true
           ,'src'  => true
          ));
    }

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_ChildDef_Required('dt | dd');
    }

    function testEmptyInput() {
        $this->assertResult('', false);
    }

    function testRemoveIllegalTagsAndElements() {
        $this->assertResult(
          '<dt>Term</dt>Text in an illegal location'.
             '<dd>Definition</dd><b>Illegal tag</b>',
          '<dt>Term</dt><dd>Definition</dd>');
        $this->assertResult('How do you do!', false);
    }

    function testIgnoreWhitespace() {
        // whitespace shouldn't trigger it
        $this->assertResult("\n<dd>Definition</dd>       ");
    }

    function testPreserveWhitespaceAfterRemoval() {
        $this->assertResult(
          '<dd>Definition</dd>       <b></b>       ',
          '<dd>Definition</dd>              '
        );
    }

    function testDeleteNodeIfOnlyWhitespace() {
        $this->assertResult("\t      ", false);
    }

    function testPCDATAAllowed() {
        $this->obj = new HTMLPurifier_ChildDef_Required('#PCDATA | b');
        $this->assertResult('Out <b>Bold text</b><img />', 'Out <b>Bold text</b>');
    }

    function testPCDATAAllowedWithEscaping() {
        $this->obj = new HTMLPurifier_ChildDef_Required('#PCDATA | b');
        $this->config->set('Core.EscapeInvalidChildren', true);
        $this->assertResult(
            'Out <b>Bold text</b><img />',
            'Out <b>Bold text</b>&lt;img /&gt;'
        );
    }
}

// vim: et sw=4 sts=4
