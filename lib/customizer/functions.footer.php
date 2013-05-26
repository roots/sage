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
function shoestrap_footer_widget_area_sidebars() {
  // Register the custom footer sidebars
  if (shoestrap_getVariable( 'footer_widget_area_sidebars') > 0 ) {
    for ($i = 1; $i <= intval(shoestrap_getVariable( 'footer_widget_area_sidebars' )); $i++){
      register_sidebar(array(
        'name' => __( 'Footer Widget Area' )." ".$i,
        'id' => 'footer-widget-sidebar-'.$i,
        'description' => __( '' ),
      ));    
    }
  }
}
add_action( 'widgets_init', 'shoestrap_footer_widget_area_sidebars' );

/*
 * Creates the customizer icon on the bottom-left corner of our site
 * (visible only by admins)
 */
function footer_widget_area() {
  global $wp_customize;
  if (shoestrap_getVariable( 'footer_widget_area_sidebars') == 0 )
    return;




  $bg = shoestrap_getVariable( 'footer_widget_area_background' );
  $cl = shoestrap_getVariable( 'footer_widget_area_color' );
  $opacity = (intval(shoestrap_getVariable( 'footer_widget_area_opacity' )))/100;
  $rgb = shoestrap_get_rgb($bg, true);  
  $bt = shoestrap_getVariable( 'footer_widget_area_border_top' );
  $bb = shoestrap_getVariable( 'footer_widget_area_border_bottom' );
  ?>
  <style>
    #footer_widget_area{
      color:<?php echo $cl ?>;     
    <?php if ($opacity != 1 && $opacity != "") : ?>
      background: rgba(<?php echo $rgb; ?>,<?php echo $opacity; ?>);
    <?php else : ?>
      background: <?php echo $bg ?>; 
    <?php endif; ?>
      padding: 0px 5px 20px 5px;
      border-top: <?php echo $bt['width']?>px <?php echo $bt['style']?> <?php echo $bt['color']?>;
      border-bottom: <?php echo $bb['width']?>px <?php echo $bb['style']?> <?php echo $bb['color']?>;
    }
    #footer_widget_area li {
      list-style: none;
    }
  </style>  
  <div id="footer_widget_area" class="row">
  <div class="<?php echo shoestrap_container_class(); ?>">

    <?php

      $columns = 12/shoestrap_getVariable( 'footer_widget_area_sidebars' );
      for ($i = 1; $i <= $columns+1; $i++){
        ?>
        <div class="col col-lg-<?php echo $columns; ?>">
          <?php dynamic_sidebar( 'footer-widget-sidebar-'.$i ); ?> 
        </div>
        <?php
      }


     ?>
    </div>
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


