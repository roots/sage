          <nav class="navbar-mini hidden-xs visible-sm visible-md visible-lg" role="navigation">
            <div class="pull-right"><?php if(get_field('language_switcher', 'options') == "show") { do_action('icl_language_selector'); } ?>
            <?php
                if (has_nav_menu('mini_navigation')) :
                  wp_nav_menu(array('theme_location' => 'mini_navigation', 'menu_class' => 'nav nav-pills pull-right'));
                endif;
              ?>
            </div>
          </nav>