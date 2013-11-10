<nav class="navbar navbar-default hidden-xs visible-sm visible-md visible-lg" role="navigation">
  <div class="inner-grad">
  <?php
  if (has_nav_menu('primary')) :
    wp_nav_menu(array('theme_location' => 'primary', 'menu_class' => 'nav navbar-nav'));
  endif;
  ?>
    <div class="pull-right col-search-form col-xs-12 col-sm-3 col-md-3 col-lg-3">
    <?php get_search_form( $echo ); ?>
    <?php // if(function_exists('woo_predictive_search_widget')) woo_predictive_search_widget(); ?>
    </div>
  </div>
</nav>