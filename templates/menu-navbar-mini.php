          <nav class="navbar-mini hidden-xs visible-sm visible-md visible-lg" role="navigation">
            <div class="pull-right">
            <div><?php do_action(‘icl_language_selector’); ?></div>
            <?php
                if (has_nav_menu('mini')) :
                  wp_nav_menu(array('theme_location' => 'mini', 'menu_class' => 'nav nav-pills pull-right'));
                endif;
              ?>
            
            </div>
          </nav>