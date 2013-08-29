<?php

class HTMLPurifier_ConfigSchemaTest extends HTMLPurifier_Harness
{

    protected $schema;

    public function setup() {
        $this->schema = new HTMLPurifier_ConfigSchema();
    }

    function test_define() {
        $this->schema->add('Car.Seats', 5, 'int', false);

        $this->assertIdentical($this->schema->defaults['Car.Seats'], 5);
        $this->assertIdentical($this->schema->info['Car.Seats']->type, HTMLPurifier_VarParser::INT);

        $this->schema->add('Car.Age', null, 'int', true);

        $this->assertIdentical($this->schema->defaults['Car.Age'], null);
        $this->assertIdentical($this->schema->info['Car.Age']->type, HTMLPurifier_VarParser::INT);

    }

    function test_defineAllowedValues() {
        $this->schema->add('QuantumNumber.Spin', 0.5, 'float', false);
        $this->schema->add('QuantumNumber.Current', 's', 'string', false);
        $this->schema->add('QuantumNumber.Difficulty', null, 'string', true);

        $this->schema->addAllowedValues( // okay, since default is null
            'QuantumNumber.Difficulty', array('easy' => true, 'medium' => true, 'hard' => true)
        );

        $this->assertIdentical($this->schema->defaults['QuantumNumber.Difficulty'], null);
        $this->assertIdentical($this->schema->info['QuantumNumber.Difficulty']->type, HTMLPurifier_VarParser::STRING);
        $this->assertIdentical($this->schema->info['QuantumNumber.Difficulty']->allow_null, true);
        $this->assertIdentical($this->schema->info['QuantumNumber.Difficulty']->allowed,
            array(
                'easy' => true,
                'medium' => true,
                'hard' => true
            )
        );

    }

    function test_defineValueAliases() {
        $this->schema->add('Abbrev.HTH', 'Happy to Help', 'string', false);
        $this->schema->addAllowedValues(
            'Abbrev.HTH', array(
                'Happy to Help' => true,
                'Hope that Helps' => true,
                'HAIL THE HAND!' => true,
            )
        );
        $this->schema->addValueAliases(
            'Abbrev.HTH', array(
                'happy' => 'Happy to Help',
                'hope' => 'Hope that Helps'
            )
        );
        $this->schema->addValueAliases( // delayed addition
            'Abbrev.HTH', array(
                'hail' => 'HAIL THE HAND!'
            )
        );

        $this->assertIdentical($this->schema->defaults['Abbrev.HTH'], 'Happy to Help');
        $this->assertIdentical($this->schema->info['Abbrev.HTH']->type, HTMLPurifier_VarParser::STRING);
        $this->assertIdentical($this->schema->info['Abbrev.HTH']->allowed,
            array(
                'Happy to Help' => true,
                'Hope that Helps' => true,
                'HAIL THE HAND!' => true
            )
        );
        $this->assertIdentical($this->schema->info['Abbrev.HTH']->aliases,
        array(
                'happy' => 'Happy to Help',
                'hope' => 'Hope that Helps',
                'hail' => 'HAIL THE HAND!'
            )
        );

    }

    function testAlias() {
        $this->schema->add('Home.Rug', 3, 'int', false);
        $this->schema->addAlias('Home.Carpet', 'Home.Rug');

        $this->assertTrue(!isset($this->schema->defaults['Home.Carpet']));
        $this->assertIdentical($this->schema->info['Home.Carpet']->key, 'Home.Rug');
        $this->assertIdentical($this->schema->info['Home.Carpet']->isAlias, true);

    }

}

// vim: et sw=4 sts=4
