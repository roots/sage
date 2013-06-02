<?php

function shoestrap_footer_css() {
  $bg       = shoestrap_getVariable( 'footer_background' );
  $cl       = shoestrap_getVariable( 'footer_color' );
  $opacity  = (intval(shoestrap_getVariable( 'footer_opacity' )))/100;
  $rgb      = shoestrap_get_rgb($bg, true);
  $border   = shoestrap_getVariable( 'footer_border_top' );

  $style = '<style>';
  $style .= 'footer.content-info{';
  $style .= 'color:' . $cl . ';';
  if ( $opacity != 1 && $opacity != "" ) :
    $style .= 'background: rgba(' . $rgb . ',' . $opacity . ');';
  else :
    $style .= 'background:' . $bg . ';';
  endif;

  $style .= 'border-top:' . $border['width'] . 'px ' . $border['style'] . ' ' . $border['color'] . ';';
  $style .= '}';
  $style .= '</style>';

  echo $style;
}
add_action( 'wp_head', 'shoestrap_footer_css' );


/*
 * Creates the customizer icon on the bottom-left corner of our site
 * (visible only by admins)
 */
function footer_icon() {
  global $wp_customize;
  ?>
  <?php if (current_user_can( 'edit_theme_options' ) && !isset( $wp_customize ) ){ ?>
    <style>
    </style>
    <div id="shoestrap_icon">
      <?php
      $current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      $href = add_query_arg( 'url', urlencode( $current_url ), wp_customize_url() ); ?>
      <a href="<?php echo $href; ?>"><i class="glyphicon glyphicon-cogs"></i></a>
    </div>
  <?php } ?>
  </div>
<?php }
add_action( 'shoestrap_after_footer', 'footer_icon' );


