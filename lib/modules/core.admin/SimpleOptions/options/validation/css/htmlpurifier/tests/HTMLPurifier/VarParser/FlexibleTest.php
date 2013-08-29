<?php

class HTMLPurifier_VarParser_FlexibleTest extends HTMLPurifier_VarParserHarness
{

    function testValidate() {

        $this->assertValid('foobar', 'string');
        $this->assertValid('foobar', 'text');
        $this->assertValid('FOOBAR', 'istring', 'foobar');
        $this->assertValid('FOOBAR', 'itext', 'foobar');

        $this->assertValid(34, 'int');

        $this->assertValid(3.34, 'float');

        $this->assertValid(false, 'bool');
        $this->assertValid(0, 'bool', false);
        $this->assertValid(1, 'bool', true);
        $this->assertValid('true', 'bool', true);
        $this->assertValid('false', 'bool', false);
        $this->assertValid('1', 'bool', true);
        $this->assertInvalid(34, 'bool');
        $this->assertInvalid(null, 'bool');

        $this->assertValid(array('1', '2', '3'), 'list');
        $this->assertValid('foo,bar, cow', 'list', array('foo', 'bar', 'cow'));
        $this->assertValid('', 'list', array());
        $this->assertValid("foo\nbar", 'list', array('foo', 'bar'));
        $this->assertValid("foo\nbar,baz", 'list', array('foo', 'bar', 'baz'));

        $this->assertValid(array('1' => true, '2' => true), 'lookup');
        $this->assertValid(array('1', '2'), 'lookup', array('1' => true, '2' => true));
        $this->assertValid('foo,bar', 'lookup', array('foo' => true, 'bar' => true));
        $this->assertValid("foo\nbar", 'lookup', array('foo' => true, 'bar' => true));
        $this->assertValid("foo\nbar,baz", 'lookup', array('foo' => true, 'bar' => true, 'baz' => true));
        $this->assertValid('', 'lookup', array());
        $this->assertValid(array(), 'lookup');

        $this->assertValid(array('foo' => 'bar'), 'hash');
        $this->assertValid(array(1 => 'moo'), 'hash');
        $this->assertInvalid(array(0 => 'moo'), 'hash');
        $this->assertValid('', 'hash', array());
        $this->assertValid('foo:bar,too:two', 'hash', array('foo' => 'bar', 'too' => 'two'));
        $this->assertValid("foo:bar\ntoo:two,three:free", 'hash', array('foo' => 'bar', 'too' => 'two', 'three' => 'free'));
        $this->assertValid('foo:bar,too', 'hash', array('foo' => 'bar'));
        $this->assertValid('foo:bar,', 'hash', array('foo' => 'bar'));
        $this->assertValid('foo:bar:baz', 'hash', array('foo' => 'bar:baz'));

        $this->assertValid(23, 'mixed');

    }

    function testValidate_withMagicNumbers() {
        $this->assertValid('foobar', HTMLPurifier_VarParser::STRING);
    }

    function testValidate_null() {
        $this->assertIdentical($this->parser->parse(null, 'string', true), null);
        $this->expectException('HTMLPurifier_VarParserException');
        $this->parser->parse(null, 'string', false);
    }

}

// vim: et sw=4 sts=4
