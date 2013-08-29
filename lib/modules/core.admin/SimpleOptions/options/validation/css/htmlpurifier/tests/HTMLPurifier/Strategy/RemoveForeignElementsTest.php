<?php

class HTMLPurifier_Strategy_RemoveForeignElementsTest extends HTMLPurifier_StrategyHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_RemoveForeignElements();
    }

    function testBlankInput() {
        $this->assertResult('');
    }

    function testPreserveRecognizedElements() {
        $this->assertResult('This is <b>bold text</b>.');
    }

    function testRemoveForeignElements() {
        $this->assertResult(
            '<asdf>Bling</asdf><d href="bang">Bong</d><foobar />',
            'BlingBong'
        );
    }

    function testRemoveScriptAndContents() {
        $this->assertResult(
            '<script>alert();</script>',
            ''
        );
    }

    function testRemoveStyleAndContents() {
        $this->assertResult(
            '<style>.foo {blink;}</style>',
            ''
        );
    }

    function testRemoveOnlyScriptTagsLegacy() {
        $this->config->set('Core.RemoveScriptContents', false);
        $this->assertResult(
            '<script>alert();</script>',
            'alert();'
        );
    }

    function testRemoveOnlyScriptTags() {
        $this->config->set('Core.HiddenElements', array());
        $this->assertResult(
            '<script>alert();</script>',
            'alert();'
        );
    }

    function testRemoveInvalidImg() {
        $this->assertResult('<img />', '');
    }

    function testPreserveValidImg() {
        $this->assertResult('<img src="foobar.gif" alt="foobar.gif" />');
    }

    function testPreserveInvalidImgWhenRemovalIsDisabled() {
        $this->config->set('Core.RemoveInvalidImg', false);
        $this->assertResult('<img />');
    }

    function testTextifyCommentedScriptContents() {
        $this->config->set('HTML.Trusted', true);
        $this->config->set('Output.CommentScriptContents', false); // simplify output
        $this->assertResult(
'<script type="text/javascript"><!--
alert(<b>bold</b>);
// --></script>',
'<script type="text/javascript">
alert(&lt;b&gt;bold&lt;/b&gt;);
// </script>'
        );
    }

    function testRequiredAttributesTestNotPerformedOnEndTag() {
        $def = $this->config->getHTMLDefinition(true);
        $def->addElement('f', 'Block', 'Optional: #PCDATA', false, array('req*' => 'Text'));
        $this->assertResult('<f req="text">Foo</f> Bar');
    }

    function testPreserveCommentsWithHTMLTrusted() {
        $this->config->set('HTML.Trusted', true);
        $this->assertResult('<!-- foo -->');
    }

    function testRemoveTrailingHyphensInComment() {
        $this->config->set('HTML.Trusted', true);
        $this->assertResult('<!-- foo ----->', '<!-- foo -->');
    }

    function testCollapseDoubleHyphensInComment() {
        $this->config->set('HTML.Trusted', true);
        $this->assertResult('<!-- bo --- asdf--as -->', '<!-- bo - asdf-as -->');
    }

    function testPreserveCommentsWithLookup() {
        $this->config->set('HTML.AllowedComments', array('allowed'));
        $this->assertResult('<!-- allowed --><!-- not allowed -->', '<!-- allowed -->');
    }

    function testPreserveCommentsWithRegexp() {
        $this->config->set('HTML.AllowedCommentsRegexp', '/^allowed[1-9]$/');
        $this->assertResult('<!-- allowed1 --><!-- not allowed -->', '<!-- allowed1 -->');
    }

}

// vim: et sw=4 sts=4
