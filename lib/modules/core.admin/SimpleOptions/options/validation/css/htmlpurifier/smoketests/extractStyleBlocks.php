<?php

require_once 'common.php';
require_once 'HTMLPurifier/Filter/ExtractStyleBlocks.php';

// need CSSTidy location
$csstidy_location = false;
if (file_exists('../conf/test-settings.php')) include '../conf/test-settings.php';
if (file_exists('../test-settings.php')) include '../test-settings.php';

if (!$csstidy_location) {
?>
Error: <a href="http://csstidy.sourceforge.net/">CSSTidy</a> library not
found, please install and configure <code>test-settings.php</code>
accordingly.
<?php
    exit;
}

require_once $csstidy_location . 'class.csstidy.php';
require_once $csstidy_location . 'class.csstidy_print.php';

$purifier = new HTMLPurifier(array(
    'Filter.ExtractStyleBlocks' => true,
));

$html = isset($_POST['html']) ? $_POST['html'] : '';
$purified_html = $purifier->purify($html);

?><!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>Extract Style Blocks - HTML Purifier Smoketest</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php

// generate style blocks
foreach ($purifier->context->get('StyleBlocks') as $style) {
?><style type="text/css">
<!--/*--><![CDATA[/*><!--*/
<?php echo $style; ?>

/*]]>*/-->
</style>
<?php
}

?>
</head>
<body>
<h1>Extract Style Blocks</h1>
<p>
  This smoketest allows users to specify global style sheets for the
  document, allowing for interesting techniques and compact markup
  that wouldn't normally be possible, using the ExtractStyleBlocks filter.
</p>
<p>
  User submitted content:
</p>
<div style="border: 1px solid #CCC; margin: 1em; padding: 1em;">
  <?php echo $purified_html ?>
</div>
<form action="" method="post">
  <textarea cols="100" rows="20" name="html"><?php echo escapeHTML($html) ?></textarea>
  <input type="submit" value="Submit" />
</form>
</body>
</html>
<?php

// vim: et sw=4 sts=4
