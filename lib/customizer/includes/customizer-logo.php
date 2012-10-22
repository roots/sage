<?php

function bc_customizer_logo() {
  if (get_theme_mod('bc_customizer_logo')) {
    $image = '<img id="site-logo" src="%s" alt="%s" style="max-width:100%%; height:auto;">';
    printf(
      $image,
      get_theme_mod('bc_customizer_logo'),
      get_bloginfo('name')
    );
  } else {
    bloginfo('name');
  }
}
