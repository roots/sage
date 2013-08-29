<?php

class HTMLPurifier_Strategy_MakeWellFormedTest extends HTMLPurifier_StrategyHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_MakeWellFormed();
    }

    function testEmptyInput() {
        $this->assertResult('');
    }

    function testWellFormedInput() {
        $this->assertResult('This is <b>bold text</b>.');
    }

    function testUnclosedTagTerminatedByDocumentEnd() {
        $this->assertResult(
            '<b>Unclosed tag, gasp!',
            '<b>Unclosed tag, gasp!</b>'
        );
    }

    function testUnclosedTagTerminatedByParentNodeEnd() {
        $this->assertResult(
            '<b><i>Bold and italic?</b>',
            '<b><i>Bold and italic?</i></b><i></i>'
        );
    }

    function testRemoveStrayClosingTag() {
        $this->assertResult(
            'Unused end tags... recycle!</b>',
            'Unused end tags... recycle!'
        );
    }

    function testConvertStartToEmpty() {
        $this->assertResult(
            '<br style="clear:both;">',
            '<br style="clear:both;" />'
        );
    }

    function testConvertEmptyToStart() {
        $this->assertResult(
            '<div style="clear:both;" />',
            '<div style="clear:both;"></div>'
        );
    }

    function testAutoCloseParagraph() {
        $this->assertResult(
            '<p>Paragraph 1<p>Paragraph 2',
            '<p>Paragraph 1</p><p>Paragraph 2</p>'
        );
    }

    function testAutoCloseParagraphInsideDiv() {
        $this->assertResult(
            '<div><p>Paragraphs<p>In<p>A<p>Div</div>',
            '<div><p>Paragraphs</p><p>In</p><p>A</p><p>Div</p></div>'
        );
    }

    function testAutoCloseListItem() {
        $this->assertResult(
            '<ol><li>Item 1<li>Item 2</ol>',
            '<ol><li>Item 1</li><li>Item 2</li></ol>'
        );
    }

    function testAutoCloseColgroup() {
        $this->assertResult(
            '<table><colgroup><col /><tr></tr></table>',
            '<table><colgroup><col /></colgroup><tr></tr></table>'
        );
    }

    function testAutoCloseMultiple() {
        $this->assertResult(
            '<b><span><div></div>asdf',
            '<b><span></span></b><div><b></b></div><b>asdf</b>'
        );
    }

    function testUnrecognized() {
        $this->assertResult(
            '<asdf><foobar /><biddles>foo</asdf>',
            '<asdf><foobar /><biddles>foo</biddles></asdf>'
        );
    }

    function testBlockquoteWithInline() {
        $this->config->set('HTML.Doctype', 'XHTML 1.0 Strict');
        $this->assertResult(
            // This is actually invalid, but will be fixed by
            // ChildDef_StrictBlockquote
            '<blockquote>foo<b>bar</b></blockquote>'
        );
    }

    function testLongCarryOver() {
        $this->assertResult(
            '<b>asdf<div>asdf<i>df</i></div>asdf</b>',
            '<b>asdf</b><div><b>asdf<i>df</i></b></div><b>asdf</b>'
        );
    }

    function testInterleaved() {
        $this->assertResult(
            '<u>foo<i>bar</u>baz</i>',
            '<u>foo<i>bar</i></u><i>baz</i>'
        );
    }

    function testNestedOl() {
        $this->assertResult(
            '<ol><ol><li>foo</li></ol></ol>',
            '<ol><ol><li>foo</li></ol></ol>'
        );
    }

    function testNestedUl() {
        $this->assertResult(
            '<ul><ul><li>foo</li></ul></ul>',
            '<ul><ul><li>foo</li></ul></ul>'
        );
    }

    function testNestedOlWithStrangeEnding() {
        $this->assertResult(
            '<ol><li><ol><ol><li>foo</li></ol></li><li>foo</li></ol>',
            '<ol><li><ol><ol><li>foo</li></ol></ol></li><li>foo</li></ol>'
        );
    }

    function testNoAutocloseIfNoParentsCanAccomodateTag() {
        $this->assertResult(
            '<table><tr><td><li>foo</li></td></tr></table>',
            '<table><tr><td>foo</td></tr></table>'
        );
    }

}

// vim: et sw=4 sts=4
