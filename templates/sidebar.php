<section class="widget">
  <div class="widget-inner">
<?php 
  $queried_post_type = get_query_var('post_type');
    if ( is_single() && 'product' ==  $queried_post_type || is_post_type_archive('product') ) { ?>
    <?php $menu = 'Products & Services'; ?>
    <nav class="nav-sidebar" role="navigation">
      <?php wp_nav_menu(array('menu' => $menu, 'menu_class' => 'nav nav-list')); ?>
    </nav>
<?php } elseif ( is_single() && 'resource' ==  $queried_post_type || is_post_type_archive('resource') ) { ?>
    <?php $menu = 'Resources'; ?>
    <nav class="nav-sidebar" role="navigation">
      <?php wp_nav_menu(array('menu' => $menu, 'menu_class' => 'nav nav-list')); ?>
    </nav>
<?php } else { ?>

<?php
  /* Sidebar Menu based on Page Title */
  $menu = '';
  if( is_page() ) { 
  	global $post;
          /* Get an array of Ancestors and Parents if they exist */
  	$parents = get_post_ancestors( $post->ID );
          /* Get the top Level page->ID count base 1, array base 0 so -1 */ 
  	$id = ($parents) ? $parents[count($parents)-1]: $post->ID;
  	/* Get the parent and set the $menu with the page title (post_title) */
          $parent = get_page( $id );
  	$menu = $parent->post_title;
  }
  ?>
    <nav class="nav-sidebar" role="navigation">
      <?php wp_nav_menu(array('menu' => $menu, 'menu_class' => 'nav nav-list')); ?>
    </nav>
<?php } ?>
  </div>
</section>
<?php dynamic_sidebar('sidebar-primary'); ?>