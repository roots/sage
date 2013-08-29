<?php

class HTMLPurifier_PercentEncoderTest extends HTMLPurifier_Harness
{

    protected $PercentEncoder;
    protected $func;

    function setUp() {
        $this->PercentEncoder = new HTMLPurifier_PercentEncoder();
        $this->func = '';
    }

    function assertDecode($string, $expect = true) {
        if ($expect === true) $expect = $string;
        $this->assertIdentical($this->PercentEncoder->{$this->func}($string), $expect);
    }

    function test_normalize() {
        $this->func = 'normalize';

        $this->assertDecode('Aw.../-$^8'); // no change
        $this->assertDecode('%41%77%7E%2D%2E%5F', 'Aw~-._'); // decode unreserved chars
        $this->assertDecode('%3A%2F%3F%23%5B%5D%40%21%24%26%27%28%29%2A%2B%2C%3B%3D'); // preserve reserved chars
        $this->assertDecode('%2b', '%2B'); // normalize to uppercase
        $this->assertDecode('%2B2B%3A3A'); // extra text
        $this->assertDecode('%2b2B%4141', '%2B2BA41'); // extra text, with normalization
        $this->assertDecode('%', '%25'); // normalize stray percent sign
        $this->assertDecode('%5%25', '%255%25'); // permaturely terminated encoding
        $this->assertDecode('%GJ', '%25GJ'); // invalid hexadecimal chars

        // contested behavior, if this changes, we'll also have to have
        // outbound encoding
        $this->assertDecode('%FC'); // not reserved or unreserved, preserve

    }

    function assertEncode($string, $expect = true, $preserve = false) {
        if ($expect === true) $expect = $string;
        $encoder = new HTMLPurifier_PercentEncoder($preserve);
        $result = $encoder->encode($string);
        $this->assertIdentical($result, $expect);
    }

    function test_encode_noChange() {
        $this->assertEncode('abc012-_~.');
    }

    function test_encode_encode() {
        $this->assertEncode('>', '%3E');
    }

    function test_encode_preserve() {
        $this->assertEncode('<>', '<%3E', '<');
    }

    function test_encode_low() {
        $this->assertEncode("\1", '%01');
    }

}

// vim: et sw=4 sts=4
