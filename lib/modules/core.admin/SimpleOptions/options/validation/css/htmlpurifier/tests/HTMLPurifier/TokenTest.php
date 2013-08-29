<?php

class HTMLPurifier_TokenTest extends HTMLPurifier_Harness
{

    protected function assertTokenConstruction($name, $attr,
        $expect_name = null, $expect_attr = null
    ) {
        if ($expect_name === null) $expect_name = $name;
        if ($expect_attr === null) $expect_attr = $attr;
        $token = new HTMLPurifier_Token_Start($name, $attr);

        $this->assertIdentical($expect_name, $token->name);
        $this->assertIdentical($expect_attr, $token->attr);
    }

    function testConstruct() {

        // standard case
        $this->assertTokenConstruction('a', array('href' => 'about:blank'));

        // lowercase the tag's name
        $this->assertTokenConstruction('A', array('href' => 'about:blank'),
                                       'a');

        // lowercase attributes
        $this->assertTokenConstruction('a', array('HREF' => 'about:blank'),
                                       'a', array('href' => 'about:blank'));

    }

}

// vim: et sw=4 sts=4
