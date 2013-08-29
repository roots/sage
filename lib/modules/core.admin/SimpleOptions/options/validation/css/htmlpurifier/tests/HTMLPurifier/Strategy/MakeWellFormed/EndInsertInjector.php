<?php

class HTMLPurifier_Strategy_MakeWellFormed_EndInsertInjector extends HTMLPurifier_Injector
{
    public $name = 'EndInsertInjector';
    public $needed = array('span');
    public function handleEnd(&$token) {
        if ($token->name == 'div') return;
        $token = array(
            new HTMLPurifier_Token_Start('b'),
            new HTMLPurifier_Token_Text('Comment'),
            new HTMLPurifier_Token_End('b'),
            $token
        );
    }
}

// vim: et sw=4 sts=4
