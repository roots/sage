<?php

function roots_theme_activation_init() {
//	register_setting('roots_activation');
}
add_action('admin_init', 'roots_theme_activation_init');

function roots_activation_page_capability($capability) {
	return 'edit_theme_options';
}
add_filter('option_page_capability_roots_activation', 'roots_activation_page_capability');

function roots_theme_activation_add_page() {
	$theme_page = add_theme_page(
		__('Theme Activation', 'roots'),
		__('Theme Activation', 'roots'),
		'edit_theme_options',
		'theme_activation',
		'roots_theme_activation_render_page'
	);

	if (!$theme_page)
		return;
}
add_action('admin_menu', 'roots_theme_activation_add_page');

function roots_theme_activation_render_page() { ?>

	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf(__('%s Theme Activation', 'roots'), get_current_theme()); ?></h2>
		<?php settings_errors(); ?>
		
		<div class="updated">
			<p><strong><?php echo _e('Hello.', 'roots'); ?></strong></p>
		</div>
		
		<form method="post" action="options.php">
			<?php
			//	settings_fields('roots_activation');
			?>
					
			<table class="form-table">
			
				<tr valign="top"><th scope="row"><?php _e('Create static front page?', 'roots'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('Create static front page?', 'roots'); ?></span></legend>
							<select name="create_front_page" id="create_front_page">
								<option selected="selected" value="yes"><?php echo _e('Yes', 'roots'); ?></option>
								<option value="no"><?php echo _e('No', 'roots'); ?></option>
							</select>							
							<br />
							<small class="description"><?php printf(__('Create a page called Home and set it to be the static front page', 'roots')); ?></small>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top"><th scope="row"><?php _e('Change permalink structure?', 'roots'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('Update permalink structure?', 'roots'); ?></span></legend>
							<select name="change_permalink_structure" id="change_permalink_structure">
								<option selected="selected" value="yes"><?php echo _e('Yes', 'roots'); ?></option>
								<option value="no"><?php echo _e('No', 'roots'); ?></option>
							</select>
							<br />
							<small class="description"><?php printf(__('Change permalink structure to /&#37;year&#37;/&#37;postname&#37;/', 'roots')); ?></small>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top"><th scope="row"><?php _e('Change uploads folder?', 'roots'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('Update uploads folder?', 'roots'); ?></span></legend>
							<select name="change_uploads_folder" id="change_uploads_folder">
								<option selected="selected" value="yes"><?php echo _e('Yes', 'roots'); ?></option>
								<option value="no"><?php echo _e('No', 'roots'); ?></option>
							</select>							
							<br />
							<small class="description"><?php printf(__('Change uploads folder to /assets/ instead of /wp-content/uploads/', 'roots')); ?></small>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top"><th scope="row"><?php _e('Create navigation menus?', 'roots'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('Update uploads folder?', 'roots'); ?></span></legend>
							<select name="update_permalink_structure" id="update_permalink_structure">
								<option selected="selected" value="yes"><?php echo _e('Yes', 'roots'); ?></option>
								<option value="no"><?php echo _e('No', 'roots'); ?></option>
							</select>							
							<br />
							<small class="description"><?php printf(__('Create the Primary and Utility Navigation menus and set their locations', 'roots')); ?></small>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top"><th scope="row"><?php _e('Add pages to menu?', 'roots'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('Add pages to menu?', 'roots'); ?></span></legend>
							<select name="add_pages_to_primary_navigation" id="add_pages_to_primary_navigation">
								<option selected="selected" value="yes"><?php echo _e('Yes', 'roots'); ?></option>
								<option value="no"><?php echo _e('No', 'roots'); ?></option>
							</select>							
							<br />
							<small class="description"><?php printf(__('Add all current published pages to the Primary Navigation', 'roots')); ?></small>
						</fieldset>
					</td>
				</tr>													
				
			</table>
			
			<?php submit_button(); ?>
		</form>		
	</div>

<?php }

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