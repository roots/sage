<?php

class HTMLPurifier_ConfigSchema_InterchangeTest extends UnitTestCase
{

    protected $interchange;

    public function setup() {
        $this->interchange = new HTMLPurifier_ConfigSchema_Interchange();
    }

    function testAddDirective() {
        $v = new HTMLPurifier_ConfigSchema_Interchange_Directive();
        $v->id = new HTMLPurifier_ConfigSchema_Interchange_Id('Namespace.Directive');
        $this->interchange->addDirective($v);
        $this->assertIdentical($v, $this->interchange->directives['Namespace.Directive']);
    }

}

// vim: et sw=4 sts=4
