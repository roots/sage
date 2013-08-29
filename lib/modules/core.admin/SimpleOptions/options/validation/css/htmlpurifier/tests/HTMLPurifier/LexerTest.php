<?php

class HTMLPurifier_LexerTest extends HTMLPurifier_Harness
{

    protected $_has_pear = false;

    public function __construct() {
        parent::__construct();
        if ($GLOBALS['HTMLPurifierTest']['PH5P']) {
            require_once 'HTMLPurifier/Lexer/PH5P.php';
        }
    }

    // HTMLPurifier_Lexer::create() --------------------------------------------

    function test_create() {
        $this->config->set('Core.MaintainLineNumbers', true);
        $lexer = HTMLPurifier_Lexer::create($this->config);
        $this->assertIsA($lexer, 'HTMLPurifier_Lexer_DirectLex');
    }

    function test_create_objectLexerImpl() {
        $this->config->set('Core.LexerImpl', new HTMLPurifier_Lexer_DirectLex());
        $lexer = HTMLPurifier_Lexer::create($this->config);
        $this->assertIsA($lexer, 'HTMLPurifier_Lexer_DirectLex');
    }

    function test_create_unknownLexer() {
        $this->config->set('Core.LexerImpl', 'AsdfAsdf');
        $this->expectException(new HTMLPurifier_Exception('Cannot instantiate unrecognized Lexer type AsdfAsdf'));
        HTMLPurifier_Lexer::create($this->config);
    }

    function test_create_incompatibleLexer() {
        $this->config->set('Core.LexerImpl', 'DOMLex');
        $this->config->set('Core.MaintainLineNumbers', true);
        $this->expectException(new HTMLPurifier_Exception('Cannot use lexer that does not support line numbers with Core.MaintainLineNumbers or Core.CollectErrors (use DirectLex instead)'));
        HTMLPurifier_Lexer::create($this->config);
    }

    // HTMLPurifier_Lexer->parseData() -----------------------------------------

    function assertParseData($input, $expect = true) {
        if ($expect === true) $expect = $input;
        $lexer = new HTMLPurifier_Lexer();
        $this->assertIdentical($expect, $lexer->parseData($input));
    }

    function test_parseData_plainText() {
        $this->assertParseData('asdf');
    }

    function test_parseData_ampersandEntity() {
        $this->assertParseData('&amp;', '&');
    }

    function test_parseData_quotEntity() {
        $this->assertParseData('&quot;', '"');
    }

    function test_parseData_aposNumericEntity() {
        $this->assertParseData('&#039;', "'");
    }

    function test_parseData_aposCompactNumericEntity() {
        $this->assertParseData('&#39;', "'");
    }

    function test_parseData_adjacentAmpersandEntities() {
        $this->assertParseData('&amp;&amp;&amp;', '&&&');
    }

    function test_parseData_trailingUnescapedAmpersand() {
        $this->assertParseData('&amp;&', '&&');
    }

    function test_parseData_internalUnescapedAmpersand() {
        $this->assertParseData('Procter & Gamble');
    }

    function test_parseData_improperEntityFaultToleranceTest() {
        $this->assertParseData('&#x2D;');
    }

    // HTMLPurifier_Lexer->extractBody() ---------------------------------------

    function assertExtractBody($text, $extract = true) {
        $lexer = new HTMLPurifier_Lexer();
        $result = $lexer->extractBody($text);
        if ($extract === true) $extract = $text;
        $this->assertIdentical($extract, $result);
    }

    function test_extractBody_noBodyTags() {
        $this->assertExtractBody('<b>Bold</b>');
    }

    function test_extractBody_lowercaseBodyTags() {
        $this->assertExtractBody('<html><body><b>Bold</b></body></html>', '<b>Bold</b>');
    }

    function test_extractBody_uppercaseBodyTags() {
        $this->assertExtractBody('<HTML><BODY><B>Bold</B></BODY></HTML>', '<B>Bold</B>');
    }

