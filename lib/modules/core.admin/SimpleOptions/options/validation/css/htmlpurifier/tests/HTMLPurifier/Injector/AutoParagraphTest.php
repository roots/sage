<?php

class HTMLPurifier_Injector_AutoParagraphTest extends HTMLPurifier_InjectorHarness
{

    function setup() {
        parent::setup();
        $this->config->set('AutoFormat.AutoParagraph', true);
    }

    function testSingleParagraph() {
        $this->assertResult(
            'Foobar',
            '<p>Foobar</p>'
        );
    }

    function testSingleMultiLineParagraph() {
        $this->assertResult(
'Par 1
Par 1 still',
'<p>Par 1
Par 1 still</p>'
        );
    }

    function testTwoParagraphs() {
        $this->assertResult(
'Par1

Par2',
"<p>Par1</p>

<p>Par2</p>"
        );
    }

    function testTwoParagraphsWithLotsOfSpace() {
        $this->assertResult(
'Par1



Par2',
'<p>Par1</p>

<p>Par2</p>'
        );
    }

    function testTwoParagraphsWithInlineElements() {
        $this->assertResult(
'<b>Par1</b>

<i>Par2</i>',
'<p><b>Par1</b></p>

<p><i>Par2</i></p>'
        );
    }

    function testSingleParagraphThatLooksLikeTwo() {
        $this->assertResult(
'<b>Par1

Par2</b>',
'<p><b>Par1

Par2</b></p>'
        );
    }

    function testAddParagraphAdjacentToParagraph() {
        $this->assertResult(
            'Par1<p>Par2</p>',
'<p>Par1</p>

<p>Par2</p>'
        );
    }

    function testParagraphUnclosedInlineElement() {
        $this->assertResult(
            '<b>Par1',
            '<p><b>Par1</b></p>'
        );
    }

    function testPreservePreTags() {
        $this->assertResult(
'<pre>Par1

Par1</pre>'
        );
    }

    function testIgnoreTrailingWhitespace() {
        $this->assertResult(
'Par1

  ',
'<p>Par1</p>

'
        );
    }

    function testDoNotParagraphBlockElements() {
        $this->assertResult(
'Par1

<div>Par2</div>

Par3',
'<p>Par1</p>

<div>Par2</div>

<p>Par3</p>'
        );
    }

    function testParagraphTextAndInlineNodes() {
        $this->assertResult(
'Par<b>1</b>',
            '<p>Par<b>1</b></p>'
        );
    }

    function testPreserveLeadingWhitespace() {
        $this->assertResult(
'

Par',
'

<p>Par</p>'
        );
    }

    function testPreserveSurroundingWhitespace() {
        $this->assertResult(
'

Par

',
'

<p>Par</p>

'
        );
    }

    function testParagraphInsideBlockNode() {
        $this->assertResult(
'<div>Par1

Par2</div>',
'<div><p>Par1</p>

<p>Par2</p></div>'
        );
    }

    function testParagraphInlineNodeInsideBlockNode() {
        $this->assertResult(
'<div><b>Par1</b>

Par2</div>',
'<div><p><b>Par1</b></p>

<p>Par2</p></div>'
        );
    }

    function testNoParagraphWhenOnlyOneInsideBlockNode() {
        $this->assertResult('<div>Par1</div>');
    }

    function testParagraphTwoInlineNodesInsideBlockNode() {
        $this->assertResult(
'<div><b>Par1</b>

<i>Par2</i></div>',
'<div><p><b>Par1</b></p>

<p><i>Par2</i></p></div>'
        );
    }

    function testPreserveInlineNodesInPreTag() {
        $this->assertResult(
'<pre><b>Par1</b>

<i>Par2</i></pre>'
        );
    }

    function testSplitUpInternalsOfPTagInBlockNode() {
        $this->assertResult(
'<div><p>Foo

Bar</p></div>',
'<div><p>Foo</p>

<p>Bar</p></div>'
        );
    }

    function testSplitUpInlineNodesInPTagInBlockNode() {
        $this->assertResult(
'<div><p><b>Foo</b>

<i>Bar</i></p></div>',
'<div><p><b>Foo</b></p>

<p><i>Bar</i></p></div>'
        );
    }

    function testNoParagraphSingleInlineNodeInBlockNode() {
        $this->assertResult( '<div><b>Foo</b></div>' );
    }

    function testParagraphInBlockquote() {
        $this->assertResult(
'<blockquote>Par1

Par2</blockquote>',
'<blockquote><p>Par1</p>

<p>Par2</p></blockquote>'
        );
    }

    function testNoParagraphBetweenListItem() {
        $this->assertResult(
'<ul><li>Foo</li>

<li>Bar</li></ul>'
        );
    }

    function testParagraphSingleElementWithSurroundingSpace() {
        $this->assertResult(
'<div>

Bar

</div>',
        '<div>

<p>Bar</p>

</div>'
        );
    }

    function testIgnoreExtraSpaceWithLeadingInlineNode() {
        $this->assertResult(
'<b>Par1</b>a



Par2',
'<p><b>Par1</b>a</p>

<p>Par2</p>'
        );
    }

    function testAbsorbExtraEndingPTag() {
        $this->assertResult(
'Par1

Par2</p>',
'<p>Par1</p>

<p>Par2</p>'
        );
    }

