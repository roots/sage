<?php

class HTMLPurifier_EncoderTest extends HTMLPurifier_Harness
{

    protected $_entity_lookup;

    function setUp() {
        $this->_entity_lookup = HTMLPurifier_EntityLookup::instance();
        parent::setUp();
    }

    function assertCleanUTF8($string, $expect = null) {
        if ($expect === null) $expect = $string;
        $this->assertIdentical(HTMLPurifier_Encoder::cleanUTF8($string), $expect, 'iconv: %s');
        $this->assertIdentical(HTMLPurifier_Encoder::cleanUTF8($string, true), $expect, 'PHP: %s');
    }

    function test_cleanUTF8() {
        $this->assertCleanUTF8('Normal string.');
        $this->assertCleanUTF8("Test\tAllowed\nControl\rCharacters");
        $this->assertCleanUTF8("null byte: \0", 'null byte: ');
        $this->assertCleanUTF8("\1\2\3\4\5\6\7", '');
        $this->assertCleanUTF8("\x7F", ''); // one byte invalid SGML char
        $this->assertCleanUTF8("\xC2\x80", ''); // two byte invalid SGML
        $this->assertCleanUTF8("\xF3\xBF\xBF\xBF"); // valid four byte
        $this->assertCleanUTF8("\xDF\xFF", ''); // malformed UTF8
        // invalid codepoints
        $this->assertCleanUTF8("\xED\xB0\x80", '');
    }

