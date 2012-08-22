<?php

/**
 * Add the RSS feed link in the <head> if there's posts
 */
function roots_feed_link() {
  $count = wp_count_posts('post'); if ($count->publish > 0) {
    echo "\n\t<link rel=\"alternate\" type=\"application/rss+xml\" title=\"". get_bloginfo('name') ." Feed\" href=\"". home_url() ."/feed/\">\n";
  }
}

add_action('wp_head', 'roots_feed_link', -2);

/**
 * Add the asynchronous Google Analytics snippet from HTML5 Boilerplate
 * if an ID is defined in config.php
 *
 * @link mathiasbynens.be/notes/async-analytics-snippet
 */
function roots_google_analytics() {
  if (GOOGLE_ANALYTICS_ID) {
    echo "\n\t<script>\n";
    echo "\t\tvar _gaq=[['_setAccount','" . GOOGLE_ANALYTICS_ID . "'],['_trackPageview']];\n";
    echo "\t\t(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];\n";
    echo "\t\tg.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';\n";
    echo "\t\ts.parentNode.insertBefore(g,s)}(document,'script'));\n";
    echo "\t</script>\n";
  }
}

add_action('wp_footer', 'roots_google_analytics');
