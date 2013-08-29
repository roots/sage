<?php

class HTMLPurifier_Strategy_MakeWellFormed_SkipInjector extends HTMLPurifier_Injector
{
    public $name = 'EndRewindInjector';
    public $needed = array('span');
    public function handleElement(&$token) {
        $token = array(clone $token, clone $token);
    }
}

// vim: et sw=4 sts=4
