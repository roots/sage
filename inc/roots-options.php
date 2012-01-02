<?php

function roots_admin_enqueue_scripts($hook_suffix) {
  if ($hook_suffix !== 'appearance_page_theme_options')
    return;

  wp_enqueue_style('roots-theme-options', get_template_directory_uri() . '/inc/css/theme-options.css');
  wp_enqueue_script('roots-theme-options', get_template_directory_uri() . '/inc/js/theme-options.js');
}
add_action('admin_enqueue_scripts', 'roots_admin_enqueue_scripts');

function roots_theme_options_init() {
  if (false === roots_get_theme_options())
    add_option('roots_theme_options', roots_get_default_theme_options());

  register_setting(
    'roots_options',
    'roots_theme_options',
    'roots_theme_options_validate'
  );
}
add_action('admin_init', 'roots_theme_options_init');

function roots_option_page_capability($capability) {
  return 'edit_theme_options';
}
add_filter('option_page_capability_roots_options', 'roots_option_page_capability');

function roots_theme_options_add_page() {
  
  $roots_options = roots_get_theme_options();
  if(!$roots_options['first_install_done']){   
    $theme_page = add_theme_page(
        __('Theme Activation', 'roots'),
        __('Theme Activation', 'roots'),
        'edit_theme_options',
        'theme_activation',
        'roots_theme_activation_render_page'
    ); 
  }
  
  else {
    if ($_GET['page'] == 'theme_activation'){          
          wp_redirect('/wp-admin/themes.php?page=theme_options');
          exit();
    }
    $theme_page = add_theme_page(
        __('Theme Options', 'roots'),
        __('Theme Options', 'roots'),
        'edit_theme_options',
        'theme_options',
        'roots_theme_options_render_page'
    );
  }


  if (!$theme_page)
    return;
}
add_action('admin_menu', 'roots_theme_options_add_page');

function roots_admin_bar_render() {
  global $wp_admin_bar;

  $wp_admin_bar->add_menu(array(
    'parent' => 'appearance',
    'id' => 'theme_options',
    'title' => __('Theme Options', 'roots'),
    'href' => admin_url( 'themes.php?page=theme_options')
  ));
}
add_action('wp_before_admin_bar_render', 'roots_admin_bar_render');

global $roots_css_frameworks;
$roots_css_frameworks = array(
  'blueprint' => array(
    'name'     => 'blueprint',
    'label'     => __('Blueprint CSS', 'roots'),
    'classes'   => array(
      'container' => 'span-24',
      'main'      => 'span-14 append-1',
      'sidebar'   => 'span-8 prepend-1 last'
    )
  ),
  '960gs_12' => array(
    'name'     => '960gs_12',
    'label'   => __('960gs (12 cols)', 'roots'),
    'classes' => array(
      'container' => 'container_12',
      'main'    => 'grid_7 suffix_1',
      'sidebar' => 'grid_4'
    )
  ),
  '960gs_16' => array(
    'name'     => '960gs_16',
    'label'    => __('960gs (16 cols)', 'roots'),
    'classes'  => array(
      'container' => 'container_16',
      'main'    => 'grid_9 suffix_1',
      'sidebar' => 'grid_6'
    )
  ),
  '960gs_24' => array(
    'name'     => '960gs_24',
    'label'   => __('960gs (24 cols)', 'roots'),
    'classes' => array(
      'container' => 'container_24',
      'main'    => 'grid_15 suffix_1',
      'sidebar' => 'grid_8'
    )
  ),
  '1140' => array(
    'name'     => '1140',
    'label'   => __('1140', 'roots'),
    'classes' => array(
      'container' => '',
      'main'    => 'eightcol',
      'sidebar' => 'fourcol last'
    )
  ),
  'adapt' => array(
    'name'     => 'adapt',
    'label'   => __('Adapt.js', 'roots'),
    'classes' => array(
      'container' => 'container_12 clearfix',
      'main'    => 'grid_7 suffix_1',
      'sidebar' => 'grid_4'
    )
  ),
  'less' => array(
    'name'     => 'less',
    'label'   => __('Less Framework 4', 'roots'),
    'classes' => array(
      'container' => 'container',
      'main'    => '',
      'sidebar' => ''
    )
  ),
  'foundation' => array(
    'name'     => 'foundation',
    'label'   => __('Foundation', 'roots'),
    'classes' => array(
      'container' => 'row',
      'main'    => 'eight columns',
      'sidebar' => 'four columns'
    )
  ),
  'bootstrap' => array(
    'name'     => 'bootstrap',
    'label'   => __('Bootstrap', 'roots'),
    'classes' => array(
      'container' => 'row',
      'main'    => 'span11',
      'sidebar' => 'span5'
    )
  ),
  'bootstrap_less' => array(
    'name'     => 'bootstrap_less',
    'label'   => __('Bootstrap w/ Less', 'roots'),
    'classes' => array(
      'container' => 'row',
      'main'    => 'span11',
      'sidebar' => 'span5'
    )
  ),        
  'none' => array(
    'name'     => 'none',
    'label'   => __('None', 'roots'),
    'classes' => array(
      'container' => '',
      'main'    => '',
      'sidebar' => ''
    )
  )
);

