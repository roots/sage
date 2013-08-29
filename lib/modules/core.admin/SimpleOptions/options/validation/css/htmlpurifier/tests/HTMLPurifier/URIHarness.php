<?php

class HTMLPurifier_URIHarness extends HTMLPurifier_Harness
{

    /**
     * Prepares two URIs into object form
     * @param &$uri Reference to string input URI
     * @param &$expect_uri Reference to string expectation URI
     * @note If $expect_uri is false, it will stay false
     */
    protected function prepareURI(&$uri, &$expect_uri) {
        $parser = new HTMLPurifier_URIParser();
        if ($expect_uri === true) $expect_uri = $uri;
        $uri = $parser->parse($uri);
        if ($expect_uri !== false) {
            $expect_uri = $parser->parse($expect_uri);
        }
    }

    /**
     * Generates a URI object from the corresponding string
     */
    protected function createURI($uri) {
        $parser = new HTMLPurifier_URIParser();
        return $parser->parse($uri);
    }

}

// vim: et sw=4 sts=4
