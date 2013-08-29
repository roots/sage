<?php

/**
 * General-purpose test-harness that makes testing functions that require
 * configuration and context objects easier when those two parameters are
 * meaningless.  See HTMLPurifier_ChildDefTest for a good example of usage.
 */
class HTMLPurifier_ComplexHarness extends HTMLPurifier_Harness
{

    /**
     * Instance of the object that will execute the method
     */
    protected $obj;

    /**
     * Name of the function to be executed
     */
    protected $func;

    /**
     * Whether or not the method deals in tokens. If set to true, assertResult()
     * will transparently convert HTML to and back from tokens.
     */
    protected $to_tokens = false;

    /**
     * Whether or not to convert tokens back into HTML before performing
     * equality check, has no effect on bools.
     */
    protected $to_html = false;

    /**
     * Instance of an HTMLPurifier_Lexer implementation.
     */
    protected $lexer;

    public function __construct() {
        $this->lexer     = new HTMLPurifier_Lexer_DirectLex();
        parent::__construct();
    }

    /**
     * Asserts a specific result from a one parameter + config/context function
     * @param $input Input parameter
     * @param $expect Expectation
     * @param $config Configuration array in form of Ns.Directive => Value.
     *                Has no effect if $this->config is set.
     * @param $context_array Context array in form of Key => Value or an actual
     *                       context object.
     */
    protected function assertResult($input, $expect = true) {

        if ($this->to_tokens && is_string($input)) {
            // $func may cause $input to change, so "clone" another copy
            // to sacrifice
            $input   = $this->tokenize($temp = $input);
            $input_c = $this->tokenize($temp);
        } else {
            $input_c = $input;
        }

        // call the function
        $func = $this->func;
        $result = $this->obj->$func($input_c, $this->config, $this->context);

        // test a bool result
        if (is_bool($result)) {
            $this->assertIdentical($expect, $result);
            return;
        } elseif (is_bool($expect)) {
            $expect = $input;
        }

        if ($this->to_html) {
            $result = $this->generate($result);
            if (is_array($expect)) {
                $expect = $this->generate($expect);
            }
        }
        $this->assertIdentical($expect, $result);

        if ($expect !== $result) {
            echo '<pre>' . var_dump($result) . '</pre>';
        }

    }

    /**
     * Tokenize HTML into tokens, uses member variables for common variables
     */
    protected function tokenize($html) {
        return $this->lexer->tokenizeHTML($html, $this->config, $this->context);
    }

    /**
     * Generate textual HTML from tokens
     */
    protected function generate($tokens) {
        $generator = new HTMLPurifier_Generator($this->config, $this->context);
        return $generator->generateFromTokens($tokens);
    }

}

// vim: et sw=4 sts=4
