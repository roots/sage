<?php
function shoestrap_secondary_navbar() {

  if ( shoestrap_getVariable( 'secondary_navbar_toggle' ) != 0 ) : ?>

    <div class="<?php echo shoestrap_container_class(); ?>">
      <header class="secondary <?php echo shoestrap_navbar_class( 'secondary' ); ?>" role="banner">
        <button data-target=".nav-collapse-secondary" data-toggle="collapse" type="button" class="navbar-toggle">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <nav class="nav-secondary nav-collapse-secondary collapse pull-left" role="navigation">
          <?php wp_nav_menu( array( 'theme_location' => 'secondary_navigation', 'menu_class' => shoestrap_nav_class_pull() ) ); ?>
        </nav>
        <?php
        if ( shoestrap_getVariable( 'navbar_secondary_social' ) != 0 )
          shoestrap_navbar_social_links();
        ?>
      </header>
    </div>
  
  <?php endif;
}
add_action( 'shoestrap_pre_wrap', 'shoestrap_secondary_navbar' );



if ( shoestrap_getVariable( 'secondary_navbar_margin' ) != 0 ) {
  function shoestrap_secondary_navbar_margin() {
  $secondary_navbar_margin = shoestrap_getVariable( 'secondary_navbar_margin' );
  
  $style = '.secondary {';
  $style .= 'margin-top:'. $secondary_navbar_margin .'px !important;';
  $style .= 'margin-bottom:'. $secondary_navbar_margin .'px !important;';
  $style .= '}';
      
  wp_add_inline_style( 'shoestrap_css', $style );

  }
  add_action( 'wp_enqueue_scripts', 'shoestrap_secondary_navbar_margin', 101 );
}