<?php

if ( !function_exists( 'shoestrap_footer_css' ) ) :
function shoestrap_footer_css() {
  $bg         = shoestrap_getVariable( 'footer_background' );
  $cl         = shoestrap_getVariable( 'footer_color' );
  $cl_brand   = shoestrap_getVariable( 'color_brand_primary' );
  $opacity    = ( intval( shoestrap_getVariable( 'footer_opacity' ) ) ) / 100;
  $rgb        = shoestrap_get_rgb( $bg, true );
  $border     = shoestrap_getVariable( 'footer_border_top' );
  $top_margin = shoestrap_getVariable( 'footer_top_margin' );

  $container_margin = $top_margin * 0.381966011;

  $style = 'footer.content-info {';
    $style .= 'color:' . $cl . ';';

    if ( $opacity != 1 && $opacity != "" ) :
      $style .= 'background: rgba(' . $rgb . ',' . $opacity . ');';
    else :
      $style .= 'background:' . $bg . ';';
    endif;

    if ( !empty($border) && $border['border-top'] > 0 && !empty($border['border-color']) ) :
      $style .= 'border-top:' . $border['border-top'] . ' ' . $border['border-style'] . ' ' . $border['border-color'] . ';';
    endif;

    $style .= 'padding: 18px 10px 18px;';
    if ( !empty($top_margin) ) :
      $style .= 'margin-top:'. $top_margin .'px;';
    endif;
  $style .= '}';

  $style .= 'footer div.container { ';
    $style .= 'margin-top:'. $container_margin .'px;';
  $style .= '}';

  $style .= '#copyright-bar { ';
    $style .= 'line-height: 30px;';
  $style .= '}';

  $style .= '#footer_social_bar { ';
    $style .= 'line-height: 30px;';
    $style .= 'font-size: 16px;';
    $style .= 'text-align: right;';
  $style .= '}';

  $style .= '#footer_social_bar a { ';
    $style .= 'margin-left: 9px;';
    $style .= 'padding: 3px;';
    $style .= 'color:' . $cl . ';';
  $style .= '}';

  $style .= '#footer_social_bar a:hover, #footer_social_bar a:active { ';
    $style .= 'color:' . $cl_brand . ' !important;';
    $style .= 'text-decoration:none;';
  $style .= '}';

  wp_add_inline_style( 'shoestrap_css', $style );
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_footer_css', 101 );


if ( !function_exists( 'shoestrap_footer_icon' ) ) :
/*
 * Creates the customizer icon on the bottom-left corner of our site
 * (visible only by admins)
 */
function shoestrap_footer_icon() {
  global $wp_customize;

  if ( current_user_can( 'edit_theme_options' ) && !isset( $wp_customize ) ) : ?>
    <div id="shoestrap_icon" class="visible-lg">
      <a href="<?php echo admin_url( 'admin.php?page=shoestrap' ); ?>"><i class="icon el-icon-cogs"></i></a>
    </div>
  <?php endif; ?>
  </div>
  <?php
}
endif;
add_action( 'shoestrap_after_footer', 'shoestrap_footer_icon' );

if ( !function_exists( 'shoestrap_footer_html' ) ) :
function shoestrap_footer_html() {
  $blog_name  = get_bloginfo( 'name', 'display' );
  $ftext      = shoestrap_getVariable( 'footer_text' );

  if ( $ftext == '' ) :
    $ftext = '&copy; [year] [sitename]';
  endif;

  $ftext = str_replace( '[year]', date( 'Y' ), $ftext );
  $ftext = str_replace( '[sitename]', $blog_name, $ftext );

  $social = shoestrap_getVariable( 'footer_social_toggle' );
  $social_width = shoestrap_getVariable( 'footer_social_width' );

  $width = 12;

  // Social is enabled, we're modifying the width!
  if ( intval( $social_width ) > 0 && $social ) :
    $width = $width - intval($social_width);
  endif;

  $social_blank = shoestrap_getVariable( 'footer_social_new_window_toggle' );

  if ( $social_blank == 1 ) :
    $blank = ' target="_blank"';
  endif;

  $networks = shoestrap_get_social_links();

  do_action( 'shoestrap_footer_before_copyright' );
  ?>

  <div id="footer-copyright">
    <article class="<?php echo shoestrap_container_class(); ?>">
      <div id="copyright-bar" class="col-lg-<?php echo $width; ?>"><?php echo $ftext; ?></div>
      <?php if ( $social && count( $networks ) > 0 ) : ?>
        <div id="footer_social_bar" class="col-lg-<?php echo $social_width; ?>">
        <?php
          foreach ( $networks as $network ) {
            if ( $network['url'] == '' )
              continue;
            ?>
            <a href="<?php echo $network['url']; ?>"<?php echo $blank;?> title="<?php echo $network['icon']; ?>">
              <span class="icon el-icon-<?php echo $network['icon']; ?>"></span>
            </a>
          <?php } ?>
        </div>
      <?php endif; ?>
    </article>
  </div>
  <?php
}
endif;

function shoestrap_footer_content() {
  // Finding the number of active widget sidebars
  $num_of_sidebars = 0;
  $base_class = 'col-md-';

  for ( $i=0; $i<5 ; $i++ ) :
    $sidebar = 'sidebar-footer-'.$i.'';
    if ( is_active_sidebar($sidebar) ) :
      $num_of_sidebars++;
    endif;
  endfor;

  // Showing the active sidebars
  for ( $i=0; $i<5 ; $i++ ) :
    $sidebar = 'sidebar-footer-' . $i;

    if ( is_active_sidebar( $sidebar ) ) :
      // Setting each column width accordingly
      $col_class = 12 / $num_of_sidebars;
    
      echo '<div class="' . $base_class . $col_class . '">';
      dynamic_sidebar( $sidebar );
      echo '</div>';
    endif;
  endfor;

  // Showing extra features from /lib/modules/core.footer/functions.footer.php
  do_action( 'shoestrap_footer_pre_override' );
  echo '</div>';
}