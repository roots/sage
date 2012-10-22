<?php

function bc_customizer_enabled(){}

function bc_customizer_remove_controls($wp_customize){
  $wp_customize->remove_control( 'header_textcolor');
}
add_action( 'customize_register', 'bc_customizer_remove_controls' );

function bc_customizer_btn_class($echo = true){
  $btn_class = get_theme_mod( 'bc_customizer_hero_cta_color' );

  if ($btn_class == 'primary') {$class = 'btn btn-primary';
  } elseif ($btn_class == 'info') { $class = 'btn btn-info';
  } elseif ($btn_class == 'success') { $class = 'btn btn-success';
  } elseif ($btn_class == 'warning') { $class = 'btn btn-warning';
  } elseif ($btn_class == 'danger') { $class = 'btn btn-danger';
  } elseif ($btn_class == 'inverse') { $class = 'btn btn-inverse';
  } else { $class = 'btn'; }

  if ($echo) {
    echo $class;
  } else {
    return $class;
  }
}

function bc_customizer_get_brightness($hex) {
  // returns brightness value from 0 to 255
  // strip off any leading #
  $hex = str_replace('#', '', $hex);
  
  $c_r = hexdec(substr($hex, 0, 2));
  $c_g = hexdec(substr($hex, 2, 2));
  $c_b = hexdec(substr($hex, 4, 2));
  
  return (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;
}

function bc_customizer_adjust_brightness($hex, $steps) {
  // Steps should be between -255 and 255. Negative = darker, positive = lighter
  $steps = max(-255, min(255, $steps));
  
  // Format the hex color string
  $hex = str_replace('#', '', $hex);
  if (strlen($hex) == 3) {
      $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
  }
  
  // Get decimal values
  $r = hexdec(substr($hex,0,2));
  $g = hexdec(substr($hex,2,2));
  $b = hexdec(substr($hex,4,2));
  
  // Adjust number of steps and keep it inside 0 to 255
  $r = max(0,min(255,$r + $steps));
  $g = max(0,min(255,$g + $steps));  
  $b = max(0,min(255,$b + $steps));
  
  $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
  $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
  $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
  
  return '#'.$r_hex.$g_hex.$b_hex;
}

function bc_customizer_typography(){
  $webfont      = get_theme_mod('bc_customizer_google_webfonts');
  $f            = strlen($webfont);
  if ($f > 3){
    $webfontname  = str_replace(' ', '+', $webfont);
    echo "<link href='http://fonts.googleapis.com/css?family=" . $webfontname . "' rel='stylesheet' type='text/css'>";
  }
}

function bc_customizer_preview() {
  ?>
  <script type="text/javascript">
  ( function( $ ){
  } )( jQuery )
  </script>
  <?php
}