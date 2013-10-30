<?php
/**
 * Custom functions
 */

// For safety, replace the detailed information during the login
add_filter('login_errors', create_function('$a', "return __('Error! Try to go again.', 'roots');"));
