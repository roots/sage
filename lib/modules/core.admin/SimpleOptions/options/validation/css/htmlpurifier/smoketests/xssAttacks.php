<?php

require_once('common.php');

function formatCode($string) {
    return
        str_replace(
            array("\t", '»', '\0(null)'),
            array('<strong>\t</strong>', '<span class="linebreak">»</span>', '<strong>\0</strong>'),
            escapeHTML(
                str_replace("\0", '\0(null)',
                    wordwrap($string, 28, " »\n", true)
                )
            )
        );
}

?><!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>HTML Purifier XSS Attacks Smoketest</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css">
        .scroll {overflow:auto; width:100%;}
        .even {background:#EAEAEA;}
        thead th {border-bottom:1px solid #000;}
        pre strong {color:#00C;}
        pre .linebreak {color:#AAA;font-weight:100;}
    </style>
</head>
<body>
<h1>HTML Purifier XSS Attacks Smoketest</h1>
<p>XSS attacks are from
<a href="http://ha.ckers.org/xss.html">http://ha.ckers.org/xss.html</a>.</p>
<p><strong>Caveats:</strong>
<tt>Google.com</tt> has been programatically disallowed, but as you can
see, there are ways of getting around that, so coverage in this area
is not complete. Most XSS broadcasts its presence by spawning an alert dialogue.
The displayed code is not strictly correct, as linebreaks have been forced for
readability. Linewraps have been marked with <tt>»</tt>.  Some tests are
omitted for your convenience. Not all control characters are displayed.</p>

<h2>Test</h2>
<?php

if (version_compare(PHP_VERSION, '5', '<')) exit('<p>Requires PHP 5.</p>');

$xml = simplexml_load_file('xssAttacks.xml');

// programatically disallow google.com for URI evasion tests
// not complete
$config = HTMLPurifier_Config::createDefault();
$config->set('URI.HostBlacklist', array('google.com'));
$purifier = new HTMLPurifier($config);

?>
<table cellspacing="0" cellpadding="2">
<thead><tr><th>Name</th><th width="30%">Raw</th><th>Output</th><th>Render</th></tr></thead>
<tbody>
<?php

$i = 0;
foreach ($xml->attack as $attack) {
    $code = $attack->code;

    // custom code for null byte injection tests
    if (substr($code, 0, 7) == 'perl -e') {
        $code = substr($code, $i=strpos($code, '"')+1, strrpos($code, '"') - $i);
        $code = str_replace('\0', "\0", $code);
    }

    // disable vectors we cannot test in any meaningful way
    if ($code == 'See Below') continue; // event handlers, whitelist defeats
    if ($attack->name == 'OBJECT w/Flash 2') continue; // requires ActionScript
    if ($attack->name == 'IMG Embedded commands 2') continue; // is an HTTP response

    // custom code for US-ASCII, which couldn't be expressed in XML without encoding
    if ($attack->name == 'US-ASCII encoding') $code = urldecode($code);
?>
    <tr<?php if ($i++ % 2) {echo ' class="even"';} ?>>
        <td><?php echo escapeHTML($attack->name); ?></td>
        <td><pre><?php echo formatCode($code); ?></pre></td>
        <?php $pure_html = $purifier->purify($code); ?>
        <td><pre><?php echo formatCode($pure_html); ?></pre></td>
        <td><div class="scroll"><?php echo $pure_html ?></div></td>
    </tr>
<?php
}

?>
</tbody>
</table>
</body>
</html>
<?php

// vim: et sw=4 sts=4