    function test_convertToUTF8_noConvert() {
        // UTF-8 means that we don't touch it
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertToUTF8("\xF6", $this->config, $this->context),
            "\xF6", // this is invalid
            'Expected identical [Binary: F6]'
        );
    }

    function test_convertToUTF8_spuriousEncoding() {
        if (!HTMLPurifier_Encoder::iconvAvailable()) return;
        $this->config->set('Core.Encoding', 'utf99');
        $this->expectError('Invalid encoding utf99');
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertToUTF8("\xF6", $this->config, $this->context),
            ''
        );
    }

    function test_convertToUTF8_iso8859_1() {
        $this->config->set('Core.Encoding', 'ISO-8859-1');
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertToUTF8("\xF6", $this->config, $this->context),
            "\xC3\xB6"
        );
    }

    function test_convertToUTF8_withoutIconv() {
        $this->config->set('Core.Encoding', 'ISO-8859-1');
        $this->config->set('Test.ForceNoIconv', true);
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertToUTF8("\xF6", $this->config, $this->context),
            "\xC3\xB6"
        );

    }

    function getZhongWen() {
        return "\xE4\xB8\xAD\xE6\x96\x87 (Chinese)";
    }

    function test_convertFromUTF8_utf8() {
        // UTF-8 means that we don't touch it
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertFromUTF8("\xC3\xB6", $this->config, $this->context),
            "\xC3\xB6"
        );
    }

    function test_convertFromUTF8_iso8859_1() {
        $this->config->set('Core.Encoding', 'ISO-8859-1');
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertFromUTF8("\xC3\xB6", $this->config, $this->context),
            "\xF6",
            'Expected identical [Binary: F6]'
        );
    }

    function test_convertFromUTF8_iconvNoChars() {
        if (!HTMLPurifier_Encoder::iconvAvailable()) return;
        $this->config->set('Core.Encoding', 'ISO-8859-1');
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertFromUTF8($this->getZhongWen(), $this->config, $this->context),
            " (Chinese)"
        );
    }

    function test_convertFromUTF8_phpNormal() {
        // Plain PHP implementation has slightly different behavior
        $this->config->set('Core.Encoding', 'ISO-8859-1');
        $this->config->set('Test.ForceNoIconv', true);
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertFromUTF8("\xC3\xB6", $this->config, $this->context),
            "\xF6",
            'Expected identical [Binary: F6]'
        );
    }

    function test_convertFromUTF8_phpNoChars() {
        $this->config->set('Core.Encoding', 'ISO-8859-1');
        $this->config->set('Test.ForceNoIconv', true);
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertFromUTF8($this->getZhongWen(), $this->config, $this->context),
            "?? (Chinese)"
        );
    }

    function test_convertFromUTF8_withProtection() {
        // Preserve the characters!
        $this->config->set('Core.Encoding', 'ISO-8859-1');
        $this->config->set('Core.EscapeNonASCIICharacters', true);
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertFromUTF8($this->getZhongWen(), $this->config, $this->context),
            "&#20013;&#25991; (Chinese)"
        );
    }

    function test_convertFromUTF8_withProtectionButUtf8() {
        // Preserve the characters!
        $this->config->set('Core.EscapeNonASCIICharacters', true);
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertFromUTF8($this->getZhongWen(), $this->config, $this->context),
            "&#20013;&#25991; (Chinese)"
        );
    }

    function test_convertToASCIIDumbLossless() {

        // Uppercase thorn letter
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertToASCIIDumbLossless("\xC3\x9Eorn"),
            "&#222;orn"
        );

        $this->assertIdentical(
            HTMLPurifier_Encoder::convertToASCIIDumbLossless("an"),
            "an"
        );

        // test up to four bytes
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertToASCIIDumbLossless("\xF3\xA0\x80\xA0"),
            "&#917536;"
        );

    }

    function assertASCIISupportCheck($enc, $ret) {
        $test = HTMLPurifier_Encoder::testEncodingSupportsASCII($enc, true);
        if ($test === false) return;
        $this->assertIdentical(
            HTMLPurifier_Encoder::testEncodingSupportsASCII($enc),
            $ret
        );
        $this->assertIdentical(
            HTMLPurifier_Encoder::testEncodingSupportsASCII($enc, true),
            $ret
        );
    }

    function test_testEncodingSupportsASCII() {
        if (HTMLPurifier_Encoder::iconvAvailable()) {
            $this->assertASCIISupportCheck('Shift_JIS', array("\xC2\xA5" => '\\', "\xE2\x80\xBE" => '~'));
            $this->assertASCIISupportCheck('JOHAB', array("\xE2\x82\xA9" => '\\'));
        }
        $this->assertASCIISupportCheck('ISO-8859-1', array());
        $this->assertASCIISupportCheck('dontexist', array()); // canary
    }

    function testShiftJIS() {
        if (!HTMLPurifier_Encoder::iconvAvailable()) return;
        $this->config->set('Core.Encoding', 'Shift_JIS');
        // This actually looks like a Yen, but we're going to treat it differently
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertFromUTF8('\\~', $this->config, $this->context),
            '\\~'
        );
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertToUTF8('\\~', $this->config, $this->context),
            '\\~'
        );
    }

    function testIconvTruncateBug() {
        if (!HTMLPurifier_Encoder::iconvAvailable()) return;
        if (HTMLPurifier_Encoder::testIconvTruncateBug() !== HTMLPurifier_Encoder::ICONV_TRUNCATES) return;
        $this->config->set('Core.Encoding', 'ISO-8859-1');
        $this->assertIdentical(
            HTMLPurifier_Encoder::convertFromUTF8("\xE4\xB8\xAD" . str_repeat('a', 10000), $this->config, $this->context),
            str_repeat('a', 10000)
        );
    }

    function testIconvChunking() {
        if (!HTMLPurifier_Encoder::iconvAvailable()) return;
        if (HTMLPurifier_Encoder::testIconvTruncateBug() !== HTMLPurifier_Encoder::ICONV_TRUNCATES) return;
        $this->assertIdentical(HTMLPurifier_Encoder::iconv('utf-8', 'iso-8859-1//IGNORE', "a\xF3\xA0\x80\xA0b", 4), 'ab');
        $this->assertIdentical(HTMLPurifier_Encoder::iconv('utf-8', 'iso-8859-1//IGNORE', "aa\xE4\xB8\xADb", 4), 'aab');
        $this->assertIdentical(HTMLPurifier_Encoder::iconv('utf-8', 'iso-8859-1//IGNORE', "aaa\xCE\xB1b", 4), 'aaab');
        $this->assertIdentical(HTMLPurifier_Encoder::iconv('utf-8', 'iso-8859-1//IGNORE', "aaaa\xF3\xA0\x80\xA0b", 4), 'aaaab');
        $this->assertIdentical(HTMLPurifier_Encoder::iconv('utf-8', 'iso-8859-1//IGNORE', "aaaa\xE4\xB8\xADb", 4), 'aaaab');
        $this->assertIdentical(HTMLPurifier_Encoder::iconv('utf-8', 'iso-8859-1//IGNORE', "aaaa\xCE\xB1b", 4), 'aaaab');
    }

}

// vim: et sw=4 sts=4
