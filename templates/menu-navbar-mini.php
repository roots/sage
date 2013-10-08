          <nav class="navbar-mini hidden-xs visible-sm visible-md visible-lg" role="navigation">
            <div class="pull-right">
            <?php
                if (has_nav_menu('mini')) :
                  wp_nav_menu(array('theme_location' => 'mini', 'menu_class' => 'nav nav-pills pull-right'));
                endif;
              ?>
            </div>
          </nav>