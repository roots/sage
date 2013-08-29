<?php

class HTMLPurifier_Strategy_MakeWellFormed_EndRewindInjector extends HTMLPurifier_Injector
{
    public $name = 'EndRewindInjector';
    public $needed = array('span');
    public function handleElement(&$token) {
        if (isset($token->_InjectorTest_EndRewindInjector_delete)) {
            $token = false;
        }
    }
    public function handleText(&$token) {
        $token = false;
    }
    public function handleEnd(&$token) {
        $i = null;
        if (
            $this->backward($i, $prev) &&
            $prev instanceof HTMLPurifier_Token_Start &&
            $prev->name == 'span'
        ) {
            $token = false;
            $prev->_InjectorTest_EndRewindInjector_delete = true;
            $this->rewind($i);
        }
    }
}

// vim: et sw=4 sts=4
