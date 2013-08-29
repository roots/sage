<?php

class HTMLPurifier_AttrDef_LangTest extends HTMLPurifier_AttrDefHarness
{

    function test() {

        $this->def = new HTMLPurifier_AttrDef_Lang();

        // basic good uses
        $this->assertDef('en');
        $this->assertDef('en-us');

        $this->assertDef(' en ', 'en'); // trim
        $this->assertDef('EN', 'en'); // case insensitivity

        // (thanks Eugen Pankratz for noticing the typos!)
        $this->assertDef('En-Us-Edison', 'en-us-edison'); // complex ci

        $this->assertDef('fr en', false); // multiple languages
        $this->assertDef('%', false); // bad character

        // test overlong language according to syntax
        $this->assertDef('thisistoolongsoitgetscut', false);

        // primary subtag rules
            // I'm somewhat hesitant to allow x and i as primary language codes,
            // because they usually are never used in real life. However,
            // theoretically speaking, having them alone is permissable, so
            // I'll be lenient. No XML parser is going to complain anyway.
        $this->assertDef('x');
        $this->assertDef('i');
            // real world use-cases
        $this->assertDef('x-klingon');
        $this->assertDef('i-mingo');
            // because the RFC only defines two and three letter primary codes,
            // anything with a length of four or greater is invalid, despite
            // the syntax stipulation of 1 to 8 characters. Because the RFC
            // specifically states that this reservation is in order to allow
            // for future versions to expand, the adoption of a new RFC will
            // require these test cases to be rewritten, even if backwards-
            // compatibility is largely retained (i.e. this is not forwards
            // compatible)
        $this->assertDef('four', false);
            // for similar reasons, disallow any other one character language
        $this->assertDef('f', false);

        // second subtag rules
            // one letter subtags prohibited until revision. This is, however,
            // less volatile than the restrictions on the primary subtags.
            // Also note that this test-case tests fix-behavior: chop
            // off subtags until you get a valid language code.
        $this->assertDef('en-a', 'en');
            // however, x is a reserved single-letter subtag that is allowed
        $this->assertDef('en-x', 'en-x');
            // 2-8 chars are permitted, but have special meaning that cannot
            // be checked without maintaining country code lookup tables (for
            // two characters) or special registration tables (for all above).
        $this->assertDef('en-uk', true);

        // further subtag rules: only syntactic constraints
        $this->assertDef('en-us-edison');
        $this->assertDef('en-us-toolonghaha', 'en-us');
        $this->assertDef('en-us-a-silly-long-one');

        // rfc 3066 stipulates that if a three letter and a two letter code
        // are available, the two letter one MUST be used. Without a language
        // code lookup table, we cannot implement this functionality.

        // although the HTML protocol, technically speaking, allows you to
        // omit language tags, this implicitly means that the parent element's
        // language is the one applicable, which, in some cases, is incorrect.
        // Thus, we allow und, only slightly defying the RFC's SHOULD NOT
        // designation.
        $this->assertDef('und');

        // because attributes only allow one language, mul is allowed, complying
        // with the RFC's SHOULD NOT designation.
        $this->assertDef('mul');

    }

}

// vim: et sw=4 sts=4
