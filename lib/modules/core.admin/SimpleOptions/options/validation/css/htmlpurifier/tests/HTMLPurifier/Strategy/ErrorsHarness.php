<?php

class HTMLPurifier_Strategy_ErrorsHarness extends HTMLPurifier_ErrorsHarness
{

    // needs to be defined
    protected function getStrategy() {}

    protected function invoke($input) {
        $strategy = $this->getStrategy();
        $lexer = new HTMLPurifier_Lexer_DirectLex();
        $tokens = $lexer->tokenizeHTML($input, $this->config, $this->context);
        $strategy->execute($tokens, $this->config, $this->context);
    }

}

// vim: et sw=4 sts=4
