<?php

if (is_admin() && isset($_GET['activated']) && 'themes.php' == $GLOBALS['pagenow']) {
  wp_redirect(admin_url('themes.php?page=theme_activation_options'));
  exit;
}

function bc_core_theme_activation_options_init() {
  if (bc_core_get_theme_activation_options() === false) {
    add_option('bc_core_theme_activation_options', bc_core_get_default_theme_activation_options());
  }

  register_setting(
    'bc_core_activation_options',
    'bc_core_theme_activation_options',
    'bc_core_theme_activation_options_validate'
  );
}

add_action('admin_init', 'bc_core_theme_activation_options_init');

function bc_core_activation_options_page_capability($capability) {
  return 'edit_theme_options';
}

add_filter('option_page_capability_bc_core_activation_options', 'bc_core_activation_options_page_capability');

function bc_core_theme_activation_options_add_page() {
  $bc_core_activation_options = bc_core_get_theme_activation_options();
  if (!$bc_core_activation_options['first_run']) {
    $theme_page = add_theme_page(
      __('Theme Activation', 'bc_core'),
      __('Theme Activation', 'bc_core'),
      'edit_theme_options',
      'theme_activation_options',
      'bc_core_theme_activation_options_render_page'
    );
  } else {
    if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'theme_activation_options') {
      global $wp_rewrite;
      $wp_rewrite->flush_rules();
      wp_redirect(admin_url('themes.php'));
      exit;
    }
  }

}

add_action('admin_menu', 'bc_core_theme_activation_options_add_page', 50);

function bc_core_get_default_theme_activation_options() {
  $default_theme_activation_options = array(
    'first_run'                       => false,
    'create_front_page'               => false,
    'change_permalink_structure'      => false,
    'change_uploads_folder'           => false,
    'create_navigation_menus'         => false,
    'add_pages_to_primary_navigation' => false,
  );

  return apply_filters('bc_core_default_theme_activation_options', $default_theme_activation_options);
}

function bc_core_get_theme_activation_options() {
  return get_option('bc_core_theme_activation_options', bc_core_get_default_theme_activation_options());
}

function bc_core_theme_activation_options_render_page() { ?>

  <div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php printf(__('%s Theme Activation', 'bc_core'), wp_get_theme() ); ?></h2>
    <?php settings_errors(); ?>

    <form method="post" action="options.php">

      <?php
        settings_fields('bc_core_activation_options');
        $bc_core_activation_options = bc_core_get_theme_activation_options();
        $bc_core_default_activation_options = bc_core_get_default_theme_activation_options();
      ?>

      <input type="hidden" value="1" name="bc_core_theme_activation_options[first_run]" />

      <table class="form-table">

        <tr valign="top"><th scope="row"><?php _e('Create static front page?', 'bc_core'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Create static front page?', 'bc_core'); ?></span></legend>
              <select name="bc_core_theme_activation_options[create_front_page]" id="create_front_page">
                <option selected="selected" value="yes"><?php echo _e('Yes', 'bc_core'); ?></option>
                <option value="no"><?php echo _e('No', 'bc_core'); ?></option>
              </select>
              <br />
              <small class="description"><?php printf(__('Create a page called Home and set it to be the static front page', 'bc_core')); ?></small>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Change permalink structure?', 'bc_core'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Update permalink structure?', 'bc_core'); ?></span></legend>
              <select name="bc_core_theme_activation_options[change_permalink_structure]" id="change_permalink_structure">
                <option selected="selected" value="yes"><?php echo _e('Yes', 'bc_core'); ?></option>
                <option value="no"><?php echo _e('No', 'bc_core'); ?></option>
              </select>
              <br />
              <small class="description"><?php printf(__('Change permalink structure to /&#37;postname&#37;/', 'bc_core')); ?></small>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Change uploads folder?', 'bc_core'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Update uploads folder?', 'bc_core'); ?></span></legend>
              <select name="bc_core_theme_activation_options[change_uploads_folder]" id="change_uploads_folder">
                <option selected="selected" value="yes"><?php echo _e('Yes', 'bc_core'); ?></option>
                <option value="no"><?php echo _e('No', 'bc_core'); ?></option>
              </select>
              <br />
              <small class="description"><?php printf(__('Change uploads folder to /assets/ instead of /wp-content/uploads/', 'bc_core')); ?></small>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Create navigation menu?', 'bc_core'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Create navigation menu?', 'bc_core'); ?></span></legend>
              <select name="bc_core_theme_activation_options[create_navigation_menus]" id="create_navigation_menus">
                <option selected="selected" value="yes"><?php echo _e('Yes', 'bc_core'); ?></option>
                <option value="no"><?php echo _e('No', 'bc_core'); ?></option>
              </select>
              <br />
              <small class="description"><?php printf(__('Create the Primary Navigation menu and set the location', 'bc_core')); ?></small>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Add pages to menu?', 'bc_core'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Add pages to menu?', 'bc_core'); ?></span></legend>
              <select name="bc_core_theme_activation_options[add_pages_to_primary_navigation]" id="add_pages_to_primary_navigation">
                <option selected="selected" value="yes"><?php echo _e('Yes', 'bc_core'); ?></option>
                <option value="no"><?php echo _e('No', 'bc_core'); ?></option>
              </select>
              <br />
              <small class="description"><?php printf(__('Add all current published pages to the Primary Navigation', 'bc_core')); ?></small>
            </fieldset>
          </td>
        </tr>

      </table>

      <?php submit_button(); ?>
    </form>
  </div>

<?php }

