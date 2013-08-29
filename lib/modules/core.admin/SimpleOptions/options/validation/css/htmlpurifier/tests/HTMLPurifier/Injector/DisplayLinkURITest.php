<?php

class HTMLPurifier_Injector_DisplayLinkURITest extends HTMLPurifier_InjectorHarness
{

    function setup() {
        parent::setup();
        $this->config->set('AutoFormat.DisplayLinkURI', true);
    }

    function testBasicLink() {
        $this->assertResult(
            '<a href="http://malware.example.com">Don\'t go here!</a>',
            '<a>Don\'t go here!</a> (http://malware.example.com)'
        );
    }

    function testEmptyLink() {
        $this->assertResult(
            '<a>Don\'t go here!</a>',
            '<a>Don\'t go here!</a>'
        );
    }
    function testEmptyText() {
        $this->assertResult(
            '<a href="http://malware.example.com"></a>',
            '<a></a> (http://malware.example.com)'
        );
    }

}

// vim: et sw=4 sts=4
