<?php

function path2class($path) {
    $temp = $path;
    $temp = str_replace('./', '',  $temp); // remove leading './'
    $temp = str_replace('.\\', '',  $temp); // remove leading '.\'
    $temp = str_replace('\\', '_', $temp); // normalize \ to _
    $temp = str_replace('/',  '_', $temp); // normalize / to _
    while(strpos($temp, '__') !== false) $temp = str_replace('__', '_', $temp);
    $temp = str_replace('.php', '', $temp);
    return $temp;
}

// vim: et sw=4 sts=4
