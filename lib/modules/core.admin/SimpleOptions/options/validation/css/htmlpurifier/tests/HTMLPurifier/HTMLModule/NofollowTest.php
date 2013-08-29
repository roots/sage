<?php

class HTMLPurifier_HTMLModule_NofollowTest extends HTMLPurifier_HTMLModuleHarness
{

    function setUp() {
        parent::setUp();
        $this->config->set('HTML.Nofollow', true);
        $this->config->set('Attr.AllowedRel', array("nofollow", "blah"));
    }

    function testNofollow() {
        $this->assertResult(
            '<a href="http://google.com">x</a><a href="http://google.com" rel="blah">a</a><a href="/local">b</a><a href="mailto:foo@example.com">c</a>',
            '<a href="http://google.com" rel="nofollow">x</a><a href="http://google.com" rel="blah nofollow">a</a><a href="/local">b</a><a href="mailto:foo@example.com">c</a>'
        );
    }

    function testNofollowDupe() {
        $this->assertResult(
            '<a href="http://google.com" rel="nofollow">x</a><a href="http://google.com" rel="blah nofollow">a</a><a href="/local">b</a><a href="mailto:foo@example.com">c</a>'
        );
    }

}

// vim: et sw=4 sts=4
