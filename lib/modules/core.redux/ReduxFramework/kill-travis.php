<?php
/**
 * KillTravis should be run early in Travis processing.
 * Checks for a file on remote server, if the file is found
 * it kills the process.
 *
 * @package     ReduxFramework\KillTravis
 * @author      Daniel J Griffiths <ghost1227@reduxframework.com>
 * @since       2.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    exit;
}


// Check for remote file
$content = file_get_contents("http://reduxframework.com/killtravis");


// Kill if the file is found
if ( strstr ( $content, '1' ) ) {
    killtravis();
}
