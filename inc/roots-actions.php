<?php

function roots_google_analytics() {
  $roots_google_analytics_id = GOOGLE_ANALYTICS_ID;
  if ($roots_google_analytics_id !== '') {
    echo "\n\t<script>\n";
    echo "\t\tvar _gaq=[['_setAccount','$roots_google_analytics_id'],['_trackPageview'],['_trackPageLoadTime']];\n";
    echo "\t\t(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];\n";
    echo "\t\tg.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';\n";
    echo "\t\ts.parentNode.insertBefore(g,s)}(document,'script'));\n";
    echo "\t</script>\n";
  }
}

add_action('roots_footer', 'roots_google_analytics');