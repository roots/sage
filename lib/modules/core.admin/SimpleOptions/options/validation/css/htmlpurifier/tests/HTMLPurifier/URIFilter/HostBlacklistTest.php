<?php

class HTMLPurifier_URIFilter_HostBlacklistTest extends HTMLPurifier_URIFilterHarness
{

    function setUp() {
        parent::setUp();
        $this->filter = new HTMLPurifier_URIFilter_HostBlacklist();
    }

    function testRejectBlacklistedHost() {
        $this->config->set('URI.HostBlacklist', 'example.com');
        $this->assertFiltering('http://example.com', false);
    }

    function testRejectBlacklistedHostThoughNotTrue() {
        // maybe this behavior should change
        $this->config->set('URI.HostBlacklist', 'example.com');
        $this->assertFiltering('http://example.comcast.com', false);
    }

    function testPreserveNonBlacklistedHost() {
        $this->config->set('URI.HostBlacklist', 'example.com');
        $this->assertFiltering('http://google.com');
    }

}

// vim: et sw=4 sts=4
