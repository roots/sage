<?php

function roots_get_stylesheets() {
  $styles = '';
  $styles .= stylesheet_link_tag('/style.css', 1);
  $styles .= stylesheet_link_tag('/bootstrap.css', 1);

  if (BOOTSTRAP_RESPONSIVE) {
    $styles .= stylesheet_link_tag('/bootstrap-responsive.css', 1);
  }

  $styles .= stylesheet_link_tag('/app.css', 1);

  if (is_child_theme()) {
    $styles .= "\t<link rel=\"stylesheet\" href=\"" . get_stylesheet_uri(). "\">\n";
  }

  echo $styles;
}

function stylesheet_link_tag($file, $tabs = 0, $newline = true, $rel = 'stylesheet') {
  $indent = str_repeat("\t", $tabs);
  return $indent . '<link rel="' . $rel .'" href="' . get_template_directory_uri() . '/css' . $file . '">' . ($newline ? "\n" : "");
}

add_action('roots_stylesheets', 'roots_get_stylesheets');

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