<?php
$content = file_get_contents("http://reduxframework.com/killtravis");

if ( strstr ( $content, '1' ) ) {
     killtravis();
}