    function test_extractBody_realisticUseCase() {
        $this->assertExtractBody(
'<?xml version="1.0"
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
      <title>xyz</title>
   </head>
   <body>
      <form method="post" action="whatever1">
         <div>
            <input type="text" name="username" />
            <input type="text" name="password" />
            <input type="submit" />
         </div>
      </form>
   </body>
</html>',
    '
      <form method="post" action="whatever1">
         <div>
            <input type="text" name="username" />
            <input type="text" name="password" />
            <input type="submit" />
         </div>
      </form>
   ');
    }

    function test_extractBody_bodyWithAttributes() {
        $this->assertExtractBody('<html><body bgcolor="#F00"><b>Bold</b></body></html>', '<b>Bold</b>');
    }

    function test_extractBody_preserveUnclosedBody() {
        $this->assertExtractBody('<body>asdf'); // not closed, don't accept
    }

    function test_extractBody_useLastBody() {
        $this->assertExtractBody('<body>foo</body>bar</body>', 'foo</body>bar');
    }

    // HTMLPurifier_Lexer->tokenizeHTML() --------------------------------------

    function assertTokenization($input, $expect, $alt_expect = array()) {
        $lexers = array();
        $lexers['DirectLex']  = new HTMLPurifier_Lexer_DirectLex();
        if (class_exists('DOMDocument')) {
            $lexers['DOMLex'] = new HTMLPurifier_Lexer_DOMLex();
            $lexers['PH5P']   = new HTMLPurifier_Lexer_PH5P();
        }
        foreach ($lexers as $name => $lexer) {
            $result = $lexer->tokenizeHTML($input, $this->config, $this->context);
            if (isset($alt_expect[$name])) {
                if ($alt_expect[$name] === false) continue;
                $t_expect = $alt_expect[$name];
                $this->assertIdentical($result, $alt_expect[$name], "$name: %s");
            } else {
                $t_expect = $expect;
                $this->assertIdentical($result, $expect, "$name: %s");
            }
            if ($t_expect != $result) {
                printTokens($result);
            }
        }
    }

    function test_tokenizeHTML_emptyInput() {
        $this->assertTokenization('', array());
    }

    function test_tokenizeHTML_plainText() {
        $this->assertTokenization(
            'This is regular text.',
            array(
                new HTMLPurifier_Token_Text('This is regular text.')
            )
        );
    }

    function test_tokenizeHTML_textAndTags() {
        $this->assertTokenization(
            'This is <b>bold</b> text',
            array(
                new HTMLPurifier_Token_Text('This is '),
                new HTMLPurifier_Token_Start('b', array()),
                new HTMLPurifier_Token_Text('bold'),
                new HTMLPurifier_Token_End('b'),
                new HTMLPurifier_Token_Text(' text'),
            )
        );
    }

    function test_tokenizeHTML_normalizeCase() {
        $this->assertTokenization(
            '<DIV>Totally rad dude. <b>asdf</b></div>',
            array(
                new HTMLPurifier_Token_Start('DIV', array()),
                new HTMLPurifier_Token_Text('Totally rad dude. '),
                new HTMLPurifier_Token_Start('b', array()),
                new HTMLPurifier_Token_Text('asdf'),
                new HTMLPurifier_Token_End('b'),
                new HTMLPurifier_Token_End('div'),
            )
        );
    }

    function test_tokenizeHTML_notWellFormed() {
        $this->assertTokenization(
            '<asdf></asdf><d></d><poOloka><poolasdf><ds></asdf></ASDF>',
            array(
                new HTMLPurifier_Token_Start('asdf'),
                new HTMLPurifier_Token_End('asdf'),
                new HTMLPurifier_Token_Start('d'),
                new HTMLPurifier_Token_End('d'),
                new HTMLPurifier_Token_Start('poOloka'),
                new HTMLPurifier_Token_Start('poolasdf'),
                new HTMLPurifier_Token_Start('ds'),
                new HTMLPurifier_Token_End('asdf'),
                new HTMLPurifier_Token_End('ASDF'),
            ),
            array(
                'DOMLex' => $alt = array(
                    new HTMLPurifier_Token_Empty('asdf'),
                    new HTMLPurifier_Token_Empty('d'),
                    new HTMLPurifier_Token_Start('pooloka'),
                    new HTMLPurifier_Token_Start('poolasdf'),
                    new HTMLPurifier_Token_Empty('ds'),
                    new HTMLPurifier_Token_End('poolasdf'),
                    new HTMLPurifier_Token_End('pooloka'),
                ),
                'PH5P' => $alt,
            )
        );
    }

    function test_tokenizeHTML_whitespaceInTag() {
        $this->assertTokenization(
            '<a'."\t".'href="foobar.php"'."\n".'title="foo!">Link to <b id="asdf">foobar</b></a>',
            array(
                new HTMLPurifier_Token_Start('a',array('href'=>'foobar.php','title'=>'foo!')),
                new HTMLPurifier_Token_Text('Link to '),
                new HTMLPurifier_Token_Start('b',array('id'=>'asdf')),
                new HTMLPurifier_Token_Text('foobar'),
                new HTMLPurifier_Token_End('b'),
                new HTMLPurifier_Token_End('a'),
            )
        );
    }

    function test_tokenizeHTML_singleAttribute() {
        $this->assertTokenization(
            '<br style="&amp;" />',
            array(
                new HTMLPurifier_Token_Empty('br', array('style' => '&'))
            )
        );
    }

    function test_tokenizeHTML_emptyTag() {
        $this->assertTokenization(
            '<br />',
            array( new HTMLPurifier_Token_Empty('br') )
        );
    }

    function test_tokenizeHTML_comment() {
        $this->assertTokenization(
            '<!-- Comment -->',
            array( new HTMLPurifier_Token_Comment(' Comment ') )
        );
    }

    function test_tokenizeHTML_malformedComment() {
        $this->assertTokenization(
            '<!-- not so well formed --->',
            array( new HTMLPurifier_Token_Comment(' not so well formed -') )
        );
    }

    function test_tokenizeHTML_unterminatedTag() {
        $this->assertTokenization(
            '<a href=""',
            array( new HTMLPurifier_Token_Text('<a href=""') ),
            array(
                // I like our behavior better, but it's non-standard
                'DOMLex'   => array( new HTMLPurifier_Token_Empty('a', array('href'=>'')) ),
                'PH5P' => false, // total barfing, grabs scaffolding too
            )
        );
    }

    function test_tokenizeHTML_specialEntities() {
        $this->assertTokenization(
            '&lt;b&gt;',
            array(
                new HTMLPurifier_Token_Text('<b>')
            ),
            array(
                // some parsers will separate entities out
                'PH5P' => array(
                    new HTMLPurifier_Token_Text('<'),
                    new HTMLPurifier_Token_Text('b'),
                    new HTMLPurifier_Token_Text('>'),
                ),
            )
        );
    }

    function test_tokenizeHTML_earlyQuote() {
        $this->assertTokenization(
            '<a "=>',
            array( new HTMLPurifier_Token_Empty('a') ),
            array(
                // we barf on this input
                'DirectLex' => array(
                    new HTMLPurifier_Token_Start('a', array('"' => ''))
                ),
                'PH5P' => false, // behavior varies; handle this personally
            )
        );
    }

    function test_tokenizeHTML_earlyQuote_PH5P() {
        if (!class_exists('DOMDocument')) return;
        $lexer = new HTMLPurifier_Lexer_PH5P();
        $result = $lexer->tokenizeHTML('<a "=>', $this->config, $this->context);
        if ($this->context->get('PH5PError', true)) {
            $this->assertIdentical(array(
                new HTMLPurifier_Token_Start('a', array('"' => ''))
            ), $result);
        } else {
            $this->assertIdentical(array(
                new HTMLPurifier_Token_Empty('a', array('"' => ''))
            ), $result);
        }
    }

    function test_tokenizeHTML_unescapedQuote() {
        $this->assertTokenization(
            '"',
            array( new HTMLPurifier_Token_Text('"') )
        );
    }

    function test_tokenizeHTML_escapedQuote() {
        $this->assertTokenization(
            '&quot;',
            array( new HTMLPurifier_Token_Text('"') )
        );
    }

    function test_tokenizeHTML_cdata() {
        $this->assertTokenization(
            '<![CDATA[You <b>can&#39;t</b> get me!]]>',
            array( new HTMLPurifier_Token_Text('You <b>can&#39;t</b> get me!') ),
            array(
                'PH5P' =>  array(
                    new HTMLPurifier_Token_Text('You '),
                    new HTMLPurifier_Token_Text('<'),
                    new HTMLPurifier_Token_Text('b'),
                    new HTMLPurifier_Token_Text('>'),
                    new HTMLPurifier_Token_Text('can'),
                    new HTMLPurifier_Token_Text('&'),
                    new HTMLPurifier_Token_Text('#39;t'),
                    new HTMLPurifier_Token_Text('<'),
                    new HTMLPurifier_Token_Text('/b'),
                    new HTMLPurifier_Token_Text('>'),
                    new HTMLPurifier_Token_Text(' get me!'),
                ),
            )
        );
    }

    function test_tokenizeHTML_characterEntity() {
        $this->assertTokenization(
            '&theta;',
            array( new HTMLPurifier_Token_Text("\xCE\xB8") )
        );
    }

    function test_tokenizeHTML_characterEntityInCDATA() {
        $this->assertTokenization(
            '<![CDATA[&rarr;]]>',
            array( new HTMLPurifier_Token_Text("&rarr;") ),
            array(
                'PH5P' => array(
                    new HTMLPurifier_Token_Text('&'),
                    new HTMLPurifier_Token_Text('rarr;'),
                ),
            )
        );
    }

    function test_tokenizeHTML_entityInAttribute() {
        $this->assertTokenization(
            '<a href="index.php?title=foo&amp;id=bar">Link</a>',
            array(
                new HTMLPurifier_Token_Start('a',array('href' => 'index.php?title=foo&id=bar')),
                new HTMLPurifier_Token_Text('Link'),
                new HTMLPurifier_Token_End('a'),
            )
        );
    }

    function test_tokenizeHTML_preserveUTF8() {
        $this->assertTokenization(
            "\xCE\xB8",
            array( new HTMLPurifier_Token_Text("\xCE\xB8") )
        );
    }

    function test_tokenizeHTML_specialEntityInAttribute() {
        $this->assertTokenization(
            '<br test="x &lt; 6" />',
            array( new HTMLPurifier_Token_Empty('br', array('test' => 'x < 6')) )
        );
    }

    function test_tokenizeHTML_emoticonProtection() {
        $this->assertTokenization(
            '<b>Whoa! <3 That\'s not good >.></b>',
            array(
                new HTMLPurifier_Token_Start('b'),
                new HTMLPurifier_Token_Text('Whoa! '),
                new HTMLPurifier_Token_Text('<'),
                new HTMLPurifier_Token_Text('3 That\'s not good >.>'),
                new HTMLPurifier_Token_End('b')
            ),
            array(
                // text is absorbed together
                'DOMLex' => array(
                    new HTMLPurifier_Token_Start('b'),
                    new HTMLPurifier_Token_Text('Whoa! <3 That\'s not good >.>'),
                    new HTMLPurifier_Token_End('b'),
                ),
                'PH5P' => array( // interesting grouping
                    new HTMLPurifier_Token_Start('b'),
                    new HTMLPurifier_Token_Text('Whoa! '),
                    new HTMLPurifier_Token_Text('<'),
                    new HTMLPurifier_Token_Text('3 That\'s not good >.>'),
                    new HTMLPurifier_Token_End('b'),
                ),
            )
        );
    }

    function test_tokenizeHTML_commentWithFunkyChars() {
        $this->assertTokenization(
            '<!-- This >< comment --><br />',
            array(
                new HTMLPurifier_Token_Comment(' This >< comment '),
                new HTMLPurifier_Token_Empty('br'),
            )
        );
    }

    function test_tokenizeHTML_unterminatedComment() {
        $this->assertTokenization(
            '<!-- This >< comment',
            array( new HTMLPurifier_Token_Comment(' This >< comment') ),
            array(
                'DOMLex'   => false,
                'PH5P'     => false,
            )
        );
    }

    function test_tokenizeHTML_scriptCDATAContents() {
        $this->config->set('HTML.Trusted', true);
        $this->assertTokenization(
            'Foo: <script>alert("<foo>");</script>',
            array(
                new HTMLPurifier_Token_Text('Foo: '),
                new HTMLPurifier_Token_Start('script'),
                new HTMLPurifier_Token_Text('alert("<foo>");'),
                new HTMLPurifier_Token_End('script'),
            ),
            array(
                // PH5P, for some reason, bubbles the script to <head>
                'PH5P' => false,
            )
        );
    }

    function test_tokenizeHTML_entitiesInComment() {
        $this->assertTokenization(
            '<!-- This comment < &lt; & -->',
            array( new HTMLPurifier_Token_Comment(' This comment < &lt; & ') )
        );
    }

    function test_tokenizeHTML_attributeWithSpecialCharacters() {
        $this->assertTokenization(
            '<a href="><>">',
            array( new HTMLPurifier_Token_Empty('a', array('href' => '><>')) ),
            array(
                'DirectLex' => array(
                    new HTMLPurifier_Token_Start('a', array('href' => '')),
                    new HTMLPurifier_Token_Text('<'),
                    new HTMLPurifier_Token_Text('">'),
                )
            )
        );
    }

    function test_tokenizeHTML_emptyTagWithSlashInAttribute() {
        $this->assertTokenization(
            '<param name="src" value="http://example.com/video.wmv" />',
            array( new HTMLPurifier_Token_Empty('param', array('name' => 'src', 'value' => 'http://example.com/video.wmv')) )
        );
    }

    function test_tokenizeHTML_style() {
        $extra = array(
                // PH5P doesn't seem to like style tags
                'PH5P' => false,
                // DirectLex defers to RemoveForeignElements for textification
                'DirectLex' => array(
                    new HTMLPurifier_Token_Start('style', array('type' => 'text/css')),
                    new HTMLPurifier_Token_Comment("\ndiv {}\n"),
                    new HTMLPurifier_Token_End('style'),
                ),
            );
        if (!defined('LIBXML_VERSION')) {
            // LIBXML_VERSION is missing in early versions of PHP
            // prior to 1.30 of php-src/ext/libxml/libxml.c (version-wise,
            // this translates to 5.0.x. In such cases, punt the test entirely.
            return;
        } elseif (LIBXML_VERSION < 20628) {
            // libxml's behavior is wrong prior to this version, so make
            // appropriate accomodations
            $extra['DOMLex'] = $extra['DirectLex'];
        }
        $this->assertTokenization(
'<style type="text/css"><!--
div {}
--></style>',
            array(
                new HTMLPurifier_Token_Start('style', array('type' => 'text/css')),
                new HTMLPurifier_Token_Text("\ndiv {}\n"),
                new HTMLPurifier_Token_End('style'),
            ),
            $extra
        );
    }

    function test_tokenizeHTML_tagWithAtSignAndExtraGt() {
        $alt_expect = array(
            // Technically this is invalid, but it won't be a
            // problem with invalid element removal; also, this
            // mimics Mozilla's parsing of the tag.
            new HTMLPurifier_Token_Start('a@'),
            new HTMLPurifier_Token_Text('>'),
        );
        $this->assertTokenization(
            '<a@>>',
            array(
                new HTMLPurifier_Token_Start('a'),
                new HTMLPurifier_Token_Text('>'),
                new HTMLPurifier_Token_End('a'),
            ),
            array(
                'DirectLex' => $alt_expect,
            )
        );
    }

    function test_tokenizeHTML_emoticonHeart() {
        $this->assertTokenization(
            '<br /><3<br />',
            array(
                new HTMLPurifier_Token_Empty('br'),
                new HTMLPurifier_Token_Text('<'),
                new HTMLPurifier_Token_Text('3'),
                new HTMLPurifier_Token_Empty('br'),
            ),
            array(
                'DOMLex' => array(
                    new HTMLPurifier_Token_Empty('br'),
                    new HTMLPurifier_Token_Text('<3'),
                    new HTMLPurifier_Token_Empty('br'),
                ),
            )
        );
    }

    function test_tokenizeHTML_emoticonShiftyEyes() {
        $this->assertTokenization(
            '<b><<</b>',
            array(
                new HTMLPurifier_Token_Start('b'),
                new HTMLPurifier_Token_Text('<'),
                new HTMLPurifier_Token_Text('<'),
                new HTMLPurifier_Token_End('b'),
            ),
            array(
                'DOMLex' => array(
                    new HTMLPurifier_Token_Start('b'),
                    new HTMLPurifier_Token_Text('<<'),
                    new HTMLPurifier_Token_End('b'),
                ),
            )
        );
    }

    function test_tokenizeHTML_eon1996() {
        $this->assertTokenization(
            '< <b>test</b>',
            array(
                new HTMLPurifier_Token_Text('<'),
                new HTMLPurifier_Token_Text(' '),
                new HTMLPurifier_Token_Start('b'),
                new HTMLPurifier_Token_Text('test'),
                new HTMLPurifier_Token_End('b'),
            ),
            array(
                'DOMLex' => array(
                    new HTMLPurifier_Token_Text('< '),
                    new HTMLPurifier_Token_Start('b'),
                    new HTMLPurifier_Token_Text('test'),
                    new HTMLPurifier_Token_End('b'),
                ),
            )
        );
    }

    function test_tokenizeHTML_bodyInCDATA() {
        $alt_tokens = array(
            new HTMLPurifier_Token_Text('<'),
            new HTMLPurifier_Token_Text('body'),
            new HTMLPurifier_Token_Text('>'),
            new HTMLPurifier_Token_Text('Foo'),
            new HTMLPurifier_Token_Text('<'),
            new HTMLPurifier_Token_Text('/body'),
            new HTMLPurifier_Token_Text('>'),
        );
        $this->assertTokenization(
            '<![CDATA[<body>Foo</body>]]>',
            array(
                new HTMLPurifier_Token_Text('<body>Foo</body>'),
            ),
            array(
                'PH5P' => $alt_tokens,
            )
        );
    }

    function test_tokenizeHTML_() {
        $this->assertTokenization(
            '<a><img /></a>',
            array(
                new HTMLPurifier_Token_Start('a'),
                new HTMLPurifier_Token_Empty('img'),
                new HTMLPurifier_Token_End('a'),
            )
        );
    }

    function test_tokenizeHTML_ignoreIECondComment() {
        $this->assertTokenization(
            '<!--[if IE]>foo<a>bar<!-- baz --><![endif]-->',
            array()
        );
    }

    function test_tokenizeHTML_removeProcessingInstruction() {
        $this->config->set('Core.RemoveProcessingInstructions', true);
        $this->assertTokenization(
            '<?xml blah blah ?>',
            array()
        );
    }

   function test_tokenizeHTML_removeNewline() {
        $this->config->set('Core.NormalizeNewlines', true);
        $this->assertTokenization(
            "plain\rtext\r\n",
            array(
                new HTMLPurifier_Token_Text("plain\ntext\n")
            )
        );
   }

   function test_tokenizeHTML_noRemoveNewline() {
        $this->config->set('Core.NormalizeNewlines', false);
        $this->assertTokenization(
            "plain\rtext\r\n",
            array(
                new HTMLPurifier_Token_Text("plain\rtext\r\n")
            )
        );
     }

    function test_tokenizeHTML_conditionalCommentUngreedy() {
        $this->assertTokenization(
            '<!--[if gte mso 9]>a<![endif]-->b<!--[if gte mso 9]>c<![endif]-->',
            array(
                new HTMLPurifier_Token_Text("b")
            )
        );
    }

    function test_tokenizeHTML_imgTag() {
        $start = array(
                        new HTMLPurifier_Token_Start('img',
                            array(
                                'src' => 'img_11775.jpg',
                                'alt' => '[Img #11775]',
                                'id' => 'EMBEDDED_IMG_11775',
                            )
                        )
                    );
        $this->assertTokenization(
            '<img src="img_11775.jpg" alt="[Img #11775]" id="EMBEDDED_IMG_11775" >',
            array(
                new HTMLPurifier_Token_Empty('img',
                    array(
                        'src' => 'img_11775.jpg',
                        'alt' => '[Img #11775]',
                        'id' => 'EMBEDDED_IMG_11775',
                    )
                )
            ),
            array(
                'DirectLex' => $start,
                )
        );
    }


    /*

    function test_tokenizeHTML_() {
        $this->assertTokenization(
            ,
            array(

            )
        );
    }
    */

}

// vim: et sw=4 sts=4