    function testAbsorbExtraEndingDivTag() {
        $this->assertResult(
'Par1

Par2</div>',
'<p>Par1</p>

<p>Par2</p>'
        );
    }

    function testDoNotParagraphSingleSurroundingSpaceInBlockNode() {
        $this->assertResult(
'<div>
Par1
</div>'
        );
    }

    function testBlockNodeTextDelimeterInBlockNode() {
        $this->assertResult(
'<div>Par1

<div>Par2</div></div>',
'<div><p>Par1</p>

<div>Par2</div></div>'
        );
    }

    function testBlockNodeTextDelimeterWithoutDoublespaceInBlockNode() {
        $this->assertResult(
'<div>Par1
<div>Par2</div></div>'
        );
    }

    function testBlockNodeTextDelimeterWithoutDoublespace() {
        $this->assertResult(
'Par1
<div>Par2</div>',
'<p>Par1
</p>

<div>Par2</div>'
        );
    }

    function testTwoParagraphsOfTextAndInlineNode() {
        $this->assertResult(
'Par1

<b>Par2</b>',
'<p>Par1</p>

<p><b>Par2</b></p>'
        );
    }

    function testLeadingInlineNodeParagraph() {
        $this->assertResult(
'<img /> Foo',
'<p><img /> Foo</p>'
        );
    }

    function testTrailingInlineNodeParagraph() {
        $this->assertResult(
'<li>Foo <a>bar</a></li>'
        );
    }

    function testTwoInlineNodeParagraph() {
        $this->assertResult(
'<li><b>baz</b><a>bar</a></li>'
        );
    }

    function testNoParagraphTrailingBlockNodeInBlockNode() {
        $this->assertResult(
'<div><div>asdf</div><b>asdf</b></div>'
        );
    }

    function testParagraphTrailingBlockNodeWithDoublespaceInBlockNode() {
        $this->assertResult(
'<div><div>asdf</div>

<b>asdf</b></div>',
'<div><div>asdf</div>

<p><b>asdf</b></p></div>'
        );
    }

    function testParagraphTwoInlineNodesAndWhitespaceNode() {
        $this->assertResult(
'<b>One</b> <i>Two</i>',
'<p><b>One</b> <i>Two</i></p>'
        );
    }

    function testNoParagraphWithInlineRootNode() {
        $this->config->set('HTML.Parent', 'span');
        $this->assertResult(
'Par

Par2'
        );
    }

    function testInlineAndBlockTagInDivNoParagraph() {
        $this->assertResult(
            '<div><code>bar</code> mmm <pre>asdf</pre></div>'
        );
    }

    function testInlineAndBlockTagInDivNeedingParagraph() {
        $this->assertResult(
'<div><code>bar</code> mmm

<pre>asdf</pre></div>',
'<div><p><code>bar</code> mmm</p>

<pre>asdf</pre></div>'
        );
    }

    function testTextInlineNodeTextThenDoubleNewlineNeedsParagraph() {
        $this->assertResult(
'<div>asdf <code>bar</code> mmm

<pre>asdf</pre></div>',
'<div><p>asdf <code>bar</code> mmm</p>

<pre>asdf</pre></div>'
        );
    }

    function testUpcomingTokenHasNewline() {
        $this->assertResult(
'<div>Test<b>foo</b>bar<b>bing</b>bang

boo</div>',
'<div><p>Test<b>foo</b>bar<b>bing</b>bang</p>

<p>boo</p></div>'
);
    }

    function testEmptyTokenAtEndOfDiv() {
        $this->assertResult(
'<div><p>foo</p>
</div>',
'<div><p>foo</p>
</div>'
);
    }

    function testEmptyDoubleLineTokenAtEndOfDiv() {
        $this->assertResult(
'<div><p>foo</p>

</div>',
'<div><p>foo</p>

</div>'
);
    }

    function testTextState11Root() {
        $this->assertResult('<div></div>   ');
    }

    function testTextState11Element() {
        $this->assertResult(
"<div><div></div>

</div>");
    }

    function testTextStateLikeElementState111NoWhitespace() {
        $this->assertResult('<div><p>P</p>Boo</div>', '<div><p>P</p>Boo</div>');
    }

    function testElementState111NoWhitespace() {
        $this->assertResult('<div><p>P</p><b>Boo</b></div>', '<div><p>P</p><b>Boo</b></div>');
    }

    function testElementState133() {
        $this->assertResult(
"<div><b>B</b><pre>Ba</pre>

Bar</div>",
"<div><b>B</b><pre>Ba</pre>

<p>Bar</p></div>"
);
    }

    function testElementState22() {
        $this->assertResult(
            '<ul><li>foo</li></ul>'
        );
    }

    function testElementState311() {
        $this->assertResult(
            '<p>Foo</p><b>Bar</b>',
'<p>Foo</p>

<p><b>Bar</b></p>'
        );
    }

    function testAutoClose() {
        $this->assertResult(
            '<p></p>
<hr />'
        );
    }

    function testErrorNeeded() {
        $this->config->set('HTML.Allowed', 'b');
        $this->expectError('Cannot enable AutoParagraph injector because p is not allowed');
        $this->assertResult('<b>foobar</b>');
    }

}

// vim: et sw=4 sts=4
