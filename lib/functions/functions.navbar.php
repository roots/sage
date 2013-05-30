<?php

function shoestrap_nav_class_pull() {
  if ( shoestrap_getVariable( 'navbar_nav_right' ) == '1' ) {
    $ul = 'nav navbar-nav pull-right';
  } else {
    $ul = 'nav navbar-nav';
  }
  return $ul;
}

/*
 * The template for the primary navbar searchbox
 */
function shoestrap_navbar_searchbox() {
  $show_searchbox = shoestrap_getVariable( 'navbar_search' );
  if ( $show_searchbox == '1' ) { ?>
    <ul class="pull-right nav nav-collapse"><li>
    <?php do_action('shoestrap_pre_searchform'); ?>
    <form role="search" method="get" id="searchform" class="form-search navbar-search" action="<?php echo home_url('/'); ?>">
      <label class="hide" for="s"><?php _e('Search for:', 'shoestrap'); ?></label>
      <input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="s" class="search-query" placeholder="<?php _e('Search', 'shoestrap'); ?> <?php bloginfo('name'); ?>">
    </form>
    <?php do_action('shoestrap_after_searchform'); ?>
    </li></ul>
    <?php
  }
}
add_action( 'shoestrap_post_main_nav', 'shoestrap_navbar_searchbox', 11 );

function shoestrap_navbar_class() {
  $pos    = shoestrap_getVariable( 'navbar_position' );
  $style  = shoestrap_getVariable( 'navbar_style' );

  if ( $pos == 1 )
    $class = 'navbar navbar-fixed-top';
  elseif ( $pos == 2 )
    $class = 'navbar navbar-fixed-bottom';
  else $class = 'navbar navbar-static-top';

  return $class . ' style' . $style;
}

function shoestrap_secondary_navbar() {
  if (has_nav_menu('secondary_navigation')) : ?>
    <div class="navbar">
      <div class="<?php echo shoestrap_container_class(); ?>">
        <a class="btn navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <?php wp_nav_menu( array( 'theme_location' => 'secondary_navigation', 'menu_class' => 'nav navbar-nav' ) ); ?>
      </div>
    </div>
  <?php endif;
}
add_action( 'shoestrap_pre_content', 'shoestrap_secondary_navbar' );
