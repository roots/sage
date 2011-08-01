<?php

// http://foolswisdom.com/wp-activate-theme-actio/

global $pagenow;
if (is_admin() && $pagenow  === 'themes.php' && isset( $_GET['activated'])) {

	// on theme activation make sure there's a Home page
	// create it if there isn't and set the Home page menu order to -1
	// set WordPress to have the front page display the Home page as a static page
	$default_pages = array('Home');
	$existing_pages = get_pages();
  $temp = array();

	foreach ($existing_pages as $page) {
		$temp[] = $page->post_title;
	}

  $pages_to_create = array_diff($default_pages, $temp);

  foreach ($pages_to_create as $new_page_title) {

		// create post object
		$add_default_pages = array(
      'post_title' => $new_page_title,
      'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum consequat, orci ac laoreet cursus, dolor sem luctus lorem, eget consequat magna felis a magna. Aliquam scelerisque condimentum ante, eget facilisis tortor lobortis in. In interdum venenatis justo eget consequat. Morbi commodo rhoncus mi nec pharetra. Aliquam erat volutpat. Mauris non lorem eu dolor hendrerit dapibus. Mauris mollis nisl quis sapien posuere consectetur. Nullam in sapien at nisi ornare bibendum at ut lectus. Pellentesque ut magna mauris. Nam viverra suscipit ligula, sed accumsan enim placerat nec. Cras vitae metus vel dolor ultrices sagittis. Duis venenatis augue sed risus laoreet congue ac ac leo. Donec fermentum accumsan libero sit amet iaculis. Duis tristique dictum enim, ac fringilla risus bibendum in. Nunc ornare, quam sit amet ultricies gravida, tortor mi malesuada urna, quis commodo dui nibh in lacus. Nunc vel tortor mi. Pellentesque vel urna a arcu adipiscing imperdiet vitae sit amet neque. Integer eu lectus et nunc dictum sagittis. Curabitur commodo vulputate fringilla. Sed eleifend, arcu convallis adipiscing congue, dui turpis commodo magna, et vehicula sapien turpis sit amet nisi.',
      'post_status' => 'publish',
      'post_type' => 'page'
    );

		// insert the post into the database
		$result = wp_insert_post($add_default_pages);	
	}
	
	$home = get_page_by_title('Home');
	update_option('show_on_front', 'page');
	update_option('page_on_front', $home->ID);
	
	$home_menu_order = array(
    'ID' => $home->ID,
    'menu_order' => -1
  );
	wp_update_post($home_menu_order);
	
	// set the permalink structure
	if (get_option('permalink_structure') !== '/%year%/%postname%/') { 
		update_option('permalink_structure', '/%year%/%postname%/');
  }

	$wp_rewrite->init();
	$wp_rewrite->flush_rules();	
	
	// don't organize uploads by year and month
	update_option('uploads_use_yearmonth_folders', 0);
	update_option('upload_path', 'assets');
	
	// automatically create menus and set their locations
	// add all pages to the Primary Navigation
	$roots_nav_theme_mod = false;

	if (!has_nav_menu('primary_navigation')) {
		$primary_nav_id = wp_create_nav_menu('Primary Navigation', array('slug' => 'primary_navigation'));
		$roots_nav_theme_mod['primary_navigation'] = $primary_nav_id;
	}	

	if (!has_nav_menu('utility_navigation')) {
		$utility_nav_id = wp_create_nav_menu('Utility Navigation', array('slug' => 'utility_navigation'));
		$roots_nav_theme_mod['utility_navigation'] = $utility_nav_id;
	}

	if ($roots_nav_theme_mod) { 
    set_theme_mod('nav_menu_locations', $roots_nav_theme_mod);
  }
	
  $primary_nav = wp_get_nav_menu_object('Primary Navigation');

  $primary_nav_term_id = (int) $primary_nav->term_id;
  $menu_items= wp_get_nav_menu_items($primary_nav_term_id);
  if (!$menu_items || empty($menu_items)) {
     $pages = get_pages();
     foreach($pages as $page) {
        $item = array(
           'menu-item-object-id' => $page->ID,
           'menu-item-object' => 'page',
           'menu-item-type' => 'post_type',
           'menu-item-status' => 'publish'
        );
        wp_update_nav_menu_item($primary_nav_term_id, 0, $item);
     }
  }

}

?>