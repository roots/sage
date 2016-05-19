<header class="banner">
<nav class="navbar navbar-default navbar-static-top nav-primary" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>

        <a class="navbar-brand" href="<?php bloginfo('url'); ?>">
          <?php bloginfo('name'); ?>
        </a>
      </div>

      <?php
      if (has_nav_menu('primary_navigation')){
        wp_nav_menu( array(
          'menu'              => 'primary_navigation',
          'theme_location'    => 'primary_navigation',
          'depth'             => 2,
          'container'         => 'div',
          'container_class'   => 'collapse navbar-collapse navbar-ex1-collapse',
          'menu_class'        => 'nav navbar-nav',
          'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
          'walker'            => new wp_bootstrap_navwalker())
        );
      }
      ?>
    </div>
  </nav>
  <div class="container">
  </div>
</header>
