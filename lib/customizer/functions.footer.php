<?php

function shoestrap_footer_css() {
  $bg = shoestrap_getVariable( 'footer_background' );
  $cl = shoestrap_getVariable( 'footer_color' );
  $opacity = (intval(shoestrap_getVariable( 'footer_opacity' )))/100;
  $rgb = shoestrap_get_rgb($bg, true);
?>
  <style>
    footer.content-info{
      color:<?php echo $cl ?>;     
    <?php if ($opacity != 1 && $opacity != "") : ?>
      background: rgba(<?php echo $rgb; ?>,<?php echo $opacity; ?>);
    <?php else : ?>
      background: <?php echo $bg ?>; 
    <?php endif; ?>
        
    }
  </style>
<?php
}
add_action( 'wp_head', 'shoestrap_footer_css' );

/*
 * Creates the customizer icon on the bottom-left corner of our site
 * (visible only by admins)
 */
function footer_widget_area() {
  global $wp_customize;
  if (shoestrap_getVariable( 'footer_widget_area_toggle' == 0 ))
    return;




  $bg = shoestrap_getVariable( 'footer_widget_area_background' );
  $cl = shoestrap_getVariable( 'footer_widget_area_color' );
  $opacity = (intval(shoestrap_getVariable( 'footer_widget_area_opacity' )))/100;
  $rgb = shoestrap_get_rgb($bg, true);  
  ?>
  <style>
    #footer_widget_area{
      color:<?php echo $cl ?>;     
    <?php if ($opacity != 1 && $opacity != "") : ?>
      background: rgba(<?php echo $rgb; ?>,<?php echo $opacity; ?>);
    <?php else : ?>
      background: <?php echo $bg ?>; 
    <?php endif; ?>
        
    }
  </style>  
  <div id="footer_widget_area">

  </div>
  
  
<?php }
add_action( 'shoestrap_before_footer', 'footer_widget_area' );

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
