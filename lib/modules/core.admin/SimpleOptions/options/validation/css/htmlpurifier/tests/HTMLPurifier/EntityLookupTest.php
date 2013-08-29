<?php

// this page is UTF-8 encoded!

class HTMLPurifier_EntityLookupTest extends HTMLPurifier_Harness
{

    function test() {

        $lookup = HTMLPurifier_EntityLookup::instance();

        // latin char
        $this->assertIdentical('â', $lookup->table['acirc']);

        // special char
        $this->assertIdentical('"', $lookup->table['quot']);
        $this->assertIdentical('“', $lookup->table['ldquo']);
        $this->assertIdentical('<', $lookup->table['lt']); // expressed strangely in source file

        // symbol char
        $this->assertIdentical('θ', $lookup->table['theta']);

    }

}

// vim: et sw=4 sts=4
