<?php

require_once 'common.php';

echo '<?xml version="1.0" encoding="UTF-8" ?>';
?><!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>HTML Purifier Preserve YouTube Smoketest</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<h1>HTML Purifier Preserve YouTube Smoketest</h1>
<?php

$string = '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/BdU--T8rLns"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/BdU--T8rLns" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>

<object width="416" height="337"><param name="movie" value="http://www.youtube.com/cp/vjVQa1PpcFNbP_fag8PvopkXZyiXyT0J8U47lw7x5Fc="></param><embed src="http://www.youtube.com/cp/vjVQa1PpcFNbP_fag8PvopkXZyiXyT0J8U47lw7x5Fc=" type="application/x-shockwave-flash" width="416" height="337"></embed></object>

<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/uNxBeJNyAqA&hl=en_US&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/uNxBeJNyAqA&hl=en_US&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="385"></embed></object>

<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" height="385" width="480"><param name="width" value="480" /><param name="height" value="385" /><param name="src" value="http://www.youtube.com/p/E37ADDDFCA0FD050&amp;hl=en" /><embed height="385" src="http://www.youtube.com/p/E37ADDDFCA0FD050&amp;hl=en" type="application/x-shockwave-flash" width="480"></embed></object>

<object
    classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
    id="ooyalaPlayer_229z0_gbps1mrs" width="630" height="354"
    codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab"><param
    name="movie" value="http://player.ooyala.com/player.swf?embedCode=FpZnZwMTo1wqBF-ed2__OUBb3V4HR6za&version=2"
    /><param name="bgcolor" value="#000000" /><param
    name="allowScriptAccess" value="always" /><param
    name="allowFullScreen" value="true" /><param name="flashvars"
    value="embedType=noscriptObjectTag&embedCode=pteGRrMTpcKMyQ052c8NwYZ5M5FdSV3j"
    /><embed src="http://player.ooyala.com/player.swf?embedCode=FpZnZwMTo1wqBF-ed2__OUBb3V4HR6za&version=2"
    bgcolor="#000000" width="630" height="354"
    name="ooyalaPlayer_229z0_gbps1mrs" align="middle" play="true"
    loop="false" allowscriptaccess="always" allowfullscreen="true"
    type="application/x-shockwave-flash"
    flashvars="&embedCode=FpZnZwMTo1wqBF-ed2__OUBb3V4HR6za"
    pluginspage="http://www.adobe.com/go/getflashplayer"></embed></object>
';

$regular_purifier = new HTMLPurifier();

$safeobject_purifier = new HTMLPurifier(array(
    'HTML.SafeObject' => true,
    'Output.FlashCompat' => true,
));

?>
<h2>Unpurified</h2>
<p><a href="?break">Click here to see the unpurified version (breaks validation).</a></p>
<div><?php
if (isset($_GET['break'])) echo $string;
?></div>

<h2>Without YouTube exception</h2>
<div><?php
echo $regular_purifier->purify($string);
?></div>

<h2>With SafeObject exception and flash compatibility</h2>
<div><?php
echo $safeobject_purifier->purify($string);
?></div>

</body>
</html>
<?php

// vim: et sw=4 sts=4
