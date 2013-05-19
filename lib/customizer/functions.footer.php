<?php

function shoestrap_footer_css() {
  $bg = get_theme_mod( 'footer_bg' );
  $cl = get_theme_mod( 'footer_color' );
  
  echo '<style>';
  echo 'footer.content-info{background:' . $bg . '; color:' . $cl . ';}';
  echo '</style>';
}
add_action( 'wp_head', 'shoestrap_footer_css' );

/*
 * Creates the customizer icon on the bottom-left corner of our site
 * (visible only by admins)
 */
function footer_icon() { ?>
  <?php if (current_user_can( 'edit_theme_options' )){ ?>
    <style>
    </style>
    <div id="shoestrap_icon">
      <?php
      $current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      $href = add_query_arg( 'url', urlencode( $current_url ), wp_customize_url() ); ?>
      <a href="<?php echo $href; ?>"><i class="glyphicon-cogs"></i></a>
    </div>
  <?php } ?>
  </div>
<?php }
add_action( 'shoestrap_after_footer', 'footer_icon' );
