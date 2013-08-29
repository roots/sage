<?php

class HTMLPurifier_Injector_PurifierLinkifyTest extends HTMLPurifier_InjectorHarness
{

    function setup() {
        parent::setup();
        $this->config->set('AutoFormat.PurifierLinkify', true);
        $this->config->set('AutoFormat.PurifierLinkify.DocURL', '#%s');
    }

    function testNoTriggerCharacer() {
        $this->assertResult('Foobar');
    }

    function testTriggerCharacterInIrrelevantContext() {
        $this->assertResult('20% off!');
    }

    function testPreserveNamespace() {
        $this->assertResult('%Core namespace (not recognized)');
    }

    function testLinkifyBasic() {
        $this->assertResult(
          '%Namespace.Directive',
          '<a href="#Namespace.Directive">%Namespace.Directive</a>'
        );
    }

    function testLinkifyWithAdjacentTextNodes() {
        $this->assertResult(
          'This %Namespace.Directive thing',
          'This <a href="#Namespace.Directive">%Namespace.Directive</a> thing'
        );
    }

    function testLinkifyInBlock() {
        $this->assertResult(
          '<div>This %Namespace.Directive thing</div>',
          '<div>This <a href="#Namespace.Directive">%Namespace.Directive</a> thing</div>'
        );
    }

    function testPreserveInATag() {
        $this->assertResult(
          '<a>%Namespace.Directive</a>'
        );
    }

    function testNeeded() {
        $this->config->set('HTML.Allowed', 'b');
        $this->expectError('Cannot enable PurifierLinkify injector because a is not allowed');
        $this->assertResult('%Namespace.Directive');
    }

}

// vim: et sw=4 sts=4
