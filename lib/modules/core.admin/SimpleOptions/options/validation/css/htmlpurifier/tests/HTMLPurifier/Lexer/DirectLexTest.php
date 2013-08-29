<?php

class HTMLPurifier_Lexer_DirectLexTest extends HTMLPurifier_Harness
{

    protected $DirectLex;

    function setUp() {
        $this->DirectLex = new HTMLPurifier_Lexer_DirectLex();
    }

    // internals testing
    function test_parseAttributeString() {

        $input[0] = 'href="about:blank" rel="nofollow"';
        $expect[0] = array('href'=>'about:blank', 'rel'=>'nofollow');

        $input[1] = "href='about:blank'";
        $expect[1] = array('href'=>'about:blank');

        // note that the single quotes aren't /really/ escaped
        $input[2] = 'onclick="javascript:alert(\'asdf\');"';
        $expect[2] = array('onclick' => "javascript:alert('asdf');");

        $input[3] = 'selected';
        $expect[3] = array('selected'=>'selected');

        // [INVALID]
        $input[4] = '="nokey"';
        $expect[4] = array();

        // [SIMPLE]
        $input[5] = 'color=blue';
        $expect[5] = array('color' => 'blue');

        // [INVALID]
        $input[6] = 'href="about:blank';
        $expect[6] = array('href' => 'about:blank');

        // [INVALID]
        $input[7] = '"=';
        $expect[7] = array('"' => '');
        // we ought to get array()

        $input[8] = 'href ="about:blank"rel ="nofollow"';
        $expect[8] = array('href' => 'about:blank', 'rel' => 'nofollow');

        $input[9] = 'two bool';
        $expect[9] = array('two' => 'two', 'bool' => 'bool');

        $input[10] = 'name="input" selected';
        $expect[10] = array('name' => 'input', 'selected' => 'selected');

        $input[11] = '=""';
        $expect[11] = array();

        $input[12] = '="" =""';
        $expect[12] = array('"' => ''); // tough to say, just don't throw a loop

        $input[13] = 'href="';
        $expect[13] = array('href' => '');

        $input[14] = 'href=" <';
        $expect[14] = array('href' => ' <');

        $config = HTMLPurifier_Config::createDefault();
        $context = new HTMLPurifier_Context();
        $size = count($input);
        for($i = 0; $i < $size; $i++) {
            $result = $this->DirectLex->parseAttributeString($input[$i], $config, $context);
            $this->assertIdentical($expect[$i], $result, 'Test ' . $i . ': %s');
        }

    }

    function testLineNumbers() {

        //       .  .     .     .  .     .     .           .      .             .
        //       01234567890123 01234567890123 0123456789012345 0123456789012   012345
        $html = "<b>Line 1</b>\n<i>Line 2</i>\nStill Line 2<br\n/>Now Line 4\n\n<br />";

        $expect = array(
            // line 1
            0 => new HTMLPurifier_Token_Start('b')
           ,1 => new HTMLPurifier_Token_Text('Line 1')
           ,2 => new HTMLPurifier_Token_End('b')
           ,3 => new HTMLPurifier_Token_Text("\n")
            // line 2
           ,4 => new HTMLPurifier_Token_Start('i')
           ,5 => new HTMLPurifier_Token_Text('Line 2')
           ,6 => new HTMLPurifier_Token_End('i')
           ,7 => new HTMLPurifier_Token_Text("\nStill Line 2")
            // line 3
           ,8 => new HTMLPurifier_Token_Empty('br')
            // line 4
           ,9 => new HTMLPurifier_Token_Text("Now Line 4\n\n")
            // line SIX
           ,10 => new HTMLPurifier_Token_Empty('br')
        );

        $context = new HTMLPurifier_Context();
        $config  = HTMLPurifier_Config::createDefault();
        $output = $this->DirectLex->tokenizeHTML($html, $config, $context);

        $this->assertIdentical($output, $expect);

        $context = new HTMLPurifier_Context();
        $config  = HTMLPurifier_Config::create(array(
            'Core.MaintainLineNumbers' => true
        ));
        $expect[0]->position(1, 0);
        $expect[1]->position(1, 3);
        $expect[2]->position(1, 9);
        $expect[3]->position(2, -1);
        $expect[4]->position(2, 0);
        $expect[5]->position(2, 3);
        $expect[6]->position(2, 9);
        $expect[7]->position(3, -1);
        $expect[8]->position(3, 12);
        $expect[9]->position(4, 2);
        $expect[10]->position(6, 0);

        $output = $this->DirectLex->tokenizeHTML($html, $config, $context);
        $this->assertIdentical($output, $expect);

    }

}

// vim: et sw=4 sts=4
