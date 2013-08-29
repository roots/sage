<?php

class HTMLPurifier_EntityParserTest extends HTMLPurifier_Harness
{

    protected $EntityParser;

    public function setUp() {
        $this->EntityParser = new HTMLPurifier_EntityParser();
        $this->_entity_lookup = HTMLPurifier_EntityLookup::instance();
    }

    function test_substituteNonSpecialEntities() {
        $char_theta = $this->_entity_lookup->table['theta'];
        $this->assertIdentical($char_theta,
            $this->EntityParser->substituteNonSpecialEntities('&theta;') );
        $this->assertIdentical('"',
            $this->EntityParser->substituteNonSpecialEntities('"') );

        // numeric tests, adapted from Feyd
        $args = array();
        $args[] = array(1114112,false     );
        $args[] = array(1114111,'F48FBFBF'); // 0x0010FFFF
        $args[] = array(1048576,'F4808080'); // 0x00100000
        $args[] = array(1048575,'F3BFBFBF'); // 0x000FFFFF
        $args[] = array(262144, 'F1808080'); // 0x00040000
        $args[] = array(262143, 'F0BFBFBF'); // 0x0003FFFF
        $args[] = array(65536,  'F0908080'); // 0x00010000
        $args[] = array(65535,  'EFBFBF'  ); // 0x0000FFFF
        $args[] = array(57344,  'EE8080'  ); // 0x0000E000
        $args[] = array(57343,  false     ); // 0x0000DFFF  these are ill-formed
        $args[] = array(56040,  false     ); // 0x0000DAE8  these are ill-formed
        $args[] = array(55296,  false     ); // 0x0000D800  these are ill-formed
        $args[] = array(55295,  'ED9FBF'  ); // 0x0000D7FF
        $args[] = array(53248,  'ED8080'  ); // 0x0000D000
        $args[] = array(53247,  'ECBFBF'  ); // 0x0000CFFF
        $args[] = array(4096,   'E18080'  ); // 0x00001000
        $args[] = array(4095,   'E0BFBF'  ); // 0x00000FFF
        $args[] = array(2048,   'E0A080'  ); // 0x00000800
        $args[] = array(2047,   'DFBF'    ); // 0x000007FF
        $args[] = array(128,    'C280'    ); // 0x00000080  invalid SGML char
        $args[] = array(127,    '7F'      ); // 0x0000007F  invalid SGML char
        $args[] = array(0,      '00'      ); // 0x00000000  invalid SGML char

        $args[] = array(20108,  'E4BA8C'  ); // 0x00004E8C
        $args[] = array(77,     '4D'      ); // 0x0000004D
        $args[] = array(66306,  'F0908C82'); // 0x00010302
        $args[] = array(1072,   'D0B0'    ); // 0x00000430

        foreach ($args as $arg) {
            $string = '&#' . $arg[0] . ';' . // decimal
                      '&#x' . dechex($arg[0]) . ';'; // hex
            $expect = '';
            if ($arg[1] !== false) {
                // this is only for PHP 5, the below is PHP 5 and PHP 4
                //$chars = str_split($arg[1], 2);
                $chars = array();
                // strlen must be called in loop because strings size changes
                for ($i = 0; strlen($arg[1]) > $i; $i += 2) {
                    $chars[] = $arg[1][$i] . $arg[1][$i+1];
                }
                foreach ($chars as $char) {
                    $expect .= chr(hexdec($char));
                }
                $expect .= $expect; // double it
            }
            $this->assertIdentical(
                $this->EntityParser->substituteNonSpecialEntities($string),
                $expect,
                'Identical expectation [Hex: '. dechex($arg[0]) .']'
            );
        }

    }

    function test_substituteSpecialEntities() {
        $this->assertIdentical(
            "'",
            $this->EntityParser->substituteSpecialEntities('&#39;')
        );
    }

}

// vim: et sw=4 sts=4
