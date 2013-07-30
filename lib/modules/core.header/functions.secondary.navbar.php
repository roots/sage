<?php
function shoestrap_secondary_navbar() {

  if ( shoestrap_getVariable( 'secondary_navbar_toggle' ) != 0 ) : ?>

    <div class="<?php echo shoestrap_container_class(); ?>">
      <header class="<?php echo shoestrap_navbar_class( 'secondary' ); ?>" role="banner">
        <button data-target=".nav-main" data-toggle="collapse" type="button" class="navbar-toggle">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <nav class="nav-secondary nav-collapse collapse pull-left" role="navigation">
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
add_action( 'shoestrap_header_media', 'shoestrap_secondary_navbar' );
