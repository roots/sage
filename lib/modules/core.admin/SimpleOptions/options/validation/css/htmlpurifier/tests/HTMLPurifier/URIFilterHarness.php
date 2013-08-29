<?php

class HTMLPurifier_URIFilterHarness extends HTMLPurifier_URIHarness
{

    protected function assertFiltering($uri, $expect_uri = true) {
        $this->prepareURI($uri, $expect_uri);
        $this->filter->prepare($this->config, $this->context);
        $result = $this->filter->filter($uri, $this->config, $this->context);
        $this->assertEitherFailOrIdentical($result, $uri, $expect_uri);
    }

}

// vim: et sw=4 sts=4
