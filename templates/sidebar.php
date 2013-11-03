<?php if (!is_front_page()){ ?>

<?php get_template_part('templates/content', 'logo'); ?>

<?php
  $queried_post_type = get_query_var('post_type');
  $queried_taxonomy = get_query_var('taxonomy');
  
            if ( is_single() && 'product' ==  $queried_post_type || is_post_type_archive('product') || 'product_cat' ==  $queried_taxonomy ) { ?>

<?php } elseif ( is_single() && 'service' ==  $queried_post_type || is_post_type_archive('service') ) { ?>

<?php } elseif ( is_single() && 'application' ==  $queried_post_type || is_post_type_archive('application') ) { ?>

<?php } elseif ( is_single() && 'resource' ==  $queried_post_type || is_post_type_archive('resource') ) { ?>

<?php } elseif ( is_single() && 'market' ==  $queried_post_type || is_post_type_archive('market') ) { ?>

<?php } elseif ( is_page() ) {   ?>

<?php } elseif ( is_page( 'Products & Services' ) || is_page( 'products-services' ) ) { ?>

<?php } else {   ?>

<?php } ?>

<?php dynamic_sidebar('sidebar-primary'); ?>
<?php get_template_part('templates/content', 'actions-sidebar'); ?>

<div class="">
<?php get_template_part('templates/content', 'twitter'); ?>
</div>

<?php } ?>