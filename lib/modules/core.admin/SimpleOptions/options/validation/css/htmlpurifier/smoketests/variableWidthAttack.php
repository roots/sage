<?php

require_once 'common.php';

echo '<?xml version="1.0" encoding="UTF-8" ?>';
?><!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>HTML Purifier Variable Width Attack Smoketest</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<h1>HTML Purifier Variable Width Attack Smoketest</h1>
<p>For more information, see
<a href="http://applesoup.googlepages.com/bypass_filter.txt">Cheng Peng Su's
original advisory.</a>  This particular exploit code appears only to work
in Internet Explorer, if it works at all.</p>
<h2>Test</h2>
<?php

$purifier = new HTMLPurifier();

?>
<table>
<thead><tr><th>ASCII</th><th width="30%">Raw</th><th>Output</th><th>Render</th></tr></thead>
<tbody>
<?php

for ($i = 0; $i < 256; $i++) {
    $c = chr($i);
    $html = '<img src="" alt="X' . $c . '"';
    $html .= '>A"'; // in our out the attribute? ;-)
    $html .= "onerror=alert('$i')>O";
    $pure_html = $purifier->purify($html);
?>
<tr>
    <td><?php echo $i; ?></td>
    <td style="font-size:8pt;"><?php echo escapeHTML($html); ?></td>
    <td style="font-size:8pt;"><?php echo escapeHTML($pure_html); ?></td>
    <td><?php echo $pure_html; ?></td>
</tr>
<?php } ?>
</tbody>
</table>

<h2>Analysis</h2>

<p>By making sure that UTF-8 is well formed and non-SGML codepoints are
removed, as well as escaping quotes outside of tags, this is a non-threat.</p>

</body>
</html>
<?php

// vim: et sw=4 sts=4
