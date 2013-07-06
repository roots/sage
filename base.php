<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>

  <!--[if lt IE 7]><div class="alert"><?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'roots'); ?></div><![endif]-->

  <?php

  // If the user has selected "boxed" style, add a container div
  if ( shoestrap_getVariable( 'site_style' ) == 'boxed' )
    echo '<div class="container boxed-container">';

  // the "get_header" hook
  do_action( 'get_header' );

  // Use Bootstrap's navbar if enabled
  if ( current_theme_supports( 'bootstrap-top-navbar') ) :
    // the "shoestrap_pre_navbar" hook
    do_action( 'shoestrap_pre_navbar' );

    // Override the header-top-navbar template file if there is a "shoestrap_header_top_navbar_override" action
    if ( !has_action( 'shoestrap_header_top_navbar_override' ) )
      get_template_part( 'templates/header-top-navbar' );
    else
      do_action( 'shoestrap_header_top_navbar_override' );

  // If the navbar is disabled, load the menu header
  else :
    // Override the header template file if there is a "shoestrap_header_override" action
    if ( !has_action( 'shoestrap_header_override' ) )
      get_template_part( 'templates/header' );
    else
      do_action( 'shoestrap_header_override' );
  endif;

  // The "shoestrap_post_navbar" action
  do_action( 'shoestrap_post_navbar' );

  // If the user has selected "boxed" style, close the container div that we opened earlier.
  if ( shoestrap_getVariable( 'site_style' ) == 'boxed' )
    echo '</div>';

  // If there is a "shoestrap_below_top_navbar" action, add it here.
  // This is also used to render the extra header.
  if ( has_action( 'shoestrap_below_top_navbar' ) ) :
    echo '<div class="before-main-wrapper">';
    do_action('shoestrap_below_top_navbar');
    echo '</div>';
  endif;

  // The "shoestrap_pre_wrap" hook.
  // This is also used to render the hero area.
  do_action('shoestrap_pre_wrap');

  if ( has_action( 'shoestrap_breadcrumbs' ) ) :
    do_action('shoestrap_breadcrumbs');
  endif;

	do_action('shoestrap_header_media');

  // Open the main-content wrapper
  echo '<div class="wrap main-section ' . shoestrap_container_class() . '" role="document">';
    // The "shoestrap_pre_content" hook
    do_action('shoestrap_pre_content');
    // Adding a div with the "row" class so that bootstrap can properly handle
    // the main content and sidebars width classes
    echo '<div class="content row">';
      // The "shoestrap_pre_main" hook
      do_action('shoestrap_pre_main');

      // If the layout requires an extra wrapping element, add it here.
      if ( shoestrap_section_class( 'wrap' ) )
        echo '<div class="mp_wrap ' . shoestrap_section_class( 'wrapper' ) . '"><div class="row">';

      // This is where we load the extra template files for the main content.
      echo '<div class="main ' . shoestrap_section_class( 'main' ) . '" role="main">';
        include roots_template_path();
      echo '</div><!-- /.main -->';

      do_action('shoestrap_after_main');

      // Add the PRIMARY sidebar when applicable.
      if ( ( shoestrap_getLayout() != 0 && ( roots_display_sidebar() ) ) || ( is_front_page() && get_theme_mod( 'layout_sidebar_on_front' ) == 1 ) ) :
        if ( !is_front_page() || ( is_front_page() && get_theme_mod( 'layout_sidebar_on_front' ) == 1 ) ) :
          echo '<aside class="sidebar ' . shoestrap_section_class( 'primary' ) . '" role="complementary">';
            if ( !has_action( 'shoestrap_sidebar_override' ) )
              include roots_sidebar_path();
            else
              do_action( 'shoestrap_sidebar_override' );
          echo '</aside><!-- /.sidebar -->';
        endif;
      endif;

      // If the layout requires an extra wrapping element and we added it before, close it here.
      if ( shoestrap_section_class( 'wrap' ) )
        echo '</div></div>';

      // Add the SECONDARY sidebar when applicable.
      if ( shoestrap_getLayout() >= 3 && is_active_sidebar( 'sidebar-secondary' ) ) :
        if ( !is_front_page() || ( is_front_page() && get_theme_mod( 'layout_sidebar_on_front' ) == 1 ) ) :
          echo '<aside class="sidebar secondary ' . shoestrap_section_class( 'secondary' ) . '" role="complementary">';
            dynamic_sidebar('sidebar-secondary');
          echo '</aside><!-- /.sidebar -->';
        endif;
      endif;

    // Close the content div
    echo '</div><!-- /.content -->';
    // The "shoestrap_after_content" hook
    do_action('shoestrap_after_content');
  // Close the wrapper div
  echo '</div><!-- /.wrap -->';
  // The "shoestrap_after_wrap" hook
  do_action('shoestrap_after_wrap');

  // If the user has selected "boxed" style, add a container div to contain the footer.
  if ( shoestrap_getVariable( 'site_style' ) == 'boxed' )
    echo '<div class="container boxed-container">';

  // The "shoestrap_pre_footer" hook
  do_action('shoestrap_pre_footer');

  // Add the footer OR override the footer template if there is a "shoestrap_footer_override" action.
  if ( !has_action( 'shoestrap_footer_override' ) )
    get_template_part('templates/footer');
  else
    do_action( 'shoestrap_footer_override' );

  // The "shoestrap_after_footer" hook
  do_action('shoestrap_after_footer');

  // If the user has selected "boxed" style, close the container div that we opened earlier to contain the footer.
  if ( shoestrap_getVariable( 'site_style' ) == 'boxed' )
    echo '</div>';

  // wp_footer required by WordPress.
  wp_footer();
  ?>

</body>
</html>
