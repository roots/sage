<?php

class HTMLPurifier_Injector_LinkifyTest extends HTMLPurifier_InjectorHarness
{

    function setup() {
        parent::setup();
        $this->config->set('AutoFormat.Linkify', true);
    }

    function testLinkifyURLInRootNode() {
        $this->assertResult(
            'http://example.com',
            '<a href="http://example.com">http://example.com</a>'
        );
    }

    function testLinkifyURLInInlineNode() {
        $this->assertResult(
            '<b>http://example.com</b>',
            '<b><a href="http://example.com">http://example.com</a></b>'
        );
    }

    function testBasicUsageCase() {
        $this->assertResult(
            'This URL http://example.com is what you need',
            'This URL <a href="http://example.com">http://example.com</a> is what you need'
        );
    }

    function testIgnoreURLInATag() {
        $this->assertResult(
            '<a>http://example.com/</a>'
        );
    }

    function testNeeded() {
        $this->config->set('HTML.Allowed', 'b');
        $this->expectError('Cannot enable Linkify injector because a is not allowed');
        $this->assertResult('http://example.com/');
    }

    function testExcludes() {
        $this->assertResult('<a><span>http://example.com</span></a>');
    }

}

// vim: et sw=4 sts=4
