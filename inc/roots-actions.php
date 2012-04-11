<?php

function roots_feed_link() {
  $count = wp_count_posts('post'); if ($count->publish > 0) {
    echo "\n\t<link rel=\"alternate\" type=\"application/rss+xml\" title=\"". get_bloginfo('name') ." Feed\" href=\"". home_url() ."/feed/\">\n";
  }
}

add_action('roots_head', 'roots_feed_link');

function roots_google_analytics() {
  $roots_google_analytics_id = GOOGLE_ANALYTICS_ID;
  if ($roots_google_analytics_id !== '') {
    echo "\n\t<script>\n";
    echo "\t\tvar _gaq = _gaq || [];\n";
    echo "\t\t_gaq.push(['_setAccount', '$roots_google_analytics_id']);\n";
    echo "\t\t_gaq.push(['_trackPageview']);\n";
    echo "\n";
    echo "\t\t(function() {\n";
    echo "\t\t\tvar ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;\n";
    echo "\t\t\tga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';\n";
    echo "\t\t\tvar s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);\n";
    echo "\t\t})();\n";
    echo "\t</script>\n";
  }
}

add_action('roots_head', 'roots_google_analytics');