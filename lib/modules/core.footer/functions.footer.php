<?php

function shoestrap_footer_css() {
  $bg       = shoestrap_getVariable( 'footer_background' );
  $cl       = shoestrap_getVariable( 'footer_color' );
  $cl_brand = shoestrap_getVariable( 'color_brand_primary' );
  $opacity  = (intval(shoestrap_getVariable( 'footer_opacity' )))/100;
  $rgb      = shoestrap_get_rgb($bg, true);
  $border   = shoestrap_getVariable( 'footer_border_top' );

  $style = '<style id="core.footer-css">';
  $style .= 'footer {';
    $style .= 'color:' . $cl . ';';
    if ( $opacity != 1 && $opacity != "" ) :
      $style .= 'background: rgba(' . $rgb . ',' . $opacity . ');';
    else :
      $style .= 'background:' . $bg . ';';
    endif;

    $style .= 'border-top:' . $border['width'] . 'px ' . $border['style'] . ' ' . $border['color'] . ';';
    $style .= 'padding: 18px 10px 18px;';
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

  $style .= '</style>';

  echo $style;
}
add_action( 'wp_head', 'shoestrap_footer_css' );


/*
 * Creates the customizer icon on the bottom-left corner of our site
 * (visible only by admins)
 */
function shoestrap_footer_icon() {
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
add_action( 'shoestrap_after_footer', 'shoestrap_footer_icon' );

function shoestrap_footer_html() {

  $blog_name = get_bloginfo( 'name', 'display' );
  $ftext = shoestrap_getVariable( 'footer_text' );
  if ($ftext == "")
    $ftext = '&copy; [year] [sitename]';
  $ftext = str_replace("[year]", date('Y'), $ftext);
  $ftext = str_replace("[sitename]", $blog_name, $ftext);

  $blog_name = get_bloginfo( 'name', 'display' );

  $social = shoestrap_getVariable( 'footer_social_toggle' );
  $social_width = shoestrap_getVariable( 'footer_social_width' );

  $width = 12;
  if (intval($social_width) > 0 && $social) { // Social is enabled, we're modifying the width!
    $width = $width - intval($social_width);
  }
  $social_blank = shoestrap_getVariable( 'footer_social_new_window_toggle' );

  if ($social_blank == 1) {
    $blank = ' target="_blank"';
  }

  $networks = shoestrap_get_social_links();

  ?>
  <footer class="content-info" role="contentinfo">
    <div class="<?php echo shoestrap_container_class(); ?>">
      <?php do_action( 'shoestrap_footer_before_copyright' ); ?>    
      <div id="footer-copyright" class="row">
        <article class="<?php echo shoestrap_container_class(); ?>">
          <div id="copyright-bar" class="col col-lg-<?php echo $width; ?>"><?php echo $ftext; ?></div>
          <?php if ($social && count($networks) > 0) : ?>
          <div id="footer_social_bar" class="col col-lg-<?php echo $social_width; ?>">
          <?php 
            foreach ($networks as $network) {
              if ($network['url'] == "")
                continue;
              ?>
                <a href="<?php echo $network['url']; ?>"<?php echo $blank;?> data-toggle="tooltip" data-placement="top" title="" data-original-title="Tooltip on top"><span class="glyphicon glyphicon-<?php echo $network['icon']; ?>"></span></a>
              <?php
            }
          ?>
          </div>      
          <?php endif; ?>
        </article>
      </div>
    </div>
  </footer>
  <?php
}
add_action( 'shoestrap_footer_override', 'shoestrap_footer_html' );



function shoestrap_footer_copyright() {

}
add_action( 'shoestrap_after_footer', 'shoestrap_footer_copyright' );
