<?php

/**
 * @todo Aim for complete code coverage with mocks
 */
class HTMLPurifier_AttrDef_URITest extends HTMLPurifier_AttrDefHarness
{

    function setUp() {
        $this->def = new HTMLPurifier_AttrDef_URI();
        parent::setUp();
    }

    function testIntegration() {
        $this->assertDef('http://www.google.com/');
        $this->assertDef('http:', '');
        $this->assertDef('http:/foo', '/foo');
        $this->assertDef('javascript:bad_stuff();', false);
        $this->assertDef('ftp://www.example.com/');
        $this->assertDef('news:rec.alt');
        $this->assertDef('nntp://news.example.com/324234');
        $this->assertDef('mailto:bob@example.com');
    }

    function testIntegrationWithPercentEncoder() {
        $this->assertDef(
            'http://www.example.com/%56%fc%GJ%5%FC',
            'http://www.example.com/V%FC%25GJ%255%FC'
        );
    }

    function testPercentEncoding() {
        $this->assertDef(
            'http:colon:mercenary',
            'colon%3Amercenary'
        );
    }

    function testPercentEncodingPreserve() {
        $this->assertDef(
            'http://www.example.com/abcABC123-_.!~*()\''
        );
    }

    function testEmbeds() {
        $this->def = new HTMLPurifier_AttrDef_URI(true);
        $this->assertDef('http://sub.example.com/alas?foo=asd');
        $this->assertDef('mailto:foo@example.com', false);
    }

    function testConfigMunge() {
        $this->config->set('URI.Munge', 'http://www.google.com/url?q=%s');
        $this->assertDef(
            'http://www.example.com/',
            'http://www.google.com/url?q=http%3A%2F%2Fwww.example.com%2F'
        );
        $this->assertDef('index.html');
        $this->assertDef('javascript:foobar();', false);
    }

    function testDefaultSchemeRemovedInBlank() {
        $this->assertDef('http:', '');
    }

    function testDefaultSchemeRemovedInRelativeURI() {
        $this->assertDef('http:/foo/bar', '/foo/bar');
    }

    function testDefaultSchemeNotRemovedInAbsoluteURI() {
        $this->assertDef('http://example.com/foo/bar');
    }

    function testAltSchemeNotRemoved() {
        $this->assertDef('mailto:this-looks-like-a-path@example.com');
    }

    function testResolveNullSchemeAmbiguity() {
        $this->assertDef('///foo', '/foo');
    }

    function testResolveNullSchemeDoubleAmbiguity() {
        $this->config->set('URI.Host', 'example.com');
        $this->assertDef('////foo', '//example.com//foo');
    }

    function testURIDefinitionValidation() {
        $parser = new HTMLPurifier_URIParser();
        $uri = $parser->parse('http://example.com');
        $this->config->set('URI.DefinitionID', 'HTMLPurifier_AttrDef_URITest->testURIDefinitionValidation');

        generate_mock_once('HTMLPurifier_URIDefinition');
        $uri_def = new HTMLPurifier_URIDefinitionMock();
        $uri_def->expectOnce('filter', array($uri, '*', '*'));
        $uri_def->setReturnValue('filter', true, array($uri, '*', '*'));
        $uri_def->expectOnce('postFilter', array($uri, '*', '*'));
        $uri_def->setReturnValue('postFilter', true, array($uri, '*', '*'));
        $uri_def->setup = true;

        // Since definitions are no longer passed by reference, we need
        // to muck around with the cache to insert our mock. This is
        // technically a little bad, since the cache shouldn't change
        // behavior, but I don't feel too good about letting users
        // overload entire definitions.
        generate_mock_once('HTMLPurifier_DefinitionCache');
        $cache_mock = new HTMLPurifier_DefinitionCacheMock();
        $cache_mock->setReturnValue('get', $uri_def);

        generate_mock_once('HTMLPurifier_DefinitionCacheFactory');
        $factory_mock = new HTMLPurifier_DefinitionCacheFactoryMock();
        $old = HTMLPurifier_DefinitionCacheFactory::instance();
        HTMLPurifier_DefinitionCacheFactory::instance($factory_mock);
        $factory_mock->setReturnValue('create', $cache_mock);

        $this->assertDef('http://example.com');

        HTMLPurifier_DefinitionCacheFactory::instance($old);
    }

    function test_make() {
        $factory = new HTMLPurifier_AttrDef_URI();
        $def = $factory->make('');
        $def2 = new HTMLPurifier_AttrDef_URI();
        $this->assertIdentical($def, $def2);

        $def = $factory->make('embedded');
        $def2 = new HTMLPurifier_AttrDef_URI(true);
        $this->assertIdentical($def, $def2);
    }

    /*
    function test_validate_configWhitelist() {

        $this->config->set('URI.HostPolicy', 'DenyAll');
        $this->config->set('URI.HostWhitelist', array(null, 'google.com'));

        $this->assertDef('http://example.com/fo/google.com', false);
        $this->assertDef('server.txt');
        $this->assertDef('ftp://www.google.com/?t=a');
        $this->assertDef('http://google.com.tricky.spamsite.net', false);

    }
    */

}

// vim: et sw=4 sts=4
