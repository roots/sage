        <nav class="navbar navbar-default hidden-xs visible-sm visible-md visible-lg" role="navigation">
          <div class="inner-grad">
            <?php
              if (has_nav_menu('primary_navigation')) :
                wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav'));
              endif;
            ?>
            <div class="pull-right col-xs-12 col-sm-4 col-md-4 col-lg-4">
              <?php get_search_form( $echo ); ?>
            </div>
          </div>
        </nav>