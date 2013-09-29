<?php if (!is_front_page()){ ?>


<?php get_template_part('templates/content', 'logo'); ?>

<?php
  $queried_post_type = get_query_var('post_type');
  $queried_taxonomy = get_query_var('taxonomy');
  
    if ( is_single() && 'product' ==  $queried_post_type || is_post_type_archive('product') || 'product-category' ==  $queried_taxonomy ) { ?>

    <nav class="nav-sidebar" role="navigation">
    <?php
        if (has_nav_menu('products')) :
          wp_nav_menu(array('theme_location' => 'products', 'menu_class' => 'list-group', 'items_wrap' => '<ul class="%2$s">%3$s</ul>'));
        endif;
    ?> 
    </nav>
<?php } elseif ( is_single() && 'service' ==  $queried_post_type || is_post_type_archive('service') ) { ?>
    <nav class="nav-sidebar" role="navigation">
    <?php
        if (has_nav_menu('services')) :
          wp_nav_menu(array('theme_location' => 'services', 'menu_class' => 'list-group', 'items_wrap' => '<ul class="%2$s">%3$s</ul>'));
        endif;
    ?>
    </nav> 
<?php } elseif ( is_single() && 'application' ==  $queried_post_type || is_post_type_archive('application') ) { ?>
    <nav class="nav-sidebar" role="navigation">
    <?php
        if (has_nav_menu('services')) :
          wp_nav_menu(array('theme_location' => 'services', 'menu_class' => 'list-group', 'items_wrap' => '<ul class="%2$s">%3$s</ul>'));
        endif;
    ?>
    </nav>
<?php } elseif ( is_single() && 'resource' ==  $queried_post_type || is_post_type_archive('resource') ) { ?>
    <nav class="nav-sidebar" role="navigation">
    <?php
        if (has_nav_menu('resources')) :
          wp_nav_menu(array('theme_location' => 'resources', 'menu_class' => 'list-group', 'items_wrap' => '<ul class="%2$s">%3$s</ul>'));
        endif;
    ?>
    </nav>
<?php } elseif ( is_single() && 'market' ==  $queried_post_type || is_post_type_archive('market') ) { ?>
    <nav class="nav-sidebar" role="navigation">
    <?php
        if (has_nav_menu('markets')) :
          wp_nav_menu(array('theme_location' => 'markets', 'menu_class' => 'list-group', 'items_wrap' => '<ul class="%2$s">%3$s</ul>'));
        endif;
    ?>
    </nav>
<?php } elseif ( is_page() ) {   ?>
    <nav class="nav-sidebar" role="navigation">
    <?php
        if (has_nav_menu('pages')) :
          wp_nav_menu(array('theme_location' => 'pages', 'menu_class' => 'list-group', 'items_wrap' => '<ul class="%2$s">%3$s</ul>'));
        endif;
    ?>
    </nav>
<?php } elseif ( is_page( 'Products & Services' ) || is_page( 'products-services' ) ) {   ?>

    <nav class="nav-sidebar" role="navigation">
    <?php
        if (has_nav_menu('products')) :
          wp_nav_menu(array('theme_location' => 'products', 'menu_class' => 'list-group', 'items_wrap' => '<ul class="%2$s">%3$s</ul>'));
        endif;
    ?>
    </nav>
<?php } else {   ?>

<?php } ?>


<?php dynamic_sidebar('sidebar-primary'); ?>
<?php get_template_part('templates/content', 'actions-sidebar'); ?>

<?php } ?>