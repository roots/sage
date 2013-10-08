            <nav id="main-footer" class="footer" role="navigation">
              <div class="footer-menu-wrap">
                <span>&copy; <?php echo date('Y'); ?> <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></span>
              <?php if (has_nav_menu('footer')) :
                    wp_nav_menu(array('theme_location' => 'footer', 'menu_class' => 'footer'));
                endif; ?>
              </div>
              <div class="social-menu-wrap pull-right">
              <?php if (has_nav_menu('social')) :
                    wp_nav_menu(array('theme_location' => 'social', 'menu_class' => 'socials'));
                endif; ?>
              </div>
          </nav>