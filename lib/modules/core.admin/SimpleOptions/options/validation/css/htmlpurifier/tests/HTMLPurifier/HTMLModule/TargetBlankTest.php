<?php

class HTMLPurifier_HTMLModule_TargetBlankTest extends HTMLPurifier_HTMLModuleHarness
{

    function setUp() {
        parent::setUp();
        $this->config->set('HTML.TargetBlank', true);
    }

    function testTargetBlank() {
        $this->assertResult(
            '<a href="http://google.com">a</a><a href="/local">b</a><a href="mailto:foo@example.com">c</a>',
            '<a href="http://google.com" target="_blank">a</a><a href="/local">b</a><a href="mailto:foo@example.com">c</a>'
        );
    }

}

// vim: et sw=4 sts=4
