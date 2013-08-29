#!/usr/bin/php
<?php

chdir(dirname(__FILE__));
require_once 'common.php';
assertCli();

/**
 * @file
 * Adds vimline to files
 */

chdir(dirname(__FILE__) . '/..');
$FS = new FSTools();

$vimline = 'vim: et sw=4 sts=4';

$files = $FS->globr('.', '*');
foreach ($files as $file) {
    if (
        !is_file($file) ||
        prefix_is('./docs/doxygen', $file) ||
        prefix_is('./library/standalone', $file) ||
        prefix_is('./docs/specimens', $file) ||
        postfix_is('.ser', $file) ||
        postfix_is('.tgz', $file) ||
        postfix_is('.patch', $file) ||
        postfix_is('.dtd', $file) ||
        postfix_is('.ent', $file) ||
        postfix_is('.png', $file) ||
        postfix_is('.ico', $file) ||
        // wontfix
        postfix_is('.vtest', $file) ||
        postfix_is('.svg', $file) ||
        postfix_is('.phpt', $file) ||
        postfix_is('VERSION', $file) ||
        postfix_is('WHATSNEW', $file) ||
        postfix_is('FOCUS', $file) ||
        postfix_is('configdoc/usage.xml', $file) ||
        postfix_is('library/HTMLPurifier.includes.php', $file) ||
        postfix_is('library/HTMLPurifier.safe-includes.php', $file) ||
        postfix_is('smoketests/xssAttacks.xml', $file) ||
        // phpt files
        postfix_is('.diff', $file) ||
        postfix_is('.exp', $file) ||
        postfix_is('.log', $file) ||
        postfix_is('.out', $file) ||

        $file == './library/HTMLPurifier/Lexer/PH5P.php' ||
        $file == './maintenance/PH5P.php'
    ) continue;
    $ext = strrchr($file, '.');
    if (
        postfix_is('README', $file) ||
        postfix_is('LICENSE', $file) ||
        postfix_is('CREDITS', $file) ||
        postfix_is('INSTALL', $file) ||
        postfix_is('NEWS', $file) ||
        postfix_is('TODO', $file) ||
        postfix_is('WYSIWYG', $file) ||
        postfix_is('Changelog', $file)
    ) $ext = '.txt';
    if (postfix_is('Doxyfile', $file)) $ext = 'Doxyfile';
    if (postfix_is('.php.in', $file)) $ext = '.php';
    $no_nl = false;
    switch ($ext) {
        case '.php':
        case '.inc':
        case '.js':
            $line = '// %s';
            break;
        case '.html':
        case '.xsl':
        case '.xml':
        case '.htc':
            $line = "<!-- %s\n-->";
            break;
        case '.htmlt':
            $no_nl = true;
            $line = '--# %s';
            break;
        case '.ini':
            $line = '; %s';
            break;
        case '.css':
            $line = '/* %s */';
            break;
        case '.bat':
            $line = 'rem %s';
            break;
        case '.txt':
        case '.utf8':
            if (
                prefix_is('./library/HTMLPurifier/ConfigSchema', $file) ||
                prefix_is('./smoketests/test-schema', $file) ||
                prefix_is('./tests/HTMLPurifier/StringHashParser', $file)
            ) {
                $no_nl = true;
                $line = '--# %s';
            } else {
                $line = '    %s';
            }
            break;
        case 'Doxyfile':
            $line = '# %s';
            break;
        default:
            throw new Exception('Unknown file: ' . $file);
    }

    echo "$file\n";
    $contents = file_get_contents($file);

    $regex = '~' . str_replace('%s', 'vim: .+', preg_quote($line, '~')) .  '~m';
    $contents = preg_replace($regex, '', $contents);

    $contents = rtrim($contents);

    if (strpos($contents, "\r\n") !== false) $nl = "\r\n";
    elseif (strpos($contents, "\n") !== false) $nl = "\n";
    elseif (strpos($contents, "\r") !== false) $nl = "\r";
    else $nl = PHP_EOL;

    if (!$no_nl) $contents .= $nl;
    $contents .= $nl . str_replace('%s', $vimline, $line) . $nl;

    file_put_contents($file, $contents);

}

// vim: et sw=4 sts=4