function bc_core_theme_activation_options_validate($input) {
  $output = $defaults = bc_core_get_default_theme_activation_options();

  if (isset($input['first_run'])) {
    if ($input['first_run'] === '1') {
      $input['first_run'] = true;
    }
    $output['first_run'] = $input['first_run'];
  }

  if (isset($input['create_front_page'])) {
    if ($input['create_front_page'] === 'yes') {
      $input['create_front_page'] = true;
    }
    if ($input['create_front_page'] === 'no') {
      $input['create_front_page'] = false;
    }
    $output['create_front_page'] = $input['create_front_page'];
  }

  if (isset($input['change_permalink_structure'])) {
    if ($input['change_permalink_structure'] === 'yes') {
      $input['change_permalink_structure'] = true;
    }
    if ($input['change_permalink_structure'] === 'no') {
      $input['change_permalink_structure'] = false;
    }
    $output['change_permalink_structure'] = $input['change_permalink_structure'];
  }

  if (isset($input['change_uploads_folder'])) {
    if ($input['change_uploads_folder'] === 'yes') {
      $input['change_uploads_folder'] = true;
    }
    if ($input['change_uploads_folder'] === 'no') {
      $input['change_uploads_folder'] = false;
    }
    $output['change_uploads_folder'] = $input['change_uploads_folder'];
  }

  if (isset($input['create_navigation_menus'])) {
    if ($input['create_navigation_menus'] === 'yes') {
      $input['create_navigation_menus'] = true;
    }
    if ($input['create_navigation_menus'] === 'no') {
      $input['create_navigation_menus'] = false;
    }
    $output['create_navigation_menus'] = $input['create_navigation_menus'];
  }

  if (isset($input['add_pages_to_primary_navigation'])) {
    if ($input['add_pages_to_primary_navigation'] === 'yes') {
      $input['add_pages_to_primary_navigation'] = true;
    }
    if ($input['add_pages_to_primary_navigation'] === 'no') {
      $input['add_pages_to_primary_navigation'] = false;
    }
    $output['add_pages_to_primary_navigation'] = $input['add_pages_to_primary_navigation'];
  }

  return apply_filters('bc_core_theme_activation_options_validate', $output, $input, $defaults);
}

function bc_core_theme_activation_action() {
  $bc_core_theme_activation_options = bc_core_get_theme_activation_options();

  if ($bc_core_theme_activation_options['create_front_page']) {
    $bc_core_theme_activation_options['create_front_page'] = false;

    $default_pages = array('Home');
    $existing_pages = get_pages();
    $temp = array();

    foreach ($existing_pages as $page) {
      $temp[] = $page->post_title;
    }

    $pages_to_create = array_diff($default_pages, $temp);

    foreach ($pages_to_create as $new_page_title) {
      $add_default_pages = array(
        'post_title' => $new_page_title,
        'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum consequat, orci ac laoreet cursus, dolor sem luctus lorem, eget consequat magna felis a magna. Aliquam scelerisque condimentum ante, eget facilisis tortor lobortis in. In interdum venenatis justo eget consequat. Morbi commodo rhoncus mi nec pharetra. Aliquam erat volutpat. Mauris non lorem eu dolor hendrerit dapibus. Mauris mollis nisl quis sapien posuere consectetur. Nullam in sapien at nisi ornare bibendum at ut lectus. Pellentesque ut magna mauris. Nam viverra suscipit ligula, sed accumsan enim placerat nec. Cras vitae metus vel dolor ultrices sagittis. Duis venenatis augue sed risus laoreet congue ac ac leo. Donec fermentum accumsan libero sit amet iaculis. Duis tristique dictum enim, ac fringilla risus bibendum in. Nunc ornare, quam sit amet ultricies gravida, tortor mi malesuada urna, quis commodo dui nibh in lacus. Nunc vel tortor mi. Pellentesque vel urna a arcu adipiscing imperdiet vitae sit amet neque. Integer eu lectus et nunc dictum sagittis. Curabitur commodo vulputate fringilla. Sed eleifend, arcu convallis adipiscing congue, dui turpis commodo magna, et vehicula sapien turpis sit amet nisi.',
        'post_status' => 'publish',
        'post_type' => 'page'
      );

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
  }

  if ($bc_core_theme_activation_options['change_permalink_structure']) {
    $bc_core_theme_activation_options['change_permalink_structure'] = false;

    if (get_option('permalink_structure') !== '/%postname%/') {
      update_option('permalink_structure', '/%postname%/');
    }

    global $wp_rewrite;
    $wp_rewrite->init();
    $wp_rewrite->flush_rules();
  }

  if ($bc_core_theme_activation_options['change_uploads_folder']) {
    $bc_core_theme_activation_options['change_uploads_folder'] = false;

    update_option('uploads_use_yearmonth_folders', 0);
    update_option('upload_path', 'assets');
  }

  if ($bc_core_theme_activation_options['create_navigation_menus']) {
    $bc_core_theme_activation_options['create_navigation_menus'] = false;

    $bc_core_nav_theme_mod = false;

    if (!has_nav_menu('primary_navigation')) {
      $primary_nav_id = wp_create_nav_menu('Primary Navigation', array('slug' => 'primary_navigation'));
      $bc_core_nav_theme_mod['primary_navigation'] = $primary_nav_id;
    }

    if ($bc_core_nav_theme_mod) {
      set_theme_mod('nav_menu_locations', $bc_core_nav_theme_mod);
    }
  }

  if ($bc_core_theme_activation_options['add_pages_to_primary_navigation']) {
    $bc_core_theme_activation_options['add_pages_to_primary_navigation'] = false;

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

  update_option('bc_core_theme_activation_options', $bc_core_theme_activation_options);
}

add_action('admin_init','bc_core_theme_activation_action');

function bc_core_deactivation_action() {
  update_option('bc_core_theme_activation_options', bc_core_get_default_theme_activation_options());
}

add_action('switch_theme', 'bc_core_deactivation_action');