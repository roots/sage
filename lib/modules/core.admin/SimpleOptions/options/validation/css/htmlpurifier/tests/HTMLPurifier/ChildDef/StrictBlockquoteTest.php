<?php

class   HTMLPurifier_ChildDef_StrictBlockquoteTest
extends HTMLPurifier_ChildDefHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_ChildDef_StrictBlockquote('div | p');
    }

    function testEmptyInput() {
        $this->assertResult('');
    }

    function testPreserveValidP() {
        $this->assertResult('<p>Valid</p>');
    }

    function testPreserveValidDiv() {
        $this->assertResult('<div>Still valid</div>');
    }

    function testWrapTextWithP() {
        $this->assertResult('Needs wrap', '<p>Needs wrap</p>');
    }

    function testNoWrapForWhitespaceOrValidElements() {
        $this->assertResult('<p>Do not wrap</p>    <p>Whitespace</p>');
    }

    function testWrapTextNextToValidElements() {
        $this->assertResult(
               'Wrap'. '<p>Do not wrap</p>',
            '<p>Wrap</p><p>Do not wrap</p>'
        );
    }

    function testWrapInlineElements() {
        $this->assertResult(
            '<p>Do not</p>'.'<b>Wrap</b>',
            '<p>Do not</p><p><b>Wrap</b></p>'
        );
    }

    function testWrapAndRemoveInvalidTags() {
        $this->assertResult(
            '<li>Not allowed</li>Paragraph.<p>Hmm.</p>',
            '<p>Not allowedParagraph.</p><p>Hmm.</p>'
        );
    }

    function testWrapComplicatedSring() {
        $this->assertResult(
            $var = 'He said<br />perhaps<br />we should <b>nuke</b> them.',
            "<p>$var</p>"
        );
    }

    function testWrapAndRemoveInvalidTagsComplex() {
        $this->assertResult(
            '<foo>Bar</foo><bas /><b>People</b>Conniving.'. '<p>Fools!</p>',
              '<p>Bar'.          '<b>People</b>Conniving.</p><p>Fools!</p>'
        );
    }

    function testAlternateWrapper() {
        $this->config->set('HTML.BlockWrapper', 'div');
        $this->assertResult('Needs wrap', '<div>Needs wrap</div>');

    }

    function testError() {
        $this->expectError('Cannot use non-block element as block wrapper');
        $this->obj = new HTMLPurifier_ChildDef_StrictBlockquote('div | p');
        $this->config->set('HTML.BlockWrapper', 'dav');
        $this->config->set('Cache.DefinitionImpl', null);
        $this->assertResult('Needs wrap', '<p>Needs wrap</p>');
    }

}

// vim: et sw=4 sts=4
