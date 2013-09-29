<header class="banner navbar navbar-default navbar-static-top" role="banner">
<div class="inner-grad">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    <style>
      .navbar-brand {
        background-image: url('<?php the_field('white_logo_horizontal', 'options'); ?>');
      }
    </style>
    <a class="visible-xs hidden-sm hidden-md hidden-lg navbar-brand" href="<?php echo home_url(); ?>/">
      <?php bloginfo('name'); ?>
    </a>
    </div>
    <nav class="collapse navbar-collapse" role="navigation">
      <?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav'));
        endif;
      ?>
      <div class="pull-right"><?php
        if (has_nav_menu('mini_navigation')) :
          wp_nav_menu(array('theme_location' => 'mini_navigation', 'menu_class' => 'nav navbar-nav pull-right'));
        endif;
      ?>
      </div>
      <div class="pull-right col-lg-3"><?php get_search_form( $echo ); ?></div>
      <?php
        if (has_nav_menu('user_menu')) :
          wp_nav_menu(array('theme_location' => 'user_menu', 'menu_class' => 'nav navbar-nav pull-right'));
        endif;
      ?>
    </nav>
  </div>
</div>
</header>
