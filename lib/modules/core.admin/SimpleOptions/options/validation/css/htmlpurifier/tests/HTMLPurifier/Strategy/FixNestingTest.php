<?php

class HTMLPurifier_Strategy_FixNestingTest extends HTMLPurifier_StrategyHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_FixNesting();
    }

    function testPreserveInlineInRoot() {
        $this->assertResult('<b>Bold text</b>');
    }

    function testPreserveInlineAndBlockInRoot() {
        $this->assertResult('<a href="about:blank">Blank</a><div>Block</div>');
    }

    function testRemoveBlockInInline() {
        $this->assertResult(
            '<b><div>Illegal div.</div></b>',
            '<b>Illegal div.</b>'
        );
    }

    function testEscapeBlockInInline() {
        $this->config->set('Core.EscapeInvalidChildren', true);
        $this->assertResult(
            '<b><div>Illegal div.</div></b>',
            '<b>&lt;div&gt;Illegal div.&lt;/div&gt;</b>'
        );
    }

    function testRemoveNodeWithMissingRequiredElements() {
        $this->assertResult('<ul></ul>', '');
    }

    function testListHandleIllegalPCDATA() {
        $this->assertResult(
            '<ul>Illegal text<li>Legal item</li></ul>',
            '<ul><li>Illegal text</li><li>Legal item</li></ul>'
        );
    }

    function testRemoveIllegalPCDATA() {
        $this->assertResult(
            '<table><tr>Illegal text<td></td></tr></table>',
            '<table><tr><td></td></tr></table>'
        );
    }

    function testCustomTableDefinition() {
        $this->assertResult('<table><tr><td>Cell 1</td></tr></table>');
    }

    function testRemoveEmptyTable() {
        $this->assertResult('<table></table>', '');
    }

    function testChameleonRemoveBlockInNodeInInline() {
        $this->assertResult(
          '<span><ins><div>Not allowed!</div></ins></span>',
          '<span><ins>Not allowed!</ins></span>'
        );
    }

    function testChameleonRemoveBlockInBlockNodeWithInlineContent() {
        $this->assertResult(
          '<h1><ins><div>Not allowed!</div></ins></h1>',
          '<h1><ins>Not allowed!</ins></h1>'
        );
    }

    function testNestedChameleonRemoveBlockInNodeWithInlineContent() {
        $this->assertResult(
          '<h1><ins><del><div>Not allowed!</div></del></ins></h1>',
          '<h1><ins><del>Not allowed!</del></ins></h1>'
        );
    }

    function testNestedChameleonPreserveBlockInBlock() {
        $this->assertResult(
          '<div><ins><del><div>Allowed!</div></del></ins></div>'
        );
    }

    function testChameleonEscapeInvalidBlockInInline() {
        $this->config->set('Core.EscapeInvalidChildren', true);
        $this->assertResult( // alt config
          '<span><ins><div>Not allowed!</div></ins></span>',
          '<span><ins>&lt;div&gt;Not allowed!&lt;/div&gt;</ins></span>'
        );
    }

    function testExclusionsIntegration() {
        // test exclusions
        $this->assertResult(
          '<a><span><a>Not allowed</a></span></a>',
          '<a><span></span></a>'
        );
    }

    function testPreserveInlineNodeInInlineRootNode() {
        $this->config->set('HTML.Parent', 'span');
        $this->assertResult('<b>Bold</b>');
    }

    function testRemoveBlockNodeInInlineRootNode() {
        $this->config->set('HTML.Parent', 'span');
        $this->assertResult('<div>Reject</div>', 'Reject');
   }

   function testInvalidParentError() {
        // test fallback to div
        $this->config->set('HTML.Parent', 'obviously-impossible');
        $this->config->set('Cache.DefinitionImpl', null);
        $this->expectError('Cannot use unrecognized element as parent');
        $this->assertResult('<div>Accept</div>');
    }

    function testCascadingRemovalOfNodesMissingRequiredChildren() {
        $this->assertResult('<table><tr></tr></table>', '');
    }

    function testCascadingRemovalSpecialCaseCannotScrollOneBack() {
        $this->assertResult('<table><tr></tr><tr></tr></table>', '');
    }

    function testLotsOfCascadingRemovalOfNodes() {
        $this->assertResult('<table><tbody><tr></tr><tr></tr></tbody><tr></tr><tr></tr></table>', '');
    }

    function testAdjacentRemovalOfNodeMissingRequiredChildren() {
        $this->assertResult('<table></table><table></table>', '');
    }

    function testStrictBlockquoteInHTML401() {
        $this->config->set('HTML.Doctype', 'HTML 4.01 Strict');
        $this->assertResult('<blockquote>text</blockquote>', '<blockquote><p>text</p></blockquote>');
    }

    function testDisabledExcludes() {
        $this->config->set('Core.DisableExcludes', true);
        $this->assertResult('<pre><font><font></font></font></pre>');
    }

}

// vim: et sw=4 sts=4
