<?php

/**
 * Controller for validator test-cases.
 */
class HTMLPurifier_ConfigSchema_ValidatorTestCase extends UnitTestCase
{

    protected $_path, $_parser, $_builder;
    public $validator;

    public function __construct($path) {
        $this->_path = $path;
        $this->_parser  = new HTMLPurifier_StringHashParser();
        $this->_builder = new HTMLPurifier_ConfigSchema_InterchangeBuilder();
        parent::__construct($path);
    }

    public function setup() {
        $this->validator = new HTMLPurifier_ConfigSchema_Validator();
    }

    function testValidator() {
        $hashes = $this->_parser->parseMultiFile($this->_path);
        $interchange = new HTMLPurifier_ConfigSchema_Interchange();
        $error = null;
        foreach ($hashes as $hash) {
            if (!isset($hash['ID'])) {
                if (isset($hash['ERROR'])) {
                    $this->expectException(
                        new HTMLPurifier_ConfigSchema_Exception($hash['ERROR'])
                    );
                }
                continue;
            }
            $this->_builder->build($interchange, new HTMLPurifier_StringHash($hash));
        }
        $this->validator->validate($interchange);
        $this->pass();
    }

}

// vim: et sw=4 sts=4
