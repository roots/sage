<?php

/**
 * This test is kinda weird, because it doesn't test the full safe object
 * functionality, just a small section of it. Or maybe it's actually the right
 * way.
 */
class HTMLPurifier_Injector_SafeObjectTest extends HTMLPurifier_InjectorHarness
{

    function setup() {
        parent::setup();
        // there is no AutoFormat.SafeObject directive
        $this->config->set('AutoFormat.Custom', array(new HTMLPurifier_Injector_SafeObject()));
        $this->config->set('HTML.Trusted', true);
    }

    function testPreserve() {
        $this->assertResult(
            '<b>asdf</b>'
        );
    }

    function testRemoveStrayParam() {
        $this->assertResult(
            '<param />',
            ''
        );
    }

    function testEditObjectParam() {
        $this->assertResult(
            '<object></object>',
            '<object><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /></object>'
        );
    }

    function testIgnoreStrayParam() {
        $this->assertResult(
            '<object><param /></object>',
            '<object><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /></object>'
        );
    }

    function testIgnoreDuplicates() {
        $this->assertResult(
            '<object><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /></object>'
        );
    }

    function testIgnoreBogusData() {
        $this->assertResult(
            '<object><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="always" /></object>',
            '<object><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /></object>'
        );
    }

    function testIgnoreInvalidData() {
        $this->assertResult(
            '<object><param name="foo" value="bar" /></object>',
            '<object><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /></object>'
        );
    }

    function testKeepValidData() {
        $this->assertResult(
            '<object><param name="movie" value="bar" /></object>',
            '<object data="bar"><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /><param name="movie" value="bar" /></object>'
        );
    }

    function testNested() {
        $this->assertResult(
            '<object><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /><object></object></object>',
            '<object><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /><object><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /></object></object>'
        );
    }

    function testNotActuallyNested() {
        $this->assertResult(
            '<object><p><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /></p></object>',
            '<object><param name="allowScriptAccess" value="never" /><param name="allowNetworking" value="internal" /><p></p></object>'
        );
    }

}

// vim: et sw=4 sts=4
