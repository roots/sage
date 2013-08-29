<?php

/**
 * @todo Assimilate CSSTidy into our library
 */
class HTMLPurifier_Filter_ExtractStyleBlocksTest extends HTMLPurifier_Harness
{

    // usual use case:
    function test_tokenizeHTML_extractStyleBlocks() {
        $this->config->set('Filter.ExtractStyleBlocks', true);
        $purifier = new HTMLPurifier($this->config);
        $result = $purifier->purify('<style type="text/css">.foo {text-align:center;bogus:remove-me;} body.class[foo="attr"] {text-align:right;}</style>Test<style>* {font-size:12pt;}</style>');
        $this->assertIdentical($result, 'Test');
        $this->assertIdentical($purifier->context->get('StyleBlocks'),
            array(
                ".foo {\ntext-align:center;\n}",
                "* {\nfont-size:12pt;\n}"
            )
        );
    }

    function assertExtractStyleBlocks($html, $expect = true, $styles = array()) {
        $filter = new HTMLPurifier_Filter_ExtractStyleBlocks(); // disable cleaning
        if ($expect === true) $expect = $html;
        $this->config->set('Filter.ExtractStyleBlocks.TidyImpl', false);
        $result = $filter->preFilter($html, $this->config, $this->context);
        $this->assertIdentical($result, $expect);
        $this->assertIdentical($this->context->get('StyleBlocks'), $styles);
    }

    function test_extractStyleBlocks_preserve() {
        $this->assertExtractStyleBlocks('Foobar');
    }

    function test_extractStyleBlocks_allStyle() {
        $this->assertExtractStyleBlocks('<style>foo</style>', '', array('foo'));
    }

    function test_extractStyleBlocks_multipleBlocks() {
        $this->assertExtractStyleBlocks(
          "<style>1</style><style>2</style>NOP<style>4</style>",
          "NOP",
          array('1', '2', '4')
        );
    }

    function test_extractStyleBlocks_blockWithAttributes() {
        $this->assertExtractStyleBlocks(
          '<style type="text/css">css</style>',
          '',
          array('css')
        );
    }

    function test_extractStyleBlocks_styleWithPadding() {
        $this->assertExtractStyleBlocks(
          "Alas<styled>Awesome</styled>\n<style>foo</style> Trendy!",
          "Alas<styled>Awesome</styled>\n Trendy!",
          array('foo')
        );
    }

    function assertCleanCSS($input, $expect = true) {
        $filter = new HTMLPurifier_Filter_ExtractStyleBlocks();
        if ($expect === true) $expect = $input;
        $this->normalize($input);
        $this->normalize($expect);
        $result = $filter->cleanCSS($input, $this->config, $this->context);
        $this->assertIdentical($result, $expect);
    }

    function test_cleanCSS_malformed() {
        $this->assertCleanCSS('</style>', '');
    }

    function test_cleanCSS_selector() {
        $this->assertCleanCSS("a .foo #id div.cl#foo {\nfont-weight:700;\n}");
    }

    function test_cleanCSS_angledBrackets() {
        // [Content] No longer can smuggle in angled brackets using
        // font-family; when we add support for 'content', reinstate
        // this test.
        //$this->assertCleanCSS(
        //    ".class {\nfont-family:'</style>';\n}",
        //    ".class {\nfont-family:\"\\3C /style\\3E \";\n}"
        //);
    }

    function test_cleanCSS_angledBrackets2() {
        // CSSTidy's behavior in this case is wrong, and should be fixed
        //$this->assertCleanCSS(
        //    "span[title=\"</style>\"] {\nfont-size:12pt;\n}",
        //    "span[title=\"\\3C /style\\3E \"] {\nfont-size:12pt;\n}"
        //);
    }

    function test_cleanCSS_bogus() {
        $this->assertCleanCSS("div {bogus:tree;}", "div {\n}");
    }

    /* [CONTENT]
    function test_cleanCSS_escapeCodes() {
        $this->assertCleanCSS(
            ".class {\nfont-family:\"\\3C /style\\3E \";\n}"
        );
    }

    function test_cleanCSS_noEscapeCodes() {
        $this->config->set('Filter.ExtractStyleBlocks.Escaping', false);
        $this->assertCleanCSS(
            ".class {\nfont-family:\"</style>\";\n}"
        );
    }
     */

    function test_cleanCSS_scope() {
        $this->config->set('Filter.ExtractStyleBlocks.Scope', '#foo');
        $this->assertCleanCSS(
            "p {\ntext-indent:1em;\n}",
            "#foo p {\ntext-indent:1em;\n}"
        );
    }

    function test_cleanCSS_scopeWithSelectorCommas() {
        $this->config->set('Filter.ExtractStyleBlocks.Scope', '#foo');
        $this->assertCleanCSS(
            "b, i {\ntext-decoration:underline;\n}",
            "#foo b, #foo i {\ntext-decoration:underline;\n}"
        );
    }

    function test_cleanCSS_scopeWithNaughtySelector() {
        $this->config->set('Filter.ExtractStyleBlocks.Scope', '#foo');
        $this->assertCleanCSS("  + p {\ntext-indent:1em;\n}", '');
    }

    function test_cleanCSS_scopeWithMultipleNaughtySelectors() {
        $this->config->set('Filter.ExtractStyleBlocks.Scope', '#foo');
        $this->assertCleanCSS("  ++ ++ p {\ntext-indent:1em;\n}", '');
    }

    function test_cleanCSS_scopeWithCommas() {
        $this->config->set('Filter.ExtractStyleBlocks.Scope', '#foo, .bar');
        $this->assertCleanCSS(
            "p {\ntext-indent:1em;\n}",
            "#foo p, .bar p {\ntext-indent:1em;\n}"
        );
    }

    function test_cleanCSS_scopeAllWithCommas() {
        $this->config->set('Filter.ExtractStyleBlocks.Scope', '#foo, .bar');
        $this->assertCleanCSS(
            "p, div {\ntext-indent:1em;\n}",
            "#foo p, .bar p, #foo div, .bar div {\ntext-indent:1em;\n}"
        );
    }

    function test_cleanCSS_scopeWithConflicts() {
        $this->config->set('Filter.ExtractStyleBlocks.Scope', 'p');
        $this->assertCleanCSS(
"div {
text-align:right;
}

p div {
text-align:left;
}",

"p div {
text-align:right;
}

p p div {
text-align:left;
}"
        );
    }

    function test_removeComments() {
        $this->assertCleanCSS(
"<!--
div {
text-align:right;
}
-->",
"div {
text-align:right;
}"
        );
    }

    function test_atSelector() {
        $this->assertCleanCSS(
"{
    b { text-align: center; }
}",
""
            );
    }

    function test_selectorValidation() {
        $this->assertCleanCSS(
"&, & {
text-align: center;
}",
""
        );
        $this->assertCleanCSS(
"&, b {
text-align:center;
}",
"b {
text-align:center;
}"
        );
        $this->assertCleanCSS(
"& a #foo:hover.bar   +b > i {
text-align:center;
}",
"a #foo:hover.bar + b \\3E  i {
text-align:center;
}"
        );
        $this->assertCleanCSS("doesnt-exist { text-align:center }", "");
    }

    function test_cleanCSS_caseSensitive() {
        $this->assertCleanCSS("a .foo #ID div.cl#foo {\nbackground:url(\"http://foo/BAR\");\n}");
    }

}

// vim: et sw=4 sts=4
