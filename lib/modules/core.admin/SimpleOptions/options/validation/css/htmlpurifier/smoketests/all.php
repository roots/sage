<?php

require_once 'common.php';

header('Content-type: text/html; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8" ?>';

?><!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>HTML Purifier: All Smoketests</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css">
        #content {margin:5em;}
        iframe {width:100%;height:30em;}
    </style>
</head>
<body>
<h1>HTML Purifier: All Smoketests</h1>
<div id="content">
<?php

$dir = './';
$dh  = opendir($dir);
while (false !== ($filename = readdir($dh))) {
    if ($filename[0] == '.') continue;
    if (strpos($filename, '.php') === false) continue;
    if ($filename == 'common.php') continue;
    if ($filename == 'all.php') continue;
    if ($filename == 'testSchema.php') continue;
    ?>
    <iframe src="<?php echo escapeHTML($filename); if (isset($_GET['standalone'])) {echo '?standalone';} ?>"></iframe>
    <?php
}

?>
</div>
</body>
</html>
<?php

// vim: et sw=4 sts=4