// Write the above array of CSS frameworks into a script tag
function roots_add_frameworks_object_script() {
  global $roots_css_frameworks;
  $json = json_encode($roots_css_frameworks);
?>
  <script>
    var roots_css_frameworks = <?php echo $json; ?>;
  </script>
  <?php
}
add_action('admin_head', 'roots_add_frameworks_object_script');

function roots_get_default_theme_options($default_framework = '') {
  global $roots_css_frameworks;
  if ($default_framework == '') { $default_framework = apply_filters('roots_default_css_framework', 'blueprint'); }
  $default_framework_settings = $roots_css_frameworks[$default_framework];
  $default_theme_options = array(
    'css_framework'     => $default_framework,
    'container_class'   => $default_framework_settings['classes']['container'],
    'main_class'      => $default_framework_settings['classes']['main'],
    'sidebar_class'     => $default_framework_settings['classes']['sidebar'],
    'google_analytics_id' => '',
    'root_relative_urls'  => true,
    'clean_menu'      => true,
    'fout_b_gone'     => false,
    'bootstrap_javascript'  => false,
    'bootstrap_less_javascript'  => false,    
    'first_install_done' => false,
    'create_front_page' => false,
    'change_permalink_structure' => false,
    'change_uploads_folder' => false,
    'update_permalink_structure' => false,
    'add_pages_to_primary_navigation' => false,               
  );

  return apply_filters('roots_default_theme_options', $default_theme_options);
}

function roots_get_theme_options() {
  return get_option('roots_theme_options', roots_get_default_theme_options());
}

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
                          settings_fields('roots_options');
                          $roots_options = roots_get_theme_options();
                          $roots_default_options = roots_get_default_theme_options($roots_options['css_framework']);
			?>
                        <input type="hidden" value="1" name="roots_theme_options[first_install_done]" />
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

