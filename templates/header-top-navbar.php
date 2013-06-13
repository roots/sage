<?php do_action( 'shoestrap_pre_navbar' ); ?>
<header id="banner" class="topnavbar <?php echo shoestrap_navbar_class(); ?>" role="banner">
  <div class="<?php echo shoestrap_container_class(); ?>">
  <a class="btn navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </a>

    <?php if ( get_theme_mod( 'navbar_brand' ) != 0 ) : ?>
      <a class="navbar-brand <?php shoestrap_branding_class(); ?>" href="<?php echo home_url(); ?>/">
        <?php if ( get_theme_mod( 'navbar_logo' ) == 1 ) : ?>
          <?php shoestrap_logo(); ?>
        <?php else : ?>
          <?php bloginfo('name'); ?>
        <?php endif; ?>
      </a>
    <?php endif; ?>

    <?php do_action( 'shoestrap_pre_main_nav' ); ?>
    <nav class="nav-main nav-collapse collapse" role="navigation">
      <?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu( array( 'theme_location' => 'primary_navigation', 'menu_class' => shoestrap_nav_class_pull() ) );
        endif;
      ?>
    </nav>
    <?php do_action( 'shoestrap_post_main_nav' ); ?>
  </div>
  <?php do_action( 'shoestrap_post_navbar' ); ?>
</header>
