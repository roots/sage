<header class="banner">
  <div class="container">
  	<div class="row">
  	<div class="branding col-xs-12 col-sm-6">
    	<h1>Accident Advice Helpline</h1>
    </div>
  	<div class="menu col-xs-12 col-sm-6 col-md-4 col-lg-2">
    <a class="brand" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
    <nav class="nav-primary">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
      endif;
      ?>
    </nav>
    </div><!-- .menu -->
    </div><!-- .row -->
  </div>
</header>