function roots_theme_options_render_page() {
  global $roots_css_frameworks;

  ?>
  <div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php printf(__('%s Theme Options', 'roots'), get_current_theme()); ?></h2>
    <?php settings_errors(); ?>

    <form method="post" action="options.php">
      <?php
        settings_fields('roots_options');
        $roots_options = roots_get_theme_options();
        $roots_default_options = roots_get_default_theme_options($roots_options['css_framework']);
      ?>
      <input type="hidden" value="1" name="roots_theme_options[first_install_done]" />
      <table class="form-table">

        <tr valign="top" class="radio-option"><th scope="row"><?php _e('CSS Grid Framework', 'roots'); ?></th>
          <td>
            <fieldset class="roots_css_frameworks"><legend class="screen-reader-text"><span><?php _e('CSS Grid Framework', 'roots'); ?></span></legend>
              <select name="roots_theme_options[css_framework]" id="roots_theme_options[css_framework]">
              <?php foreach ($roots_css_frameworks as $css_framework) { ?>
                <option value="<?php echo esc_attr($css_framework['name']); ?>" <?php selected($roots_options['css_framework'], $css_framework['name']); ?>><?php echo $css_framework['label']; ?></option>
              <?php } ?>
              </select>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('#main CSS Classes', 'roots'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('#main CSS Classes', 'roots'); ?></span></legend>
              <input type="text" name="roots_theme_options[main_class]" id="main_class" value="<?php echo esc_attr($roots_options['main_class']); ?>" class="regular-text" />
              <br />
                      <small class="description"><?php _e('Default:', 'roots'); ?> <span><?php echo $roots_default_options['main_class']; ?></span></small>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('#sidebar CSS Classes', 'roots'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('#sidebar CSS Classes', 'roots'); ?></span></legend>
              <input type="text" name="roots_theme_options[sidebar_class]" id="sidebar_class" value="<?php echo esc_attr($roots_options['sidebar_class']); ?>" class="regular-text" />
              <br />
                      <small class="description"><?php _e('Default:', 'roots'); ?> <span><?php echo $roots_default_options['sidebar_class']; ?></span></small>
            </fieldset>
          </td>
        </tr>
          
<?php if($roots_options['css_framework'] == 'bootstrap') { ?>
        <tr valign="top"><th scope="row"><?php _e('Bootstrap Javascript Packages', 'roots'); ?></th>
          <td>
            <fieldset class="roots_bootstrap_js"><legend class="screen-reader-text"><span><?php _e('Enable Bootstrap Javascript', 'roots'); ?></span></legend>
              <select name="roots_theme_options[bootstrap_javascript]" id="roots_theme_options[bootstrap_javascript]">
                <option value="yes" <?php selected($roots_options['bootstrap_javascript'], true); ?>><?php echo _e('Yes', 'roots'); ?></option>
                <option value="no" <?php selected($roots_options['bootstrap_javascript'], false); ?>><?php echo _e('No', 'roots'); ?></option>
              </select>
            </fieldset>
          </td>
        </tr> 
        <?php } ?>
        
<?php if($roots_options['css_framework'] == 'bootstrap_less') { ?>
        <tr valign="top"><th scope="row"><?php _e('Bootstrap Javascript Packages', 'roots'); ?></th>
          <td>
            <fieldset class="roots_bootstrap_js"><legend class="screen-reader-text"><span><?php _e('Enable Bootstrap Javascript', 'roots'); ?></span></legend>
              <select name="roots_theme_options[bootstrap_less_javascript]" id="roots_theme_options[bootstrap_less_javascript]">
                <option value="yes" <?php selected($roots_options['bootstrap_less_javascript'], true); ?>><?php echo _e('Yes', 'roots'); ?></option>
                <option value="no" <?php selected($roots_options['bootstrap_less_javascript'], false); ?>><?php echo _e('No', 'roots'); ?></option>
              </select>
            </fieldset>
          </td>
        </tr> 
        <?php } ?>        
        
        <tr valign="top"><th scope="row"><?php _e('Google Analytics ID', 'roots'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Google Analytics ID', 'roots'); ?></span></legend>
              <input type="text" name="roots_theme_options[google_analytics_id]" id="google_analytics_id" value="<?php echo esc_attr($roots_options['google_analytics_id']); ?>" />
              <br />
              <small class="description"><?php printf(__('Enter your UA-XXXXX-X ID', 'roots')); ?></small>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Enable Root Relative URLs', 'roots'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Enable Root Relative URLs', 'roots'); ?></span></legend>
              <select name="roots_theme_options[root_relative_urls]" id="roots_theme_options[root_relative_urls]">
                <option value="yes" <?php selected($roots_options['root_relative_urls'], true); ?>><?php echo _e('Yes', 'roots'); ?></option>
                <option value="no" <?php selected($roots_options['root_relative_urls'], false); ?>><?php echo _e('No', 'roots'); ?></option>
              </select>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Cleanup Menu Output', 'roots'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Cleanup Menu Output', 'roots'); ?></span></legend>
              <select name="roots_theme_options[clean_menu]" id="roots_theme_options[clean_menu]">
                <option value="yes" <?php selected($roots_options['clean_menu'], true); ?>><?php echo _e('Yes', 'roots'); ?></option>
                <option value="no" <?php selected($roots_options['clean_menu'], false); ?>><?php echo _e('No', 'roots'); ?></option>
              </select>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Enable FOUT-B-Gone', 'roots'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Enable FOUT-B-Gone', 'roots'); ?></span></legend>
              <select name="roots_theme_options[fout_b_gone]" id="roots_theme_options[fout_b_gone]">
                <option value="yes" <?php selected($roots_options['fout_b_gone'], true); ?>><?php echo _e('Yes', 'roots'); ?></option>
                <option value="no" <?php selected($roots_options['fout_b_gone'], false); ?>><?php echo _e('No', 'roots'); ?></option>
              </select>
            </fieldset>
          </td>
        </tr>

      </table>

      <?php submit_button(); ?>
    </form>
  </div>

  <?php
}

