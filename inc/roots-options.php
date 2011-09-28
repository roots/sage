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
	$theme_page = add_theme_page(
		__('Theme Options', 'roots'),
		__('Theme Options', 'roots'),
		'edit_theme_options',
		'theme_options',
		'roots_theme_options_render_page'
	);

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
			'container' => 'twelvecol',
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
		'css_framework'			=> $default_framework,
		'container_class'		=> $default_framework_settings['classes']['container'],
		'main_class'			=> $default_framework_settings['classes']['main'],
		'sidebar_class'			=> $default_framework_settings['classes']['sidebar'],
		'google_analytics_id'	=> '',
		'root_relative_urls'	=> true,
		'clean_menu'			=> true,
		'fout_b_gone'			=> false
	);

	return apply_filters('roots_default_theme_options', $default_theme_options);
}

function roots_get_theme_options() {
	return get_option('roots_theme_options', roots_get_default_theme_options());
}

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
		$output['root_relative_urls'] = ($input['root_relative_urls'] === 'yes') ? true : false;
	}

	if (isset($input['clean_menu'])) {
		$output['clean_menu'] = ($input['clean_menu'] === 'yes') ? true : false;
	}
		
	if (isset($input['fout_b_gone'])) {
		$output['fout_b_gone'] = ($input['fout_b_gone'] === 'yes') ? true : false;
	}

	return apply_filters('roots_theme_options_validate', $output, $input, $defaults);
}

?>