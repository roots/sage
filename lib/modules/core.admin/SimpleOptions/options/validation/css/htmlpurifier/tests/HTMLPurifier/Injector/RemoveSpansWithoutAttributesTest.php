<?php

class HTMLPurifier_Injector_RemoveSpansWithoutAttributesTest extends HTMLPurifier_InjectorHarness
{
    function setup() {
        parent::setup();
        $this->config->set('HTML.Allowed', 'span[class],div,p,strong,em');
        $this->config->set('AutoFormat.RemoveSpansWithoutAttributes', true);
    }

    function testSingleSpan() {
        $this->assertResult(
            '<span>foo</span>',
            'foo'
        );
    }

    function testSingleSpanWithAttributes() {
        $this->assertResult(
            '<span class="bar">foo</span>',
            '<span class="bar">foo</span>'
        );
    }

    function testSingleNestedSpan() {
        $this->assertResult(
            '<p><span>foo</span></p>',
            '<p>foo</p>'
        );
    }

    function testSingleNestedSpanWithAttributes() {
        $this->assertResult(
            '<p><span class="bar">foo</span></p>',
            '<p><span class="bar">foo</span></p>'
        );
    }


    function testSpanWithChildren() {
        $this->assertResult(
            '<span>foo <strong>bar</strong> <em>baz</em></span>',
            'foo <strong>bar</strong> <em>baz</em>'
        );
    }

    function testSpanWithSiblings() {
        $this->assertResult(
            '<p>before <span>inside</span> <strong>after</strong></p>',
            '<p>before inside <strong>after</strong></p>'
        );
    }

    function testNestedSpanWithSiblingsAndChildren() {
        $this->assertResult(
            '<p>a <span>b <em>c</em> d</span> e</p>',
            '<p>a b <em>c</em> d e</p>'
        );
    }

    function testNestedSpansWithoutAttributes() {
        $this->assertResult(
            '<span>one<span>two<span>three</span></span></span>',
            'onetwothree'
        );
    }

    function testDeeplyNestedSpan() {
        $this->assertResult(
            '<div><div><div><span class="a">a <span>b</span> c</span></div></div></div>',
            '<div><div><div><span class="a">a b c</span></div></div></div>'
        );
    }

    function testSpanWithInvalidAttributes() {
        $this->assertResult(
            '<p><span snorkel buzzer="emu">foo</span></p>',
            '<p>foo</p>'
        );
    }

    function testNestedAlternateSpans() {
        $this->assertResult(
'<span>a <span class="x">b <span>c <span class="y">d <span>e <span class="z">f
</span></span></span></span></span></span>',
'a <span class="x">b c <span class="y">d e <span class="z">f
</span></span></span>'
        );
    }

    function testSpanWithSomeInvalidAttributes() {
        $this->assertResult(
            '<p><span buzzer="emu" class="bar">foo</span></p>',
            '<p><span class="bar">foo</span></p>'
        );
    }
}

// vim: et sw=4 sts=4
