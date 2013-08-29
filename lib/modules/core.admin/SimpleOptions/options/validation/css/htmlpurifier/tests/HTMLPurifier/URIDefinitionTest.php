<?php

class HTMLPurifier_URIDefinitionTest extends HTMLPurifier_URIHarness
{

    protected function createFilterMock($expect = true, $result = true, $post = false, $setup = true) {
        static $i = 0;
        generate_mock_once('HTMLPurifier_URIFilter');
        $mock = new HTMLPurifier_URIFilterMock();
        if ($expect) $mock->expectOnce('filter');
        else $mock->expectNever('filter');
        $mock->setReturnValue('filter', $result);
        $mock->setReturnValue('prepare', $setup);
        $mock->name = $i++;
        $mock->post = $post;
        return $mock;
    }

    function test_filter() {
        $def = new HTMLPurifier_URIDefinition();
        $def->addFilter($this->createFilterMock(), $this->config);
        $def->addFilter($this->createFilterMock(), $this->config);
        $uri = $this->createURI('test');
        $this->assertTrue($def->filter($uri, $this->config, $this->context));
    }

    function test_filter_earlyAbortIfFail() {
        $def = new HTMLPurifier_URIDefinition();
        $def->addFilter($this->createFilterMock(true, false), $this->config);
        $def->addFilter($this->createFilterMock(false), $this->config); // never called
        $uri = $this->createURI('test');
        $this->assertFalse($def->filter($uri, $this->config, $this->context));
    }

    function test_setupMemberVariables_collisionPrecedenceIsHostBaseScheme() {
        $this->config->set('URI.Host', $host = 'example.com');
        $this->config->set('URI.Base', $base = 'http://sub.example.com/foo/bar.html');
        $this->config->set('URI.DefaultScheme', 'ftp');
        $def = new HTMLPurifier_URIDefinition();
        $def->setup($this->config);
        $this->assertIdentical($def->host, $host);
        $this->assertIdentical($def->base, $this->createURI($base));
        $this->assertIdentical($def->defaultScheme, 'http'); // not ftp!
    }

    function test_setupMemberVariables_onlyScheme() {
        $this->config->set('URI.DefaultScheme', 'ftp');
        $def = new HTMLPurifier_URIDefinition();
        $def->setup($this->config);
        $this->assertIdentical($def->defaultScheme, 'ftp');
    }

    function test_setupMemberVariables_onlyBase() {
        $this->config->set('URI.Base', 'http://sub.example.com/foo/bar.html');
        $def = new HTMLPurifier_URIDefinition();
        $def->setup($this->config);
        $this->assertIdentical($def->host, 'sub.example.com');
    }

}

// vim: et sw=4 sts=4
