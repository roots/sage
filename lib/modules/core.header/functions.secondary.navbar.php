<?php

if ( !function_exists( 'shoestrap_secondary_navbar' ) ) :
function shoestrap_secondary_navbar() {

  if ( shoestrap_getVariable( 'secondary_navbar_toggle' ) != 0 ) : ?>

    <div class="<?php echo shoestrap_container_class(); ?>">
      <header class="secondary navbar navbar-default <?php echo shoestrap_navbar_class( 'secondary' ); ?>" role="banner">
        <button data-target=".nav-secondary" data-toggle="collapse" type="button" class="navbar-toggle">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <?php
        if ( shoestrap_getVariable( 'navbar_secondary_social' ) != 0 )
          shoestrap_navbar_social_links();
        ?>
        <nav class="nav-secondary navbar-collapse collapse" role="navigation">
          <?php wp_nav_menu( array( 'theme_location' => 'secondary_navigation', 'menu_class' => shoestrap_nav_class_pull() ) ); ?>
        </nav>
      </header>
    </div>
  
  <?php endif;
}
endif;
add_action( 'shoestrap_pre_wrap', 'shoestrap_secondary_navbar' );


if ( shoestrap_getVariable( 'secondary_navbar_margin' ) != 0 ) :
  if ( !function_exists( 'shoestrap_secondary_navbar_margin' ) ) :
    function shoestrap_secondary_navbar_margin() {
      $secondary_navbar_margin = shoestrap_getVariable( 'secondary_navbar_margin' );
      $style = '.secondary { margin-top:' . $secondary_navbar_margin . 'px !important; margin-bottom:'. $secondary_navbar_margin .'px !important; }';

      wp_add_inline_style( 'shoestrap_css', $style );
    }
  endif;
  add_action( 'wp_enqueue_scripts', 'shoestrap_secondary_navbar_margin', 101 );
endif;