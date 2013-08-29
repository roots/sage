<?php

class HTMLPurifier_URISchemeRegistryTest extends HTMLPurifier_Harness
{

    function test() {

        generate_mock_once('HTMLPurifier_URIScheme');

        $config = HTMLPurifier_Config::create(array(
            'URI.AllowedSchemes' => 'http, telnet',
            'URI.OverrideAllowedSchemes' => true
        ));
        $context = new HTMLPurifier_Context();

        $registry = new HTMLPurifier_URISchemeRegistry();
        $this->assertIsA($registry->getScheme('http', $config, $context), 'HTMLPurifier_URIScheme_http');

        $scheme_http = new HTMLPurifier_URISchemeMock();
        $scheme_telnet = new HTMLPurifier_URISchemeMock();
        $scheme_foobar = new HTMLPurifier_URISchemeMock();

        // register a new scheme
        $registry->register('telnet', $scheme_telnet);
        $this->assertIdentical($registry->getScheme('telnet', $config, $context), $scheme_telnet);

        // overload a scheme, this is FINAL (forget about defaults)
        $registry->register('http', $scheme_http);
        $this->assertIdentical($registry->getScheme('http', $config, $context), $scheme_http);

        // when we register a scheme, it's automatically allowed
        $registry->register('foobar', $scheme_foobar);
        $this->assertIdentical($registry->getScheme('foobar', $config, $context), $scheme_foobar);

        // now, test when overriding is not allowed
        $config = HTMLPurifier_Config::create(array(
            'URI.AllowedSchemes' => 'http, telnet',
            'URI.OverrideAllowedSchemes' => false
        ));
        $this->assertNull($registry->getScheme('foobar', $config, $context));

        // scheme not allowed and never registered
        $this->assertNull($registry->getScheme('ftp', $config, $context));

    }

}

// vim: et sw=4 sts=4
