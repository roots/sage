<?php 

// create custom plugin settings menu
add_action('admin_menu', 'roots_create_menu');

function roots_create_menu() {
	$home_url = home_url();
	$theme_name = next(explode('/themes/', get_template_directory()));
	$icon = "$home_url/wp-content/themes/$theme_name/includes/img/icon-roots.png";

	// create menu
	$theme_name = get_current_theme();
	add_object_page($theme_name . ' Settings', $theme_name, 'administrator', 'roots', 'roots_settings_page', $icon);
	
	// call register settings function
	add_action('admin_init', 'roots_register_settings');

	// add js
	wp_enqueue_script('jquery-ui-tabs');

	// add css
	add_action('admin_print_styles-toplevel_page_roots', 'roots_admin_styles');
}

function roots_admin_styles() {
	$home_url = home_url();
	$theme_name = next(explode('/themes/', get_template_directory()));

	wp_register_style('roots_options_css', "$home_url/wp-content/themes/$theme_name/includes/css/options.css");
	wp_enqueue_style('roots_options_css');
	
	wp_register_script('roots_options_js', "$home_url/wp-content/themes/$theme_name/includes/js/options.js");
	wp_enqueue_script('roots_options_js');	

	wp_register_style('jquery-ui-css', "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/smoothness/jquery-ui.css");
	wp_enqueue_style('jquery-ui-css');
}

function roots_register_settings() {
	// register our settings
	register_setting('roots-settings-group', 'roots_css_framework');	
	register_setting('roots-settings-group', 'roots_main_class');
	register_setting('roots-settings-group', 'roots_sidebar_class');
	register_setting('roots-settings-group', 'roots_google_analytics');
	register_setting('roots-settings-group', 'roots_post_author');
	register_setting('roots-settings-group', 'roots_post_tweet');
	register_setting('roots-settings-group', 'roots_footer_social_share');
	register_setting('roots-settings-group', 'roots_vcard_street-address');
	register_setting('roots-settings-group', 'roots_vcard_locality');
	register_setting('roots-settings-group', 'roots_vcard_region');
	register_setting('roots-settings-group', 'roots_vcard_postal-code');
	register_setting('roots-settings-group', 'roots_vcard_tel');
	register_setting('roots-settings-group', 'roots_vcard_email');
	register_setting('roots-settings-group', 'roots_footer_vcard');
	
	// add default settings
	add_option('roots_css_framework', 'blueprint');
	add_option('roots_main_class', 'span-14 append-1');
	add_option('roots_sidebar_class', 'span-8 prepend-1 last');	
	add_option('roots_google_analytics', '');	
}

