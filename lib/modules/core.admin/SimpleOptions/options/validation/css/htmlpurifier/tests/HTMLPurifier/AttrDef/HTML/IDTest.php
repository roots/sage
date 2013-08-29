<?php

class HTMLPurifier_AttrDef_HTML_IDTest extends HTMLPurifier_AttrDefHarness
{

    function setUp() {
        parent::setUp();

        $id_accumulator = new HTMLPurifier_IDAccumulator();
        $this->context->register('IDAccumulator', $id_accumulator);
        $this->config->set('Attr.EnableID', true);
        $this->def = new HTMLPurifier_AttrDef_HTML_ID();

    }

    function test() {

        // valid ID names
        $this->assertDef('alpha');
        $this->assertDef('al_ha');
        $this->assertDef('a0-:.');
        $this->assertDef('a');

        // invalid ID names
        $this->assertDef('<asa', false);
        $this->assertDef('0123', false);
        $this->assertDef('.asa', false);

        // test duplicate detection
        $this->assertDef('once');
        $this->assertDef('once', false);

        // valid once whitespace stripped, but needs to be amended
        $this->assertDef(' whee ', 'whee');

    }

    function testPrefix() {

        $this->config->set('Attr.IDPrefix', 'user_');

        $this->assertDef('alpha', 'user_alpha');
        $this->assertDef('<asa', false);
        $this->assertDef('once', 'user_once');
        $this->assertDef('once', false);

        // if already prefixed, leave alone
        $this->assertDef('user_alas');
        $this->assertDef('user_user_alas'); // how to bypass

    }

    function testTwoPrefixes() {

        $this->config->set('Attr.IDPrefix', 'user_');
        $this->config->set('Attr.IDPrefixLocal', 'story95_');

        $this->assertDef('alpha', 'user_story95_alpha');
        $this->assertDef('<asa', false);
        $this->assertDef('once', 'user_story95_once');
        $this->assertDef('once', false);

        $this->assertDef('user_story95_alas');
        $this->assertDef('user_alas', 'user_story95_user_alas'); // !
    }

    function testLocalPrefixWithoutMainPrefix() {
        // no effect when IDPrefix isn't set
        $this->config->set('Attr.IDPrefix', '');
        $this->config->set('Attr.IDPrefixLocal', 'story95_');
        $this->expectError('%Attr.IDPrefixLocal cannot be used unless '.
            '%Attr.IDPrefix is set');
        $this->assertDef('amherst');

    }

    // reference functionality is disabled for now
    function disabled_testIDReference() {

        $this->def = new HTMLPurifier_AttrDef_HTML_ID(true);

        $this->assertDef('good_id');
        $this->assertDef('good_id'); // duplicates okay
        $this->assertDef('<b>', false);

        $this->def = new HTMLPurifier_AttrDef_HTML_ID();

        $this->assertDef('good_id');
        $this->assertDef('good_id', false); // duplicate now not okay

        $this->def = new HTMLPurifier_AttrDef_HTML_ID(true);

        $this->assertDef('good_id'); // reference still okay

    }

    function testRegexp() {

        $this->config->set('Attr.IDBlacklistRegexp', '/^g_/');

        $this->assertDef('good_id');
        $this->assertDef('g_bad_id', false);

    }

}

// vim: et sw=4 sts=4
