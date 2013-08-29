<?php

class HTMLPurifier_ConfigSchema_ValidatorAtomTest extends UnitTestCase
{

    protected function expectValidationException($msg) {
        $this->expectException(new HTMLPurifier_ConfigSchema_Exception($msg));
    }

    protected function makeAtom($value) {
        $obj = new stdClass();
        $obj->property = $value;
        // Note that 'property' and 'context' are magic wildcard values
        return new HTMLPurifier_ConfigSchema_ValidatorAtom('context', $obj, 'property');
    }

    function testAssertIsString() {
        $this->makeAtom('foo')->assertIsString();
    }

    function testAssertIsStringFail() {
        $this->expectValidationException("Property in context must be a string");
        $this->makeAtom(3)->assertIsString();
    }

    function testAssertNotNull() {
        $this->makeAtom('foo')->assertNotNull();
    }

    function testAssertNotNullFail() {
        $this->expectValidationException("Property in context must not be null");
        $this->makeAtom(null)->assertNotNull();
    }

    function testAssertAlnum() {
        $this->makeAtom('foo2')->assertAlnum();
    }

    function testAssertAlnumFail() {
        $this->expectValidationException("Property in context must be alphanumeric");
        $this->makeAtom('%a')->assertAlnum();
    }

    function testAssertAlnumFailIsString() {
        $this->expectValidationException("Property in context must be a string");
        $this->makeAtom(3)->assertAlnum();
    }

    function testAssertNotEmpty() {
        $this->makeAtom('foo')->assertNotEmpty();
    }

    function testAssertNotEmptyFail() {
        $this->expectValidationException("Property in context must not be empty");
        $this->makeAtom('')->assertNotEmpty();
    }

    function testAssertIsBool() {
        $this->makeAtom(false)->assertIsBool();
    }

    function testAssertIsBoolFail() {
        $this->expectValidationException("Property in context must be a boolean");
        $this->makeAtom('0')->assertIsBool();
    }

    function testAssertIsArray() {
        $this->makeAtom(array())->assertIsArray();
    }

    function testAssertIsArrayFail() {
        $this->expectValidationException("Property in context must be an array");
        $this->makeAtom('asdf')->assertIsArray();
    }


    function testAssertIsLookup() {
        $this->makeAtom(array('foo' => true))->assertIsLookup();
    }

    function testAssertIsLookupFail() {
        $this->expectValidationException("Property in context must be a lookup array");
        $this->makeAtom(array('foo' => 4))->assertIsLookup();
    }

    function testAssertIsLookupFailIsArray() {
        $this->expectValidationException("Property in context must be an array");
        $this->makeAtom('asdf')->assertIsLookup();
    }
}

// vim: et sw=4 sts=4