function roots_settings_page() { ?>

<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
  <h2><?php echo get_current_theme(); ?> Settings</h2>
	
<?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true') { ?>
	<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Settings saved.</strong></p></div>
<?php } ?>
	
	<form method="post" action="options.php">
			
		<?php settings_fields('roots-settings-group'); ?>
		
		<div id="tabs">
			<ul>
				<li><a href="#general">General</a></li>
			</ul>
			<div id="general">
				<ul class="options clearfix">	
					<li class="clearfix">
						<label class="settings-label">CSS Grid Framework</label>
						<div class="container">
							<input id="roots_blueprint" name="roots_css_framework" type="radio" <?php echo get_option('roots_css_framework') === 'blueprint' ? 'checked' : ''; ?> value="blueprint" /> <label for="roots_blueprint">Blueprint CSS</label><br />
							<input id="roots_960gs_12" name="roots_css_framework" type="radio" <?php echo get_option('roots_css_framework') === '960gs_12' ? 'checked' : ''; ?> value="960gs_12" /> <label for="roots_960gs_12">960gs (12 cols)</label><br />
							<input id="roots_960gs_16" name="roots_css_framework" type="radio" <?php echo get_option('roots_css_framework') === '960gs_16' ? 'checked' : ''; ?> value="960gs_16" /> <label for="roots_960gs_16">960gs (16 cols)</label><br />
							<input id="roots_960gs_24" name="roots_css_framework" type="radio" <?php echo get_option('roots_css_framework') === '960gs_24' ? 'checked' : ''; ?> value="960gs_24" /> <label for="roots_960gs_24">960gs (24 cols)</label><br />
							<input id="roots_1140" name="roots_css_framework" type="radio" <?php echo get_option('roots_css_framework') === '1140' ? 'checked' : ''; ?> value="1140" /> <label for="roots_1140">1140</label>
						</div>
					</li>
					<li>	
						<label class="settings-label">Class for #main</label>
						<input name="roots_main_class" type="text" value="<?php echo get_option('roots_main_class'); ?>" class="text" />
						<span class="note">Enter your grid classes</span>
					</li>
					<li>
						<label class="settings-label">Class for #sidebar</label>
						<input name="roots_sidebar_class" type="text" value="<?php echo get_option('roots_sidebar_class'); ?>" class="text" />
						<span class="note">Enter your grid classes</span>
					</li>									
					<li>
						<label class="settings-label">Google Analytics Tracking ID</label>
						<input name="roots_google_analytics" type="text" value="<?php echo get_option('roots_google_analytics'); ?>" class="text" />
						<span class="note">Enter your UA-XXXXX-X ID</span>
					</li>
					<li>
						<label class="settings-label">Display Post Author</label>
						<input id="roots_post_author" name="roots_post_author" type="checkbox" <?php echo get_option('roots_post_author') === 'checked' ? 'checked' : ''; ?> value="checked" /> <label for="roots_post_author">Show the post author</label>
					</li>						
					<li>
						<label class="settings-label">Post Tweet Button</label>
						<input id="roots_post_tweet" name="roots_post_tweet" type="checkbox" <?php echo get_option('roots_post_tweet') === 'checked' ? 'checked' : ''; ?> value="checked" /> <label for="roots_post_tweet">Enable Tweet button on posts</label>
					</li>						
					<li>
						<label class="settings-label">Footer Social Share Buttons</label>
						<input id="roots_footer_social_share" name="roots_footer_social_share" type="checkbox" <?php echo get_option('roots_footer_social_share') === 'checked' ?  'checked' : ''; ?> value="checked" /> <label for="roots_footer_social_share">Enable official Twitter and Facebook buttons in the footer</label>
					</li>					
					<li>
						<label class="settings-label">Footer vCard</label>
						<input id="roots_footer_vcard" name="roots_footer_vcard" type="checkbox" <?php echo get_option('roots_footer_vcard') === 'checked' ?  'checked' : ''; ?> value="checked" /> <label for="roots_footer_vcard">Enable vCard in the footer</label>
					</li>											
					<li class="clearfix">
						<label class="settings-label">vCard Information</label>
						<div class="address">
							<label for="roots_vcard_street-address">Street Address</label> <input id="roots_vcard_street-address" name="roots_vcard_street-address" type="text" value="<?php echo get_option('roots_vcard_street-address'); ?>" class="text" />
							<label for="roots_vcard_locality">City</label> <input id="roots_vcard_locality" name="roots_vcard_locality" type="text" value="<?php echo get_option('roots_vcard_locality'); ?>" class="text" />
							<label for="roots_vcard_region">State</label> <input id="roots_vcard_region" name="roots_vcard_region" type="text" value="<?php echo get_option('roots_vcard_region'); ?>" class="text" />
							<label for="roots_vcard_postal-code">Zipcode</label> <input id="roots_vcard_postal-code" name="roots_vcard_postal-code" type="text" value="<?php echo get_option('roots_vcard_postal-code'); ?>" class="text" />
							<label for="roots_vcard_tel">Telephone Number</label> <input id="roots_vcard_tel" name="roots_vcard_tel" type="text" value="<?php echo get_option('roots_vcard_tel'); ?>" class="text" />
							<label for="roots_vcard_email">Email Address</label> <input id="roots_vcard_email" name="roots_vcard_email" type="text" value="<?php echo get_option('roots_vcard_email'); ?>" class="text" />
						</div>
					</li>
				</ul>
			</div>		
		</div>		
		
		<p class="submit">
			<input type="submit" class="button-primary" value="Save Changes" />
		</p>

	</form>
</div>
<?php } ?>
