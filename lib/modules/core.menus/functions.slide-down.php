<?php

if ( !function_exists( 'shoestrap_slidedown_widgets_init' ) ) :
function shoestrap_slidedown_widgets_init() {
  // Register widgetized areas
  register_sidebar( array( 
    'name'          => __( 'Navbar Slide-Down Top', 'shoestrap' ),
    'id'            => 'navbar-slide-down-top',
    'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
    'after_widget'  => '</div></section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
  ) );

  register_sidebar( array( 
    'name'          => __( 'Navbar Slide-Down 1', 'shoestrap' ),
    'id'            => 'navbar-slide-down-1',
    'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
    'after_widget'  => '</div></section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
  ) );

  register_sidebar( array( 
    'name'          => __( 'Navbar Slide-Down 2', 'shoestrap' ),
    'id'            => 'navbar-slide-down-2',
    'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
    'after_widget'  => '</div></section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
  ) );

  register_sidebar( array( 
    'name'          => __( 'Navbar Slide-Down 3', 'shoestrap' ),
    'id'            => 'navbar-slide-down-3',
    'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
    'after_widget'  => '</div></section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
  ) );

  register_sidebar( array( 
    'name'          => __( 'Navbar Slide-Down 4', 'shoestrap' ),
    'id'            => 'navbar-slide-down-4',
    'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
    'after_widget'  => '</div></section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
  ) );
}
endif;
add_action( 'widgets_init', 'shoestrap_slidedown_widgets_init', 20 );

if ( !function_exists( 'shoestrap_navbar_widget_area_class' ) ) :
/*
 * Calculates the class of the widget areas based on a 12-column bootstrap grid.
 */
function shoestrap_navbar_widget_area_class() {
  $str = '';
  $str .= ( is_active_sidebar( 'navbar-slide-down-1' ) ) ? '1' : '';
  $str .= ( is_active_sidebar( 'navbar-slide-down-2' ) ) ? '2' : '';
  $str .= ( is_active_sidebar( 'navbar-slide-down-3' ) ) ? '3' : '';
  $str .= ( is_active_sidebar( 'navbar-slide-down-4' ) ) ? '4' : '';

  $strlen = strlen( $str );

  $colwidth = ( $strlen > 0 ) ? 12 / $strlen : 12;

  return $colwidth;
}
endif;

if ( !function_exists( 'shoestrap_navbar_slidedown_content' ) ) :
/*
 * Prints the content of the slide-down widget areas.
 */
function shoestrap_navbar_slidedown_content() { ?>
  <?php if ( shoestrap_getVariable( 'site_style' ) != 'fluid' ) : ?>
    <div id="megaDrop" class="top-megamenu container">
  <?php else : ?>
    <div id="megaDrop" class="top-megamenu">
  <?php endif; ?>

    <?php $widgetareaclass = 'col-sm-' . shoestrap_navbar_widget_area_class(); ?>
    <?php dynamic_sidebar( 'navbar-slide-down-top' ); ?>

    <div class="row">
      <?php if ( is_active_sidebar( 'navbar-slide-down-1' ) ) : ?>
        <div class="<?php echo $widgetareaclass; ?>">
          <?php dynamic_sidebar( 'navbar-slide-down-1' ); ?>
        </div>
      <?php endif; ?>
    
      <?php if ( is_active_sidebar( 'navbar-slide-down-2' ) ) : ?>
        <div class="<?php echo $widgetareaclass; ?>">
          <?php dynamic_sidebar( 'navbar-slide-down-2' ); ?>
        </div>
      <?php endif; ?>

      <?php if ( is_active_sidebar( 'navbar-slide-down-3' ) ) : ?>
        <div class="<?php echo $widgetareaclass; ?>">
          <?php dynamic_sidebar( 'navbar-slide-down-3' ); ?>
        </div>
      <?php endif; ?>

      <?php if ( is_active_sidebar( 'navbar-slide-down-4' ) ) : ?>
        <div class="<?php echo $widgetareaclass; ?>">
          <?php dynamic_sidebar( 'navbar-slide-down-4' ); ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?php
}
endif;
add_action( 'shoestrap_below_top_navbar', 'shoestrap_navbar_slidedown_content', 1 );


if ( !function_exists( 'shoestrap_navbar_slidedown_toggle' ) ) :
function shoestrap_navbar_slidedown_toggle() {
  $navbar_color = shoestrap_getVariable( 'navbar_bg' );
  
  if ( is_active_sidebar( 'navbar-slide-down-top' ) || is_active_sidebar( 'navbar-slide-down-1' ) || is_active_sidebar( 'navbar-slide-down-2' ) || is_active_sidebar( 'navbar-slide-down-3' ) || is_active_sidebar( 'navbar-slide-down-4' ) ) {
    if ( shoestrap_get_brightness( $navbar_color ) >= 160 )
      echo '<a style="width: 30px;" class="toggle-nav black" href="#">';
    else
      echo '<a style="width: 30px;" class="toggle-nav" href="#">';

    echo '<i class="el-icon-arrow-down"></i></a>';
  }
}
endif;
add_action( 'shoestrap_pre_main_nav', 'shoestrap_navbar_slidedown_toggle' );


if ( !function_exists( 'shoestrap_megadrop_script' ) ) :
function shoestrap_megadrop_script() {
  if ( is_active_sidebar( 'navbar-slide-down-top' ) || is_active_sidebar( 'navbar-slide-down-1' ) || is_active_sidebar( 'navbar-slide-down-2' ) || is_active_sidebar( 'navbar-slide-down-3' ) || is_active_sidebar( 'navbar-slide-down-4' ) ) {
    wp_register_script( 'shoestrap_megadrop', get_template_directory_uri() . '/assets/js/megadrop.js', false, null, false );
    wp_enqueue_script( 'shoestrap_megadrop' );
  }
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_megadrop_script', 200 );