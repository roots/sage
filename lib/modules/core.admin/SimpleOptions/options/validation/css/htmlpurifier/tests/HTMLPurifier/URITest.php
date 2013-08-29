<?php

class HTMLPurifier_URITest extends HTMLPurifier_URIHarness
{

    protected function createURI($uri) {
        $parser = new HTMLPurifier_URIParser();
        return $parser->parse($uri);
    }

    function test_construct() {
        $uri1 = new HTMLPurifier_URI('HTTP', 'bob', 'example.com', '23', '/foo', 'bar=2', 'slash');
        $uri2 = new HTMLPurifier_URI('http', 'bob', 'example.com',  23,  '/foo', 'bar=2', 'slash');
        $this->assertIdentical($uri1, $uri2);
    }

    protected $oldRegistry;

    protected function &setUpSchemeRegistryMock() {
        $this->oldRegistry = HTMLPurifier_URISchemeRegistry::instance();
        generate_mock_once('HTMLPurifier_URIScheme');
        generate_mock_once('HTMLPurifier_URISchemeRegistry');
        $registry = HTMLPurifier_URISchemeRegistry::instance(
          new HTMLPurifier_URISchemeRegistryMock()
        );
        return $registry;
    }

    protected function setUpSchemeMock($name) {
        $registry = $this->setUpSchemeRegistryMock();
        $scheme_mock = new HTMLPurifier_URISchemeMock();
        $registry->setReturnValue('getScheme', $scheme_mock, array($name, '*', '*'));
        return $scheme_mock;
    }

    protected function setUpNoValidSchemes() {
        $registry = $this->setUpSchemeRegistryMock();
        $registry->setReturnValue('getScheme', false, array('*', '*', '*'));
    }

    protected function tearDownSchemeRegistryMock() {
        HTMLPurifier_URISchemeRegistry::instance($this->oldRegistry);
    }

    function test_getSchemeObj() {
        $scheme_mock = $this->setUpSchemeMock('http');

        $uri = $this->createURI('http:');
        $scheme_obj = $uri->getSchemeObj($this->config, $this->context);
        $this->assertIdentical($scheme_obj, $scheme_mock);

        $this->tearDownSchemeRegistryMock();
    }

    function test_getSchemeObj_invalidScheme() {
        $this->setUpNoValidSchemes();

        $uri = $this->createURI('http:');
        $result = $uri->getSchemeObj($this->config, $this->context);
        $this->assertIdentical($result, false);

        $this->tearDownSchemeRegistryMock();
    }

    function test_getSchemaObj_defaultScheme() {
        $scheme = 'foobar';

        $scheme_mock = $this->setUpSchemeMock($scheme);
        $this->config->set('URI.DefaultScheme', $scheme);

        $uri = $this->createURI('hmm');
        $scheme_obj = $uri->getSchemeObj($this->config, $this->context);
        $this->assertIdentical($scheme_obj, $scheme_mock);

        $this->tearDownSchemeRegistryMock();
    }

    function test_getSchemaObj_invalidDefaultScheme() {
        $this->setUpNoValidSchemes();
        $this->config->set('URI.DefaultScheme', 'foobar');

        $uri = $this->createURI('hmm');

        $this->expectError('Default scheme object "foobar" was not readable');
        $result = $uri->getSchemeObj($this->config, $this->context);
        $this->assertIdentical($result, false);

        $this->tearDownSchemeRegistryMock();
    }

    protected function assertToString($expect_uri, $scheme, $userinfo, $host, $port, $path, $query, $fragment) {
        $uri = new HTMLPurifier_URI($scheme, $userinfo, $host, $port, $path, $query, $fragment);
        $string = $uri->toString();
        $this->assertIdentical($string, $expect_uri);
    }

    function test_toString_full() {
        $this->assertToString(
            'http://bob@example.com:300/foo?bar=baz#fragment',
            'http', 'bob', 'example.com', 300, '/foo', 'bar=baz', 'fragment'
        );
    }

    function test_toString_scheme() {
        $this->assertToString(
            'http:',
            'http', null, null, null, '', null, null
        );
    }

    function test_toString_authority() {
        $this->assertToString(
            '//bob@example.com:8080',
            null, 'bob', 'example.com', 8080, '', null, null
        );
    }

    function test_toString_path() {
        $this->assertToString(
            '/path/to',
            null, null, null, null, '/path/to', null, null
        );
    }

    function test_toString_query() {
        $this->assertToString(
            '?q=string',
            null, null, null, null, '', 'q=string', null
        );
    }

    function test_toString_fragment() {
        $this->assertToString(
            '#fragment',
            null, null, null, null, '', null, 'fragment'
        );
    }

    protected function assertValidation($uri, $expect_uri = true) {
        if ($expect_uri === true) $expect_uri = $uri;
        $uri = $this->createURI($uri);
        $result = $uri->validate($this->config, $this->context);
        if ($expect_uri === false) {
            $this->assertFalse($result);
        } else {
            $this->assertTrue($result);
            $this->assertIdentical($uri->toString(), $expect_uri);
        }
    }

    function test_validate_overlongPort() {
        $this->assertValidation('http://example.com:65536', 'http://example.com');
    }

    function test_validate_zeroPort() {
        $this->assertValidation('http://example.com:00', 'http://example.com');
    }

    function test_validate_invalidHostThatLooksLikeIPv6() {
        $this->assertValidation('http://[2001:0db8:85z3:08d3:1319:8a2e:0370:7334]', '');
    }

    function test_validate_removeRedundantScheme() {
        $this->assertValidation('http:foo:/:', 'foo%3A/:');
    }

    function test_validate_username() {
        $this->assertValidation("http://user\xE3\x91\x94:@foo.com", 'http://user%E3%91%94:@foo.com');
    }

    function test_validate_path_abempty() {
        $this->assertValidation("http://host/\xE3\x91\x94:", 'http://host/%E3%91%94:');
    }

    function test_validate_path_absolute() {
        $this->assertValidation("/\xE3\x91\x94:", '/%E3%91%94:');
    }

    function test_validate_path_rootless() {
        $this->assertValidation("mailto:\xE3\x91\x94:", 'mailto:%E3%91%94:');
    }

    function test_validate_path_noscheme() {
        $this->assertValidation("\xE3\x91\x94", '%E3%91%94');
    }

    function test_validate_query() {
        $this->assertValidation("?/\xE3\x91\x94", '?/%E3%91%94');
    }

    function test_validate_fragment() {
        $this->assertValidation("#/\xE3\x91\x94", '#/%E3%91%94');
    }

    function test_validate_path_empty() {
        $this->assertValidation('http://google.com');
    }

}

// vim: et sw=4 sts=4
