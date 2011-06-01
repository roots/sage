<?php

function roots_admin_enqueue_scripts($hook_suffix) {
	if ($hook_suffix != 'appearance_page_theme_options')
		return;
		
	$home_url = home_url();
	$theme_name = next(explode('/themes/', get_template_directory()));
	
	wp_enqueue_style('roots-theme-options', "$home_url/wp-content/themes/$theme_name/inc/css/theme-options.css");
	wp_enqueue_script('roots-theme-options', "$home_url/wp-content/themes/$theme_name/inc/js/theme-options.js");
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
	add_theme_page(
		__('Theme Options', 'roots'),
		__('Theme Options', 'roots'),
		'edit_theme_options',
		'theme_options',
		'theme_options_render_page'
	);
}
add_action('admin_menu', 'roots_theme_options_add_page');

function roots_css_framework() {
	$framework_options = array(
		'blueprint' => array(
			'value' => 'blueprint',
			'label' => __('Blueprint CSS', 'roots'),
		),
		'960gs_12' => array(
			'value' => '960gs_12',
			'label' => __('960gs (12 cols)', 'roots'),
		),
		'960gs_16' => array(
			'value' => '960gs_16',
			'label' => __('960gs (16 cols)', 'roots'),
		),
		'960gs_24' => array(
			'value' => '960gs_24',
			'label' => __('960gs (24 cols)', 'roots'),
		),				
		'1140' => array(
			'value' => '1140',
			'label' => __('1140', 'roots'),
		),
		'adapt' => array(
			'value' => 'adapt',
			'label' => __('Adapt.js', 'roots'),
		),		
	);

	return apply_filters('roots_css_framework', $framework_options);
}

function roots_get_default_theme_options() {
	$default_theme_options = array(
		'css_framework'			=> 'blueprint',
		'container_class'		=> 'span-24',
		'main_class'			=> 'span-14 append-1',
		'sidebar_class'			=> 'span-8 prepend-1 last',
		'google_analytics_id'	=> ''
	);

	return apply_filters('roots_default_theme_options', $default_theme_options);
}

function roots_get_theme_options() {
	return get_option('roots_theme_options');
}

function theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf(__('%s Theme Options', 'roots'), get_current_theme()); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields('roots_options');
				$roots_options = roots_get_theme_options();
				$roots_default_options = roots_get_default_theme_options();
			?>

			<table class="form-table">

				<tr valign="top" class="radio-option"><th scope="row"><?php _e('CSS Grid Framework', 'roots'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('CSS Grid Framework', 'roots'); ?></span></legend>
						<?php
							foreach (roots_css_framework() as $css_framework) {
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="roots_theme_options[css_framework]" value="<?php echo esc_attr($css_framework['value']); ?>" <?php checked($roots_options['css_framework'], $css_framework['value']); ?> />
									<span>
										<?php echo $css_framework['label']; ?>
									</span>
								</label>
								</div>
								<?php
							}
						?>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e('#main CSS Classes', 'roots'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('#main CSS Classes', 'roots'); ?></span></legend>
							<input type="text" name="roots_theme_options[main_class]" id="main_class" value="<?php echo esc_attr($roots_options['main_class']); ?>" class="regular-text" />
							<br />
							<small class="description"><?php printf( __('Default: %s', 'roots'), $roots_default_options['main_class']); ?></small>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top"><th scope="row"><?php _e('#sidebar CSS Classes', 'roots'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('#sidebar CSS Classes', 'roots'); ?></span></legend>
							<input type="text" name="roots_theme_options[sidebar_class]" id="sidebar_class" value="<?php echo esc_attr($roots_options['sidebar_class']); ?>" class="regular-text" />
							<br />
							<small class="description"><?php printf( __('Default: %s', 'roots'), $roots_default_options['sidebar_class']); ?></small>
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
				
			</table>

			<?php submit_button(); ?>
		</form>
	</div>	
	
	<?php
}

function roots_theme_options_validate($input) {
	$output = $defaults = roots_get_default_theme_options();

	if (isset($input['css_framework']) && array_key_exists($input['css_framework'], roots_css_framework()))
		$output['css_framework'] = $input['css_framework'];	

	// set the value of the main container class depending on the selected grid framework
	if ($output['css_framework'] = 'blueprint') {
		$output['container_class'] = 'span-24';
	}
	if ($output['css_framework'] = '960gs_12') {
		$output['container_class'] = 'container_12';
	}
	if ($output['css_framework'] = '960gs_16') {
		$output['container_class'] = 'container_16';
	}
	if ($output['css_framework'] = '960gs_24') {
		$output['container_class'] = 'container_24';
	}
	if ($output['css_framework'] = '1140') {
		$output['container_class'] = 'container';
	}
	if ($output['css_framework'] = 'adapt') {
		$output['container_class'] = 'container_12 clearfix';
	}
	
	if (isset($input['main_class']))
		$output['main_class'] = $input['main_class'];
		
	if (isset($input['sidebar_class']))
		$output['sidebar_class'] = $input['sidebar_class'];	
		
	if (isset($input['google_analytics_id']))
		$output['google_analytics_id'] = $input['google_analytics_id'];			

	return apply_filters('roots_theme_options_validate', $output, $input, $defaults);
}

?>