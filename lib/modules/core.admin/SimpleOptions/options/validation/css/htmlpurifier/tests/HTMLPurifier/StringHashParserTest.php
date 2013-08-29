<?php

/**
 * @note Sample input files are located in the StringHashParser/ directory.
 */
class HTMLPurifier_StringHashParserTest extends UnitTestCase
{

    /**
     * Instance of ConfigSchema_StringHashParser being tested.
     */
    protected $parser;

    public function setup() {
        $this->parser = new HTMLPurifier_StringHashParser();
    }

    /**
     * Assert that $file gets parsed into the form of $expect
     */
    protected function assertParse($file, $expect) {
        $result = $this->parser->parseFile(dirname(__FILE__) . '/StringHashParser/' . $file);
        $this->assertIdentical($result, $expect);
    }

    function testSimple() {
        $this->assertParse('Simple.txt', array(
            'ID' => 'Namespace.Directive',
            'TYPE' => 'string',
            'CHAIN-ME' => '2',
            'DESCRIPTION' => "Multiline\nstuff\n",
            'EMPTY' => '',
            'FOR-WHO' => "Single multiline\n",
        ));
    }

    function testOverrideSingle() {
        $this->assertParse('OverrideSingle.txt', array(
            'KEY' => 'New',
        ));
    }

    function testAppendMultiline() {
        $this->assertParse('AppendMultiline.txt', array(
            'KEY' => "Line1\nLine2\n",
        ));
    }

    function testDefault() {
        $this->parser->default = 'NEW-ID';
        $this->assertParse('Default.txt', array(
            'NEW-ID' => 'DefaultValue',
        ));
    }

    function testError() {
        try {
            $this->parser->parseFile('NoExist.txt');
        } catch (HTMLPurifier_ConfigSchema_Exception $e) {
            $this->assertIdentical($e->getMessage(), 'File NoExist.txt does not exist');
        }
    }

    function testParseMultiple() {
        $result = $this->parser->parseMultiFile(dirname(__FILE__) . '/StringHashParser/Multi.txt');
        $this->assertIdentical(
            $result,
            array(
                array(
                    'ID' => 'Namespace.Directive',
                    'TYPE' => 'string',
                    'CHAIN-ME' => '2',
                    'DESCRIPTION' => "Multiline\nstuff\n",
                    'FOR-WHO' => "Single multiline\n",
                ),
                array(
                    'ID' => 'Namespace.Directive2',
                    'TYPE' => 'integer',
                    'CHAIN-ME' => '3',
                    'DESCRIPTION' => "M\nstuff\n",
                    'FOR-WHO' => "Single multiline2\n",
                )
            )
        );
    }

}

// vim: et sw=4 sts=4
