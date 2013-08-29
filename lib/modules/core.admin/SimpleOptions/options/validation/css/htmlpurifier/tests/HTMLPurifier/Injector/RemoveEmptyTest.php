<?php

class HTMLPurifier_Injector_RemoveEmptyTest extends HTMLPurifier_InjectorHarness
{

    public function setup() {
        parent::setup();
        $this->config->set('AutoFormat.RemoveEmpty', true);
    }

    function testPreserve() {
        $this->assertResult('<b>asdf</b>');
    }

    function testRemove() {
        $this->assertResult('<b></b>', '');
    }

    function testRemoveWithSpace() {
        $this->assertResult('<b>   </b>', '');
    }

    function testRemoveWithAttr() {
        $this->assertResult('<b class="asdf"></b>', '');
    }

    function testRemoveIdAndName() {
        $this->assertResult('<a id="asdf" name="asdf"></a>', '');
    }

    function testPreserveColgroup() {
        $this->assertResult('<colgroup></colgroup>');
    }

    function testPreserveId() {
        $this->config->set('Attr.EnableID', true);
        $this->assertResult('<a id="asdf"></a>');
    }

    function testPreserveName() {
        $this->config->set('Attr.EnableID', true);
        $this->assertResult('<a name="asdf"></a>');
    }

    function testRemoveNested() {
        $this->assertResult('<b><i></i></b>', '');
    }

    function testRemoveNested2() {
        $this->assertResult('<b><i><u></u></i></b>', '');
    }

    function testRemoveNested3() {
        $this->assertResult('<b> <i> <u> </u> </i> </b>', '');
    }

    function testRemoveNbsp() {
        $this->config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        $this->assertResult('<b>&nbsp;</b>', '');
    }

    function testRemoveNbspMix() {
        $this->config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        $this->assertResult('<b>&nbsp;   &nbsp;</b>', '');
    }

    function testDontRemoveNbsp() {
        $this->config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        $this->assertResult('<td>&nbsp;</b>', "<td>\xC2\xA0</td>");
    }

    function testRemoveNbspExceptionsSpecial() {
        $this->config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        $this->config->set('AutoFormat.RemoveEmpty.RemoveNbsp.Exceptions', 'b');
        $this->assertResult('<b>&nbsp;</b>', "<b>\xC2\xA0</b>");
    }

}

// vim: et sw=4 sts=4
