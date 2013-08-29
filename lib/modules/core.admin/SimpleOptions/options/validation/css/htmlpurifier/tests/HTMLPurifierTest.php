<?php

class HTMLPurifierTest extends HTMLPurifier_Harness
{
    protected $purifier;

    function testNull() {
        $this->assertPurification("Null byte\0", "Null byte");
    }

    function test_purifyArray() {

        $this->assertIdentical(
            $this->purifier->purifyArray(
                array('Good', '<b>Sketchy', 'foo' => '<script>bad</script>')
            ),
            array('Good', '<b>Sketchy</b>', 'foo' => '')
        );

        $this->assertIsA($this->purifier->context, 'array');

    }

    function testGetInstance() {
        $purifier  = HTMLPurifier::getInstance();
        $purifier2 = HTMLPurifier::getInstance();
        $this->assertReference($purifier, $purifier2);
    }

    function testMakeAbsolute() {
        $this->config->set('URI.Base', 'http://example.com/bar/baz.php');
        $this->config->set('URI.MakeAbsolute', true);
        $this->assertPurification(
            '<a href="foo.txt">Foobar</a>',
            '<a href="http://example.com/bar/foo.txt">Foobar</a>'
        );
    }

    function testDisableResources() {
        $this->config->set('URI.DisableResources', true);
        $this->assertPurification('<img src="foo.jpg" />', '');
    }

    function test_addFilter_deprecated() {
        $this->expectError('HTMLPurifier->addFilter() is deprecated, use configuration directives in the Filter namespace or Filter.Custom');
        generate_mock_once('HTMLPurifier_Filter');
        $this->purifier->addFilter($mock = new HTMLPurifier_FilterMock());
        $mock->expectOnce('preFilter');
        $mock->expectOnce('postFilter');
        $this->purifier->purify('foo');
    }

}

// vim: et sw=4 sts=4
