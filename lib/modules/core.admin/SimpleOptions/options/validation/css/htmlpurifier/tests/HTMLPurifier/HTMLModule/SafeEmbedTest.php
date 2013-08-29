<?php

class HTMLPurifier_HTMLModule_SafeEmbedTest extends HTMLPurifier_HTMLModuleHarness
{

    function setUp() {
        parent::setUp();
        $def = $this->config->getHTMLDefinition(true);
        $def->manager->addModule('SafeEmbed');
    }

    function testMinimal() {
        $this->assertResult(
            '<embed src="http://www.youtube.com/v/RVtEQxH7PWA&amp;hl=en" />',
            '<embed src="http://www.youtube.com/v/RVtEQxH7PWA&amp;hl=en" allowscriptaccess="never" allownetworking="internal" type="application/x-shockwave-flash" />'
        );
    }

    function testYouTube() {
        $this->assertResult(
            '<embed src="http://www.youtube.com/v/RVtEQxH7PWA&amp;hl=en" type="application/x-shockwave-flash" width="425" height="344"></embed>',
            '<embed src="http://www.youtube.com/v/RVtEQxH7PWA&amp;hl=en" type="application/x-shockwave-flash" width="425" height="344" allowscriptaccess="never" allownetworking="internal" />'
        );
    }

    function testMalicious() {
        $this->assertResult(
            '<embed src="http://example.com/bad.swf" type="application/x-shockwave-flash" width="9999999" height="3499994" allowscriptaccess="always" allownetworking="always" />',
            '<embed src="http://example.com/bad.swf" type="application/x-shockwave-flash" width="1200" height="1200" allowscriptaccess="never" allownetworking="internal" />'
        );
    }

    function testFull() {
        $this->assertResult(
            '<b><embed src="http://www.youtube.com/v/RVtEQxH7PWA&amp;hl=en" type="application/x-shockwave-flash" width="24" height="23" allowscriptaccess="never" allownetworking="internal" wmode="window" /></b>'
        );
    }

}

// vim: et sw=4 sts=4