function roots_theme_options_validate($input) {
  global $roots_css_frameworks;
  $output = $defaults = roots_get_default_theme_options();

  if (isset($input['css_framework']) && array_key_exists($input['css_framework'], $roots_css_frameworks))
    $output['css_framework'] = $input['css_framework'];

  // set the value of the main container class depending on the selected grid framework
  $output['container_class'] = $roots_css_frameworks[$output['css_framework']]['classes']['container'];

  if (isset($input['main_class'])) {
    $output['main_class'] = wp_filter_nohtml_kses($input['main_class']);
  }

  if (isset($input['sidebar_class'])) {
    $output['sidebar_class'] = wp_filter_nohtml_kses($input['sidebar_class']);
  }

  if (isset($input['google_analytics_id'])) {
    if (preg_match('/^ua-\d{4,9}-\d{1,4}$/i', $input['google_analytics_id'])) {
      $output['google_analytics_id'] = $input['google_analytics_id'];
    }
  }

  if (isset($input['root_relative_urls'])) {
    if ($input['root_relative_urls'] === 'yes') {
      $input['root_relative_urls'] = true;
    }
    if ($input['root_relative_urls'] === 'no') {
      $input['root_relative_urls'] = false;
    }
    $output['root_relative_urls'] = $input['root_relative_urls'];
  }

  if (isset($input['clean_menu'])) {
    if ($input['clean_menu'] === 'yes') {
      $input['clean_menu'] = true;
    }
    if ($input['clean_menu'] === 'no') {
      $input['clean_menu'] = false;
    }
    $output['clean_menu'] = $input['clean_menu'];
  }

  if (isset($input['fout_b_gone'])) {
    if ($input['fout_b_gone'] === 'yes') {
      $input['fout_b_gone'] = true;
    }
    if ($input['fout_b_gone'] === 'no') {
      $input['fout_b_gone'] = false;
    }
    $output['fout_b_gone'] = $input['fout_b_gone'];
  }
  
  if (isset($input['bootstrap_javascript'])) {
    if ($input['bootstrap_javascript'] === 'yes') {
      $input['bootstrap_javascript'] = true;
    }
    if ($input['bootstrap_javascript'] === 'no') {
      $input['bootstrap_javascript'] = false;
    }
    $output['bootstrap_javascript'] = $input['bootstrap_javascript'];
  }

  if (isset($input['bootstrap_less_javascript'])) {
    if ($input['bootstrap_less_javascript'] === 'yes') {
      $input['bootstrap_less_javascript'] = true;
    }
    if ($input['bootstrap_less_javascript'] === 'no') {
      $input['bootstrap_less_javascript'] = false;
    }
    $output['bootstrap_less_javascript'] = $input['bootstrap_less_javascript'];
  }     
  
  if (isset($input['first_install_done'])) {
    if ($input['first_install_done'] === '1') {
      $input['first_install_done'] = true;
    }  
    $output['first_install_done'] = $input['first_install_done'];
  }
  
  return apply_filters('roots_theme_options_validate', $output, $input, $defaults);
}

?>
