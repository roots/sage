<?php

/**
 * Special test-case for cases that can't be tested using
 * HTMLPurifier_ConfigSchema_ValidatorTestCase.
 */
class HTMLPurifier_ConfigSchema_ValidatorTest extends UnitTestCase
{
    public $validator, $interchange;

    public function setup() {
        $this->validator = new HTMLPurifier_ConfigSchema_Validator();
        $this->interchange = new HTMLPurifier_ConfigSchema_Interchange();
    }

    function testDirectiveIntegrityViolation() {
        $d = $this->makeDirective('Ns.Dir');
        $d->id = new HTMLPurifier_ConfigSchema_Interchange_Id('Ns.Dir2');
        $this->expectValidationException("Integrity violation: key 'Ns.Dir' does not match internal id 'Ns.Dir2'");
        $this->validator->validate($this->interchange);
    }

    function testDirectiveTypeNotEmpty() {
        $d = $this->makeDirective('Ns.Dir');
        $d->default = 0;
        $d->description = 'Description';

        $this->expectValidationException("Type in directive 'Ns.Dir' must not be empty");
        $this->validator->validate($this->interchange);
    }

    function testDirectiveDefaultInvalid() {
        $d = $this->makeDirective('Ns.Dir');
        $d->default = 'asdf';
        $d->type = 'int';
        $d->description = 'Description';

        $this->expectValidationException("Default in directive 'Ns.Dir' had error: Expected type int, got string");
        $this->validator->validate($this->interchange);
    }

    function testDirectiveIdIsString() {
        $d = $this->makeDirective(3);
        $d->default = 0;
        $d->type = 'int';
        $d->description = 'Description';

        $this->expectValidationException("Key in id '3' in directive '3' must be a string");
        $this->validator->validate($this->interchange);
    }

    function testDirectiveTypeAllowsNullIsBool() {
        $d = $this->makeDirective('Ns.Dir');
        $d->default = 0;
        $d->type = 'int';
        $d->description = 'Description';
        $d->typeAllowsNull = 'yes';

        $this->expectValidationException("TypeAllowsNull in directive 'Ns.Dir' must be a boolean");
        $this->validator->validate($this->interchange);
    }

    function testDirectiveValueAliasesIsArray() {
        $d = $this->makeDirective('Ns.Dir');
        $d->default = 'a';
        $d->type = 'string';
        $d->description = 'Description';
        $d->valueAliases = 2;

        $this->expectValidationException("ValueAliases in directive 'Ns.Dir' must be an array");
        $this->validator->validate($this->interchange);
    }

    function testDirectiveAllowedIsLookup() {
        $d = $this->makeDirective('Ns.Dir');
        $d->default = 'foo';
        $d->type = 'string';
        $d->description = 'Description';
        $d->allowed = array('foo' => 1);

        $this->expectValidationException("Allowed in directive 'Ns.Dir' must be a lookup array");
        $this->validator->validate($this->interchange);
    }

    // helper functions


    protected function makeDirective($key) {
        $directive = new HTMLPurifier_ConfigSchema_Interchange_Directive();
        $directive->id = new HTMLPurifier_ConfigSchema_Interchange_Id($key);
        $this->interchange->addDirective($directive);
        return $directive;
    }

    protected function expectValidationException($msg) {
        $this->expectException(new HTMLPurifier_ConfigSchema_Exception($msg));
    }

}

// vim: et sw=4 sts=4
