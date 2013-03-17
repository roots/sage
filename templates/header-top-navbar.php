<div id="top-section" class="container">
  <div class="logo">
    <a class="brand" href="<?php echo home_url(); ?>/">
      <?php bloginfo('name'); ?>
    </a>
  </div>
  <?php do_action('icl_language_selector'); ?>
  <nav class="nav-main nav-collapse" role="navigation">
     <?php
            if (has_nav_menu('mini_navigation')) :
              wp_nav_menu(array('theme_location' => 'mini_navigation', 'menu_class' => 'mini-nav'));
            endif;
    ?>
  </nav>
  <header class="banner navbar" role="banner">
    <div class="navbar-inner">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <nav class="nav-main nav-collapse" role="navigation">
          <?php
            if (has_nav_menu('primary_navigation')) :
              wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav'));
            endif;
          ?>
        </nav>
        <form class="navbar-search pull-right" action="">
          <input type="text" class="search-query span2" placeholder="Search">
        </form>
    </div>
  </header>
</div>