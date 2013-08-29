<?php

class HTMLPurifier_HTMLModule_SafeObjectTest extends HTMLPurifier_HTMLModuleHarness
{

    function setUp() {
        parent::setUp();
        $this->config->set('HTML.DefinitionID', 'HTMLPurifier_HTMLModule_SafeObjectTest');
        $this->config->set('HTML.SafeObject', true);
    }

    function testMinimal() {
        $this->assertResult(
            '<object></object>',
            '<object type="application/x-shockwave-flash"><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /></object>'
        );
    }

    function testYouTube() {
        // embed is purposely removed
        $this->assertResult(
            '<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/RVtEQxH7PWA&hl=en"></param><embed src="http://www.youtube.com/v/RVtEQxH7PWA&hl=en" type="application/x-shockwave-flash" width="425" height="344"></embed></object>',
            '<object width="425" height="344" data="http://www.youtube.com/v/RVtEQxH7PWA&amp;hl=en" type="application/x-shockwave-flash"><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /><param name="movie" value="http://www.youtube.com/v/RVtEQxH7PWA&amp;hl=en" /></object>'
        );
    }

    function testMalicious() {
        $this->assertResult(
            '<object width="9999999" height="9999999"><param name="allowScriptAccess" value="always" /><param name="movie" value="http://example.com/attack.swf" /></object>',
            '<object width="1200" height="1200" data="http://example.com/attack.swf" type="application/x-shockwave-flash"><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /><param name="movie" value="http://example.com/attack.swf" /></object>'
        );
    }

    function testFull() {
        $this->assertResult(
            '<b><object width="425" height="344" type="application/x-shockwave-flash" data="Foobar"><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /><param name="flashvars" value="foobarbaz=bally" /><param name="movie" value="http://www.youtube.com/v/RVtEQxH7PWA&amp;hl=en" /><param name="wmode" value="window" /></object></b>'
        );
    }

    function testFullScreen() {
        $this->config->set('HTML.FlashAllowFullScreen', true);
        $this->assertResult(
            '<b><object width="425" height="344" type="application/x-shockwave-flash" data="Foobar"><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /><param name="flashvars" value="foobarbaz=bally" /><param name="movie" value="http://www.youtube.com/v/RVtEQxH7PWA&amp;hl=en" /><param name="wmode" value="window" /><param name="allowFullScreen" value="true" /></object></b>'
        );
    }

}

// vim: et sw=4 sts=4
