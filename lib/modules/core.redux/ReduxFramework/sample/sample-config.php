<?php

/**
	ReduxFramework Sample Config File
	For full documentation, please visit: https://github.com/ReduxFramework/ReduxFramework/wiki
**/

if ( !class_exists( "ReduxFramework" ) ) {
	return;
} 

if ( !class_exists( "Redux_Framework_sample_config" ) ) {
	class Redux_Framework_sample_config {

		public $args = array();
		public $sections = array();
		public $theme;
		public $ReduxFramework;

		public function __construct( ) {

			// Just for demo purposes. Not needed per say.
			$this->theme = wp_get_theme();

			// Set the default arguments
			$this->setArguments();
			
			// Set a few help tabs so you can see how it's done
			$this->setHelpTabs();

			// Create the sections and fields
			$this->setSections();
			
			if ( !isset( $this->args['opt_name'] ) ) { // No errors please
				return;
			}
			
			$this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
			

			// If Redux is running as a plugin, this will remove the demo notice and links
			//add_action( 'redux/plugin/hooks', array( $this, 'remove_demo' ) );
			
			// Function to test the compiler hook and demo CSS output.
			//add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2); 
			// Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.

			// Change the arguments after they've been declared, but before the panel is created
			//add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
			
			// Change the default value of a field after it's been set, but before it's been used
			//add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );

			// Dynamically add a section. Can be also used to modify sections/fields
			add_filter('redux/options/'.$this->args['opt_name'].'/sections', array( $this, 'dynamic_section' ) );

		}


		/**

			This is a test function that will let you see when the compiler hook occurs. 
			It only runs if a field	set with compiler=>true is changed.

		**/

		function compiler_action($options, $css) {
			echo "<h1>The compiler hook has run!";
			//print_r($options); //Option values
			
			// print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
			/*
			// Demo of how to use the dynamic CSS and write your own static CSS file
		    $filename = dirname(__FILE__) . '/style' . '.css';
		    global $wp_filesystem;
		    if( empty( $wp_filesystem ) ) {
		        require_once( ABSPATH .'/wp-admin/includes/file.php' );
		        WP_Filesystem();
		    }

		    if( $wp_filesystem ) {
		        $wp_filesystem->put_contents(
		            $filename,
		            $css,
		            FS_CHMOD_FILE // predefined mode settings for WP files
		        );
		    }
			*/
		}



		/**
		 
		 	Custom function for filtering the sections array. Good for child themes to override or add to the sections.
		 	Simply include this function in the child themes functions.php file.
		 
		 	NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
		 	so you must use get_template_directory_uri() if you want to use any of the built in icons
		 
		 **/

		function dynamic_section($sections){
		    //$sections = array();
		    $sections[] = array(
		        'title' => __('Section via hook', 'redux-framework-demo'),
		        'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo'),
				'icon' => 'el-icon-paper-clip',
				    // Leave this as a blank section, no options just some intro text set above.
		        'fields' => array()
		    );

		    return $sections;
		}
		
		
		/**

			Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

		**/
		
		function change_arguments($args){
		    //$args['dev_mode'] = true;
		    
		    return $args;
		}
			
		
		/**

			Filter hook for filtering the default value of any given field. Very useful in development mode.

		**/

		function change_defaults($defaults){
		    $defaults['str_replace'] = "Testing filter hook!";
		    
		    return $defaults;
		}


		// Remove the demo link and the notice of integrated demo from the redux-framework plugin
		function remove_demo() {
			
			// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
			if ( class_exists('ReduxFrameworkPlugin') ) {
				remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_meta_demo_mode_link'), null, 2 );
			}

			// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
			remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );	

		}


		public function setSections() {

			/**
			 	Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
			 **/


			// Background Patterns Reader
			$sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
			$sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
			$sample_patterns      = array();

			if ( is_dir( $sample_patterns_path ) ) :
				
			  if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
			  	$sample_patterns = array();

			    while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

			      if( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
			      	$name = explode(".", $sample_patterns_file);
			      	$name = str_replace('.'.end($name), '', $sample_patterns_file);
			      	$sample_patterns[] = array( 'alt'=>$name,'img' => $sample_patterns_url . $sample_patterns_file );
			      }
			    }
			  endif;
			endif;

			ob_start();

			$ct = wp_get_theme();
			$this->theme = $ct;
			$item_name = $this->theme->get('Name'); 
			$tags = $this->theme->Tags;
			$screenshot = $this->theme->get_screenshot();
			$class = $screenshot ? 'has-screenshot' : '';

			$customize_title = sprintf( __( 'Customize &#8220;%s&#8221;','redux-framework-demo' ), $this->theme->display('Name') );

			?>
			<div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
				<?php if ( $screenshot ) : ?>
					<?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
					<a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr( $customize_title ); ?>">
						<img src="<?php echo esc_url( $screenshot ); ?>" alt="<?php esc_attr_e( 'Current theme preview' ); ?>" />
					</a>
					<?php endif; ?>
					<img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>" alt="<?php esc_attr_e( 'Current theme preview' ); ?>" />
				<?php endif; ?>

				<h4>
					<?php echo $this->theme->display('Name'); ?>
				</h4>

				<div>
					<ul class="theme-info">
						<li><?php printf( __('By %s','redux-framework-demo'), $this->theme->display('Author') ); ?></li>
						<li><?php printf( __('Version %s','redux-framework-demo'), $this->theme->display('Version') ); ?></li>
						<li><?php echo '<strong>'.__('Tags', 'redux-framework-demo').':</strong> '; ?><?php printf( $this->theme->display('Tags') ); ?></li>
					</ul>
					<p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
					<?php if ( $this->theme->parent() ) {
						printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.' ) . '</p>',
							__( 'http://codex.wordpress.org/Child_Themes','redux-framework-demo' ),
							$this->theme->parent()->display( 'Name' ) );
					} ?>
					
				</div>

			</div>

			<?php
			$item_info = ob_get_contents();
			    
			ob_end_clean();

			$sampleHTML = '';
			if( file_exists( dirname(__FILE__).'/info-html.html' )) {
				/** @global WP_Filesystem_Direct $wp_filesystem  */
				global $wp_filesystem;
				if (empty($wp_filesystem)) {
					require_once(ABSPATH .'/wp-admin/includes/file.php');
					WP_Filesystem();
				}  		
				$sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__).'/info-html.html');
			}




			// ACTUAL DECLARATION OF SECTIONS

			$this->sections[] = array(
				'title' => __('Home Settings', 'redux-framework-demo'),
				'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'redux-framework-demo'),
				'icon' => 'el-icon-home',
			    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
				'fields' => array(	
					array(
						'id'=>'webFonts',
						'type' => 'media', 
						'title' => __('Web Fonts', 'redux-framework-demo'),
						'compiler' => 'true',
						'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
						'desc'=> __('Basic media uploader with disabled URL input field.', 'redux-framework-demo'),
						'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
						),				
					array(
						'id'=>'media',
						'type' => 'media', 
						'url'=> true,
						'title' => __('Media w/ URL', 'redux-framework-demo'),
						'compiler' => 'true',
						//'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
						'desc'=> __('Basic media uploader with disabled URL input field.', 'redux-framework-demo'),
						'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
						'default'=>array('url'=>'http://s.wordpress.org/style/images/codeispoetry.png'),
						),

					array(
						'id'=>'media-nourl',
						'type' => 'media', 
						'title' => __('Media w/o URL', 'redux-framework-demo'),
						'desc'=> __('This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'redux-framework-demo'),
						'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
						),	
					array(
						'id'=>'media-nopreview',
						'type' => 'media', 
						'preview'=> false,
						'title' => __('Media No Preview', 'redux-framework-demo'),
						'desc'=> __('This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'redux-framework-demo'),
						'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
						),			
			        array(
			            'id' => 'gallery',
			            'type' => 'gallery',
			            'title' => __('Add/Edit Gallery', 'so-panels'),
			            'subtitle' => __('Create a new Gallery by selecting existing or uploading new images using the WordPress native uploader', 'so-panels'),
			            'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
			            ),
					array(
						'id'=>'slider1',
						'type' => 'slider', 
						'title' => __('JQuery UI Slider Example 1', 'redux-framework-demo'),
						'desc'=> __('JQuery UI slider description. Min: 1, max: 500, step: 3, default value: 45', 'redux-framework-demo'),
						"default" 		=> "45",
						"min" 		=> "1",
						"step"		=> "3",
						"max" 		=> "500",
						),	

					array(
						'id'=>'slider2',
						'type' => 'slider', 
						'title' => __('JQuery UI Slider Example 2 w/ Steps (5)', 'redux-framework-demo'),
						'desc'=> __('JQuery UI slider description. Min: 0, max: 300, step: 5, default value: 75', 'redux-framework-demo'),
						"default" 		=> "75",
						"min" 		=> "0",
						"step"		=> "5",
						"max" 		=> "300",
						),	
					array(
						'id'=>'spinner1',
						'type' => 'spinner', 
						'title' => __('JQuery UI Spinner Example 1', 'redux-framework-demo'),
						'desc'=> __('JQuery UI spinner description. Min:20, max: 100, step:20, default value: 40', 'redux-framework-demo'),
						"default" 	=> "40",
						"min" 		=> "20",
						"step"		=> "20",
						"max" 		=> "100",
						),
					array(
						'id'=>'switch-on',
						'type' => 'switch', 
						'title' => __('Switch On', 'redux-framework-demo'),
						'subtitle'=> __('Look, it\'s on!', 'redux-framework-demo'),
						"default" 		=> 1,
						),	

					array(
						'id'=>'switch-off',
						'type' => 'switch', 
						'title' => __('Switch Off', 'redux-framework-demo'),
						'subtitle'=> __('Look, it\'s on!', 'redux-framework-demo'),
						"default" 		=> 0,
						),	

					array(
						'id'=>'switch-custom',
						'type' => 'switch', 
						'title' => __('Switch - Custom Titles', 'redux-framework-demo'),
						'subtitle'=> __('Look, it\'s on! Also hidden child elements!', 'redux-framework-demo'),
						"default" 		=> 0,
						'on' => 'Enabled',
						'off' => 'Disabled',
						),	

					array(
						'id'=>'switch-fold',
						'type' => 'switch', 
						'required' => array('switch-custom','=','1'),						
						'title' => __('Switch - With Hidden Items (NESTED!)', 'redux-framework-demo'),
						'subtitle'=> __('Also called a "fold" parent.', 'redux-framework-demo'),
						'desc' => __('Items set with a fold to this ID will hide unless this is set to the appropriate value.', 'redux-framework-demo'),
						'default' => 0,
						),	
					array(
						'id'=>'patterns',
						'type' => 'image_select', 
						'tiles' => true,
						'required' => array('switch-fold','equals','0'),	
						'title' => __('Images Option (with pattern=>true)', 'redux-framework-demo'),
						'subtitle'=> __('Select a background pattern.', 'redux-framework-demo'),
						'default' 		=> 0,
						'options' => $sample_patterns
						,
						),			
			        array(
			            "id" => "homepage_blocks_three",
			            "type" => "sorter",
			            "title" => "Layout Manager Advanced",
			            "subtitle" => "You can add multiple drop areas or columns.",
			            "compiler"=>'true',
			            'required' => array('switch-fold','equals','0'),	
			            'options' => array(
			                "enabled" => array(
			                    "placebo" => "placebo", //REQUIRED!
			                    "highlights" => "Highlights",
			                    "slider" => "Slider",
			                    "staticpage" => "Static Page",
			                    "services" => "Services"
			                ),
			                "disabled" => array(
			                    "placebo" => "placebo", //REQUIRED!
			                ),
			                "backup" => array(
			                    "placebo" => "placebo", //REQUIRED!
			                ),                
			            ),
			        ),
			        array(
			            "id" => "homepage_blocks",
			            "type" => "sorter",
			            "title" => "Homepage Layout Manager",
			            "desc" => "Organize how you want the layout to appear on the homepage",
			            "compiler"=>'true',
			            'options' => array(
			                "enabled" => array(
			                    "placebo" => "placebo", //REQUIRED!
			                    "highlights" => "Highlights",
			                    "slider" => "Slider",
			                    "staticpage" => "Static Page",
			                    "services" => "Services"
			                ),
			                "disabled" => array(
			                    "placebo" => "placebo", //REQUIRED!
			                ),
			            ),
			        ),        
					array(
						'id'=>'slides',
						'type' => 'slides',
						'title' => __('Slides Options', 'redux-framework-demo'),
						'subtitle'=> __('Unlimited slides with drag and drop sortings.', 'redux-framework-demo'),
						'desc' => __('This field will store all slides values into a multidimensional array to use into a foreach loop.', 'redux-framework-demo')
					),        
					array(
						'id'=>'slides',
						'type' => 'slides',
						'title' => __('Slides Options', 'redux-framework-demo'),
						'subtitle'=> __('Unlimited slides with drag and drop sortings.', 'redux-framework-demo'),
						'options'=>array(
							'flash' => 'flash',
							'bounce' => 'bounce',
							'shake' => 'shake',
							'tada' => 'tada',
							'wobble' => 'wobble',
							'pulse' => 'pulse',
							'flip' => 'flip',
							'flipInX' => 'flipInX',
							'flipOutX' => 'flipOutX',
							'flipInY' => 'flipInY',
							'flipOutY' => 'flipOutY',
							'fadeIn' => 'fadeIn',
							'fadeInUp' => 'fadeInUp',
							'fadeInDown' => 'fadeInDown',
							'fadeInLeft' => 'fadeInLeft',
							'fadeInRight' => 'fadeInRight',
							'fadeInUpBig' => 'fadeInUpBig',
							'fadeInDownBig' => 'fadeInDownBig',
							'fadeInLeftBig' => 'fadeInLeftBig',
							'fadeInRightBig' => 'fadeInRightBig',
							'fadeOut' => 'fadeOut',
							'fadeOutUp' => 'fadeOutUp',
							'fadeOutDown' => 'fadeOutDown',
							'fadeOutLeft' => 'fadeOutLeft',
							'fadeOutRight' => 'fadeOutRight',
							'fadeOutUpBig' => 'fadeOutUpBig',
							'fadeOutDownBig' => 'fadeOutDownBig',
							'fadeOutLeftBig' => 'fadeOutLeftBig',
							'fadeOutRightBig' => 'fadeOutRightBig',
							'slideInDown' => 'slideInDown',
							'slideInLeft' => 'slideInLeft',
							'slideInRight' => 'slideInRight',
							'slideOutUp' => 'slideOutUp',
							'slideOutLeft' => 'slideOutLeft',
							'slideOutRight' => 'slideOutRight',
							'bounceIn' => 'bounceIn',
							'bounceInDown' => 'bounceInDown',
							'bounceInUp' => 'bounceInUp',
							'bounceInLeft' => 'bounceInLeft',
							'bounceInRight' => 'bounceInRight',
							'bounceOut' => 'bounceOut',
							'bounceOutDown' => 'bounceOutDown',
							'bounceOutUp' => 'bounceOutUp',
							'bounceOutLeft' => 'bounceOutLeft',
							'bounceOutRight' => 'bounceOutRight',
							'rotateIn' => 'rotateIn',
							'rotateInDownLeft' => 'rotateInDownLeft',
							'rotateInDownRight' => 'rotateInDownRight',
							'rotateInUpLeft' => 'rotateInUpLeft',
							'rotateInUpRight' => 'rotateInUpRight',
							'rotateOut' => 'rotateOut',
							'rotateOutDownLeft' => 'rotateOutDownLeft',
							'rotateOutDownRight' => 'rotateOutDownRight',
							'rotateOutUpLeft' => 'rotateOutUpLeft',
							'rotateOutUpRight' => 'rotateOutUpRight',
							'lightSpeedIn' => 'lightSpeedIn',
							'lightSpeedOut' => 'lightSpeedOut',
							'hinge' => 'hinge',
							'rollIn' => 'rollIn',
							'rollOut' => 'rollOut'
						),
						'placeholder' => array(
							'title'=>"This is the title",
							'description'=>"Description here",
							'url'=>"Link",
							'select'=>"Select an Animation",
						),
						// 'select2' => array() // Select 2 options
						'desc' => __('This field will store all slides values into a multidimensional array to use into a foreach loop.', 'redux-framework-demo')
					),
					array(
						'id'=>'presets',
						'type' => 'image_select', 
						'presets' => true,
						'title' => __('Preset', 'redux-framework-demo'),
						'subtitle'=> __('This allows you to set a json string or array to override multiple preferences in your theme.', 'redux-framework-demo'),
						'default' 		=> 0,
						'desc'=> __('This allows you to set a json string or array to override multiple preferences in your theme.', 'redux-framework-demo'),
						'options' => array(
										'1' => array('alt' => 'Preset 1', 'img' => ReduxFramework::$_url.'../sample/presets/preset1.png', 'presets'=>array('switch-on'=>1,'switch-off'=>1, 'switch-custom'=>1)),
										'2' => array('alt' => 'Preset 2', 'img' => ReduxFramework::$_url.'../sample/presets/preset2.png', 'presets'=>'{"slider1":"1", "slider2":"0", "switch-on":"0"}'),
											),
						),							
					array(
						'id'=>'typography6',
						'type' => 'typography', 
						'title' => __('Typography', 'redux-framework-demo'),
						//'compiler'=>true, // Use if you want to hook in your own CSS compiler
						'google'=>true, // Disable google fonts. Won't work if you haven't defined your google api key
						'font-backup'=>true, // Select a backup non-google font in addition to a google font
						//'font-style'=>false, // Includes font-style and weight. Can use font-style or font-weight to declare
						//'subsets'=>false, // Only appears if google is true and subsets not set to false
						//'font-size'=>false,
						//'line-height'=>false,
						//'word-spacing'=>true, // Defaults to false
						//'letter-spacing'=>true, // Defaults to false
						//'color'=>false,
						//'preview'=>false, // Disable the previewer
						'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
						'output' => array('h2.site-description'), // An array of CSS selectors to apply this font style to dynamically
						'compiler' => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
						'units'=>'px', // Defaults to px
						'subtitle'=> __('Typography option with each property can be called individually.', 'redux-framework-demo'),
						'default'=> array(
							'color'=>"#333", 
							'font-style'=>'700', 
							'font-family'=>'Abel', 
							'google' => true,
							'font-size'=>'33px', 
							'line-height'=>'40px'),
						),	
					),
				);



			$this->sections[] = array(
				'type' => 'divide',
			);



			$this->sections[] = array(
				'icon' => 'el-icon-cogs',
				'title' => __('General Settings', 'redux-framework-demo'),
				'fields' => array(
					array(
						'id'=>'layout',
						'type' => 'image_select',
						'compiler'=>true,
						'title' => __('Main Layout', 'redux-framework-demo'), 
						'subtitle' => __('Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'redux-framework-demo'),
						'options' => array(
								'1' => array('alt' => '1 Column', 'img' => ReduxFramework::$_url.'assets/img/1col.png'),
								'2' => array('alt' => '2 Column Left', 'img' => ReduxFramework::$_url.'assets/img/2cl.png'),
								'3' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url.'assets/img/2cr.png'),
								'4' => array('alt' => '3 Column Middle', 'img' => ReduxFramework::$_url.'assets/img/3cm.png'),
								'5' => array('alt' => '3 Column Left', 'img' => ReduxFramework::$_url.'assets/img/3cl.png'),
								'6' => array('alt' => '3 Column Right', 'img' => ReduxFramework::$_url.'assets/img/3cr.png')
							),
						'default' => '2'
						),

					array(
						'id'=>'tracking-code',
						'type' => 'textarea',
						'required' => array('layout','equals','1'),	
						'title' => __('Tracking Code', 'redux-framework-demo'), 
						'subtitle' => __('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'redux-framework-demo'),
						'validate' => 'js',
						'desc' => 'Validate that it\'s javascript!',
						),
			        
			        array(
						'id'=>'css-code',
						'type' => 'ace_editor',
						'title' => __('CSS Code', 'redux-framework-demo'), 
						'subtitle' => __('Paste your CSS code here.', 'redux-framework-demo'),
						'mode' => 'css',
			            'theme' => 'monokai',
						'desc' => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
			            'default' => "#header{\nmargin: 0 auto;\n}"
						),
			        array(
						'id'=>'js-code',
						'type' => 'ace_editor',
						'title' => __('JS Code', 'redux-framework-demo'), 
						'subtitle' => __('Paste your JS code here.', 'redux-framework-demo'),
						'mode' => 'javascript',
			            'theme' => 'chrome',
						'desc' => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
			            'default' => "jQuery(document).ready(function(){\n\n});"
						),

					array(
						'id'=>'footer-text',
						'type' => 'editor',
						'title' => __('Footer Text', 'redux-framework-demo'), 
						'subtitle' => __('You can use the following shortcodes in your footer text: [wp-url] [site-url] [theme-url] [login-url] [logout-url] [site-title] [site-tagline] [current-year]', 'redux-framework-demo'),
						'default' => 'Powered by [wp-url]. Built on the [theme-url].',
						),
					array(
						'id'          => 'password',
						'type'        => 'password',
						'username'    => true,
						'title'       => 'SMTP Account',
						//'placeholder' => array('username' => 'Enter your Username')
					)
				)
			);




			$this->sections[] = array(
				'icon' => 'el-icon-website',
				'title' => __('Styling Options', 'redux-framework-demo'),
				'fields' => array(
					array(
						'id'=>'stylesheet',
						'type' => 'select',
						'title' => __('Theme Stylesheet', 'redux-framework-demo'), 
						'subtitle' => __('Select your themes alternative color scheme.', 'redux-framework-demo'),
						'options' => array('default.css'=>'default.css', 'color1.css'=>'color1.css'),
						'default' => 'default.css',
						),
					array(
						'id'=>'color-background',
						'type' => 'color',
						'output' => array('.site-title'),
						'title' => __('Body Background Color', 'redux-framework-demo'), 
						'subtitle' => __('Pick a background color for the theme (default: #fff).', 'redux-framework-demo'),
						'default' => '#FFFFFF',
						'validate' => 'color',
						),		
					array(
						'id'=>'color-footer',
						'type' => 'color',
						'title' => __('Footer Background Color', 'redux-framework-demo'), 
						'subtitle' => __('Pick a background color for the footer (default: #dd9933).', 'redux-framework-demo'),
						'default' => '#dd9933',
						'validate' => 'color',
						),
					array(
						'id'=>'color-rgba',
						'type' => 'color_rgba',
						'title' => __('Color RGBA - BETA', 'redux-framework-demo'), 
						'subtitle' => __('Gives you the RGBA color. Still quite experimental. Use at your own risk.', 'redux-framework-demo'),
						'default' => array( 'color' => '#dd9933', 'alpha' => '1.0' ),
						'validate' => 'colorrgba',
						),			
					array(
						'id'=>'color-header',
						'type' => 'color_gradient',
						'title' => __('Header Gradient Color Option', 'redux-framework-demo'),
						'subtitle' => __('Only color validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'default' => array('from' => '#1e73be', 'to' => '#00897e')
						),
					array(
						'id'=>'link-color',
						'type' => 'link_color',
						'title' => __('Links Color Option', 'redux-framework-demo'),
						'subtitle' => __('Only color validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						//'regular' => false, // Disable Regular Color
						//'hover' => false, // Disable Hover Color
						//'active' => false, // Disable Active Color
						//'visited' => true, // Enable Visited Color
						'default' => array(
							'regular' => '#aaa',
							'hover' => '#bbb',
							'active' => '#ccc',
						)
					),
					array(
						'id'=>'header-border',
						'type' => 'border',
						'title' => __('Header Border Option', 'redux-framework-demo'),
						'subtitle' => __('Only color validation can be done on this field type', 'redux-framework-demo'),
						'output' => array('.site-header'), // An array of CSS selectors to apply this font style to
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'default' => array('border-color' => '#1e73be', 'border-style' => 'solid', 'border-top'=>'3px', 'border-right'=>'3px', 'border-bottom'=>'3px', 'border-left'=>'3px')
						),	
					array(
						'id'=>'spacing',
						'type' => 'spacing',
						'output' => array('.site-header'), // An array of CSS selectors to apply this font style to
						'mode'=>'margin', // absolute, padding, margin, defaults to padding
						'top'=>false, // Disable the top
						//'right' => false, // Disable the right
						//'bottom' => false, // Disable the bottom
						//'left' => false, // Disable the left
						//'all' => true, // Have one field that applies to all
						//'units' => 'em', // You can specify a unit value. Possible: px, em, %
						//'units_extended' => 'true', // Allow users to select any type of unit
						//'display_units' => 'false', // Set to false to hide the units if the units are specified
						'title' => __('Padding/Margin Option', 'redux-framework-demo'),
						'subtitle' => __('Allow your users to choose the spacing or margin they want.', 'redux-framework-demo'),
						'desc' => __('You can enable or disable any piece of this field. Top, Right, Bottom, Left, or Units.', 'redux-framework-demo'),
						'default' => array('margin-top' => '1px', 'margin-right'=>"2px", 'margin-bottom' => '3px', 'margin-left'=>'4px' )
						),	
					array(
						'id'=>'dimensions',
						'type' => 'dimensions',
						//'units' => 'em', // You can specify a unit value. Possible: px, em, %
						//'units_extended' => 'true', // Allow users to select any type of unit
						'title' => __('Dimensions (Width/Height) Option', 'redux-framework-demo'),
						'subtitle' => __('Allow your users to choose width, height, and/or unit.', 'redux-framework-demo'),
						'desc' => __('You can enable or disable any piece of this field. Width, Height, or Units.', 'redux-framework-demo'),
						'default' => array('width' => 200, 'height'=>'100', )
						),												
					array(
						'id'=>'body-font2',
						'type' => 'typography',
						'title' => __('Body Font', 'redux-framework-demo'),
						'subtitle' => __('Specify the body font properties.', 'redux-framework-demo'),
						'google'=>true,
						'default' => array(
							'color'=>'#dd9933',
							'font-size'=>'30px',
							'font-family'=>'Arial, Helvetica, sans-serif',
							'font-weight'=>'Normal',
							),
						),					
					array(
						'id'=>'custom-css',
						'type' => 'textarea',
						'title' => __('Custom CSS', 'redux-framework-demo'), 
						'subtitle' => __('Quickly add some CSS to your theme by adding it to this block.', 'redux-framework-demo'),
						'desc' => __('This field is even CSS validated!', 'redux-framework-demo'),
						'validate' => 'css',
						),
					array(
						'id'=>'custom-html',
						'type' => 'textarea',
						'title' => __('Custom HTML', 'redux-framework-demo'), 
						'subtitle' => __('Just like a text box widget.', 'redux-framework-demo'),
						'desc' => __('This field is even HTML validated!', 'redux-framework-demo'),
						'validate' => 'html',
						),		
				)
			);
				
			/**
			 *  Note here I used a 'heading' in the sections array construct
			 *  This allows you to use a different title on your options page
			 * instead of reusing the 'title' value.  This can be done on any 
			 * section - kp
			 */
			$this->sections[] = array(
				'icon'    => 'el-icon-bullhorn',
				'title'   => __('Field Validation', 'redux-framework-demo'),
				'heading' => __('Validate ALL fields within Redux.', 'redux-framework-demo'),
				'desc'    => __('<p class="description">This is the Description. Again HTML is allowed2</p>', 'redux-framework-demo'),
				'fields'  => array(
					array(
						'id'=>'2',
						'type' => 'text',
						'title' => __('Text Option - Email Validated', 'redux-framework-demo'),
						'subtitle' => __('This is a little space under the Field Title in the Options table, additional info is good in here.', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'validate' => 'email',
						'msg' => 'custom error message',
						'default' => 'test@test.com'
						),	
					array(
						'id'=>'2test',
						'type' => 'text',
						'title' => __('Text Option with Data Attributes', 'redux-framework-demo'),
						'subtitle' => __('You can also pass an options array if you want. Set the default to whatever you like.', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'data' => 'post_type',
						//'options' => array(1=>'One', 2=>'Two'),
						//'default' => array(1=>'Onee', 2=>'Twoo'),
						),						
					array(
						'id'=>'multi_text',
						'type' => 'multi_text',
						'title' => __('Multi Text Option - Color Validated', 'redux-framework-demo'),
						'validate' => 'color',
						'subtitle' => __('If you enter an invalid color it will be removed. Try using the text "blue" as a color.  ;)', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo')
						),
					array(
						'id'=>'3',
						'type' => 'text',
						'title' => __('Text Option - URL Validated', 'redux-framework-demo'),
						'subtitle' => __('This must be a URL.', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'validate' => 'url',
						'default' => 'http://reduxframework.com'
						),
					array(
						'id'=>'4',
						'type' => 'text',
						'title' => __('Text Option - Numeric Validated', 'redux-framework-demo'),
						'subtitle' => __('This must be numeric.', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'validate' => 'numeric',
						'default' => '0',
						'class' => 'small-text'
						),
					array(
						'id'=>'comma_numeric',
						'type' => 'text',
						'title' => __('Text Option - Comma Numeric Validated', 'redux-framework-demo'),
						'subtitle' => __('This must be a comma separated string of numerical values.', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'validate' => 'comma_numeric',
						'default' => '0',
						'class' => 'small-text'
						),
					array(
						'id'=>'no_special_chars',
						'type' => 'text',
						'title' => __('Text Option - No Special Chars Validated', 'redux-framework-demo'),
						'subtitle' => __('This must be a alpha numeric only.', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'validate' => 'no_special_chars',
						'default' => '0'
						),
					array(
						'id'=>'str_replace',
						'type' => 'text',
						'title' => __('Text Option - Str Replace Validated', 'redux-framework-demo'),
						'subtitle' => __('You decide.', 'redux-framework-demo'),
						'desc' => __('This field\'s default value was changed by a filter hook!', 'redux-framework-demo'),
						'validate' => 'str_replace',
						'str' => array('search' => ' ', 'replacement' => 'thisisaspace'),
						'default' => 'This is the default.'
						),
					array(
						'id'=>'preg_replace',
						'type' => 'text',
						'title' => __('Text Option - Preg Replace Validated', 'redux-framework-demo'),
						'subtitle' => __('You decide.', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'validate' => 'preg_replace',
						'preg' => array('pattern' => '/[^a-zA-Z_ -]/s', 'replacement' => 'no numbers'),
						'default' => '0'
						),
					array(
						'id'=>'custom_validate',
						'type' => 'text',
						'title' => __('Text Option - Custom Callback Validated', 'redux-framework-demo'),
						'subtitle' => __('You decide.', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'validate_callback' => 'redux_validate_callback_function',
						'default' => '0'
						),
					array(
						'id'=>'5',
						'type' => 'textarea',
						'title' => __('Textarea Option - No HTML Validated', 'redux-framework-demo'), 
						'subtitle' => __('All HTML will be stripped', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'validate' => 'no_html',
						'default' => 'No HTML is allowed in here.'
						),
					array(
						'id'=>'6',
						'type' => 'textarea',
						'title' => __('Textarea Option - HTML Validated', 'redux-framework-demo'), 
						'subtitle' => __('HTML Allowed (wp_kses)', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
						'default' => 'HTML is allowed in here.'
						),
					array(
						'id'=>'7',
						'type' => 'textarea',
						'title' => __('Textarea Option - HTML Validated Custom', 'redux-framework-demo'), 
						'subtitle' => __('Custom HTML Allowed (wp_kses)', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'validate' => 'html_custom',
						'default' => '<p>Some HTML is allowed in here.</p>',
						'allowed_html' => array('') //see http://codex.wordpress.org/Function_Reference/wp_kses
						),
					array(
						'id'=>'8',
						'type' => 'textarea',
						'title' => __('Textarea Option - JS Validated', 'redux-framework-demo'), 
						'subtitle' => __('JS will be escaped', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'validate' => 'js'
						),

					)
				);
			$this->sections[] = array(
				'icon' => 'el-icon-check',
				'title' => __('Radio/Checkbox Fields', 'redux-framework-demo'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
				'fields' => array(
					array(
						'id'=>'10',
						'type' => 'checkbox',
						'title' => __('Checkbox Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'default' => '1'// 1 = on | 0 = off
						),
					array(
						'id'=>'11',
						'type' => 'checkbox',
						'title' => __('Multi Checkbox Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for multi checkbox options
						'default' => array('1' => '1', '2' => '0', '3' => '0')//See how std has changed? you also don't need to specify opts that are 0.
						),
					array(
						'id'=>'checkbox-data',
						'type' => 'checkbox',
						'title' => __('Multi Checkbox Option (with menu data)', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'data' => "menu"
						),	
					array(
						'id'=>'checkbox-sidebar',
						'type' => 'checkbox',
						'title' => __('Multi Checkbox Option (with sidebar data)', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'data' => "sidebars"
						),								
					array(
						'id'=>'12',
						'type' => 'radio',
						'title' => __('Radio Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'options' => array('1' => 'Opt 1', '2' => 'Opt 2', '3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'default' => '2'
						),
					array(
						'id'=>'radio-data',
						'type' => 'radio',
						'title' => __('Multi Checkbox Option (with menu data)', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'data' => "menu"
						),					
					array(
						'id'=>'13',
						'type' => 'image_select',
						'title' => __('Images Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'options' => array(
										'1' => array('title' => 'Opt 1', 'img' => 'images/align-none.png'),
										'2' => array('title' => 'Opt 2', 'img' => 'images/align-left.png'),
										'3' => array('title' => 'Opt 3', 'img' => 'images/align-center.png'),
										'4' => array('title' => 'Opt 4', 'img' => 'images/align-right.png')
											),//Must provide key => value(array:title|img) pairs for radio options
						'default' => '2'
						),
					array(
						'id'=>'image_select',
						'type' => 'image_select',
						'title' => __('Images Option for Layout', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This uses some of the built in images, you can use them for layout options.', 'redux-framework-demo'),
						'options' => array(
										'1' => array('alt' => '1 Column', 'img' => ReduxFramework::$_url.'assets/img/1col.png'),
										'2' => array('alt' => '2 Column Left', 'img' => ReduxFramework::$_url.'assets/img/2cl.png'),
										'3' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url.'assets/img/2cr.png'),
										'4' => array('alt' => '3 Column Middle', 'img' => ReduxFramework::$_url.'assets/img/3cm.png'),
										'5' => array('alt' => '3 Column Left', 'img' => ReduxFramework::$_url.'assets/img/3cl.png'),
										'6' => array('alt' => '3 Column Right', 'img' => ReduxFramework::$_url.'assets/img/3cr.png')
											),//Must provide key => value(array:title|img) pairs for radio options
						'default' => '2'
						),
					array(
			            'id' => 'text_sortable',
				        'type' => 'sortable',
			    	    'title' => __('Sortable Text Option', 'redux-framework-demo'),
			        	'subtitle' => __('Define and reorder these however you want.', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
			            'options' => array(
				            'si1' => 'Item 1',
			    	        'si2' => 'Item 2',
			        	    'si3' => 'Item 3',
			    	    	)
			        	),	
					array(
			            'id' => 'check_sortable',
				        'type' => 'sortable',
				        'mode' => 'checkbox', // checkbox or text
			    	    'title' => __('Sortable Text Option', 'redux-framework-demo'),
			        	'subtitle' => __('Define and reorder these however you want.', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
			            'options' => array(
				            'si1' => 'Item 1',
			    	        'si2' => 'Item 2',
			        	    'si3' => 'Item 3',
			    	    	)
			        	),	        																						
					)
				);
			$this->sections[] = array(
				'icon' => 'el-icon-list-alt',
				'title' => __('Select Fields', 'redux-framework-demo'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
				'fields' => array(
					array(
						'id'=>'select',
						'type' => 'select',
						'title' => __('Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for select options
						'default' => '2'
						),
					array(
						'id'=>'15',
						'type' => 'select',
						'multi' => true,
						'title' => __('Multi Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'required' => array('select','equals',array('1','3')),	
						'default' => array('2','3')
						),

				        array(
				            'id'        => 'opt_sel_img',
				            'type'      => 'select_image',
				            'title'     => __('Select Image', 'redux-framework-demo'), 
				            'subtitle'  => __('A preview of the selected image will appear underneath the select box.', 'redux-framework-demo'),
				            'options'   => $sample_patterns,
				            // Alternatively
				            //'options' => Array(
				            //                 'img_name' => 'img_path'
				            //             )
				            'default'   => 'tree_bark.png',
				        ),
						
					array(
						'id'=>'multi-info',
						'type' => 'info',
						'desc' => __('You can easily add a variety of data from WordPress.', 'redux-framework-demo'),
						),
					array(
						'id'=>'select-categories',
						'type' => 'select',
						'data' => 'categories',
						'title' => __('Categories Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),
					array(
						'id'=>'select-categories-multi',
						'type' => 'select',
						'data' => 'categories',
						'multi' => true,
						'title' => __('Categories Multi Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),
					array(
						'id'=>'select-pages',
						'type' => 'select',
						'data' => 'pages',
						'title' => __('Pages Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),
					array(
						'id'=>'pages-multi_select',
						'type' => 'select',
						'data' => 'pages',
						'multi' => true,
						'title' => __('Pages Multi Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),	
					array(
						'id'=>'select-tags',
						'type' => 'select',
						'data' => 'tags',
						'title' => __('Tags Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),
					array(
						'id'=>'tags-multi_select',
						'type' => 'select',
						'data' => 'tags',
						'multi' => true,
						'title' => __('Tags Multi Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),	
					array(
						'id'=>'select-menus',
						'type' => 'select',
						'data' => 'menus',
						'title' => __('Menus Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),
					array(
						'id'=>'menus-multi_select',
						'type' => 'select',
						'data' => 'menu',
						'multi' => true,
						'title' => __('Menus Multi Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),	
					array(
						'id'=>'select-post-type',
						'type' => 'select',
						'data' => 'post_type',
						'title' => __('Post Type Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),
					array(
						'id'=>'post-type-multi_select',
						'type' => 'select',
						'data' => 'post_type',
						'multi' => true,
						'title' => __('Post Type Multi Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),
					array(
						'id'=>'post-type-multi_select_sortable',
						'type' => 'select',
						'data' => 'post_type',
						'multi' => true,
						'sortable' => true,
						'title' => __('Post Type Multi Select Option + Sortable', 'redux-framework-demo'), 
						'subtitle' => __('This field also has sortable enabled!', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),					
					array(
						'id'=>'select-posts',
						'type' => 'select',
						'data' => 'post',
						'title' => __('Posts Select Option2', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),
					array(
						'id'=>'select-posts-multi',
						'type' => 'select',
						'data' => 'post',
						'multi' => true,
						'title' => __('Posts Multi Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),
			        array(
						'id'=>'select-roles',
						'type' => 'select',
						'data' => 'roles',
						'title' => __('User Role Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),
			        array(
						'id'=>'select-capabilities',
						'type' => 'select',
						'data' => 'capabilities',
						'multi' => true,
						'title' => __('Capabilities Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						),
					array(
						'id'=>'select-elusive',
						'type' => 'select',
						'data' => 'elusive-icons',
						'title' => __('Elusive Icons Select Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('Here\'s a list of all the elusive icons by name and icon.', 'redux-framework-demo'),
						),			
					)
				);
					
					

			$theme_info = '<div class="redux-framework-section-desc">';
			$theme_info .= '<p class="redux-framework-theme-data description theme-uri">'.__('<strong>Theme URL:</strong> ', 'redux-framework-demo').'<a href="'.$this->theme->get('ThemeURI').'" target="_blank">'.$this->theme->get('ThemeURI').'</a></p>';
			$theme_info .= '<p class="redux-framework-theme-data description theme-author">'.__('<strong>Author:</strong> ', 'redux-framework-demo').$this->theme->get('Author').'</p>';
			$theme_info .= '<p class="redux-framework-theme-data description theme-version">'.__('<strong>Version:</strong> ', 'redux-framework-demo').$this->theme->get('Version').'</p>';
			$theme_info .= '<p class="redux-framework-theme-data description theme-description">'.$this->theme->get('Description').'</p>';
			$tabs = $this->theme->get('Tags');
			if ( !empty( $tabs ) ) {
				$theme_info .= '<p class="redux-framework-theme-data description theme-tags">'.__('<strong>Tags:</strong> ', 'redux-framework-demo').implode(', ', $tabs ).'</p>';	
			}
			$theme_info .= '</div>';

			if(file_exists(dirname(__FILE__).'/README.md')){
			$this->sections['theme_docs'] = array(
						'icon' => ReduxFramework::$_url.'assets/img/glyphicons/glyphicons_071_book.png',
						'title' => __('Documentation', 'redux-framework-demo'),
						'fields' => array(
							array(
								'id'=>'17',
								'type' => 'raw',
								'content' => file_get_contents(dirname(__FILE__).'/README.md')
								),				
						),
						
						);
			}//if




			// You can append a new section at any time.
			$this->sections[] = array(
				'icon' => 'el-icon-eye-open',
				'title' => __('Additional Fields', 'redux-framework-demo'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
				'fields' => array(

					array(
						'id'=>'17',
						'type' => 'date',
						'title' => __('Date Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo')
						),
					array(
						'id'=>'21',
						'type' => 'divide'
						),					
					array(
						'id'=>'18',
						'type' => 'button_set',
						'title' => __('Button Set Option', 'redux-framework-demo'), 
						'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
						'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'default' => '2'
						),
					array(
						'id'=>'23',
						'type' => 'info',
			            'required' => array('18','equals',array('1','2')),	
						'desc' => __('This is the info field, if you want to break sections up.', 'redux-framework-demo')
			        ),
			        array(
			            'id'=>'info_warning',
			            'type'=>'info',
			            'style'=>'warning',
			            'title'=> __( 'This is a title.', 'redux-framework-demo' ),
			            'desc' => __( 'This is an info field with the warning style applied and a header.', 'redux-framework-demo')
			        ),
			        array(
			            'id'=>'info_success',
			            'type'=>'info',
			            'style'=>'success',
			            'icon'=>'el-icon-info-sign',
			            'title'=> __( 'This is a title.', 'redux-framework-demo' ),
			            'desc' => __( 'This is an info field with the success style applied, a header and an icon.', 'redux-framework-demo')
			        ),
					array(
						'id'=>'raw_info',
						'type' => 'info',
						'required' => array('18','equals',array('1','2')),
						'raw_html'=>true,
						'desc' => $sampleHTML,
						),
					array(
						'id'=>"custom_callback",
						'type' => 'callback',
						'title' => __('Custom Field Callback', 'redux-framework-demo'), 
						'subtitle' => __('This is a completely unique field type', 'redux-framework-demo'),
						'desc' => __('This is created with a callback function, so anything goes in this field. Make sure to define the function though.', 'redux-framework-demo'),
						'callback' => 'redux_my_custom_field'
						),
					array(
						'id'=>"group",
						'type' => 'group',//doesn't need to be called for callback fields
						'title' => __('Group - BETA', 'redux-framework-demo'), 
						'subtitle' => __('Group any items together. Experimental. Use at your own risk.', 'redux-framework-demo'),
						'groupname' => __('Group', 'redux-framework-demo'), // Group name
						'subfields' => 
							array(
								array(
									'id'=>'switch-fold',
									'type' => 'switch', 
									'title' => __('testing fold with Group', 'redux-framework-demo'),
									'subtitle'=> __('Look, it\'s on!', 'redux-framework-demo'),
									"default" 		=> 1,
									),	
								array(
			                        'id'=>'text-group',
			                        'type' => 'text',
			                        'title' => __('Text', 'redux-framework-demo'), 
			                        'subtitle' => __('Here you put your subtitle', 'redux-framework-demo'),
			                        'required' => array('switch-fold', '=' , '1'),
									),
								array(
									'id'=>'select-group',
									'type' => 'select',
									'title' => __('Testing select', 'redux-framework-demo'), 
									'subtitle' => __('Select your themes alternative color scheme.', 'redux-framework-demo'),
									'options' => array('default.css'=>'default.css', 'color1.css'=>'color1.css'),
									'default' => 'default.css',
									),
								),
						),			
						
					)

				);   

			$this->sections[] = array(
				'type' => 'divide',
			);

			$this->sections[] = array(
				'icon' => 'el-icon-info-sign',
				'title' => __('Theme Information', 'redux-framework-demo'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
				'fields' => array(
					array(
						'id'=>'raw_new_info',
						'type' => 'raw',
						'content' => $item_info,
						)
					),   
				);


			if(file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
			    $tabs['docs'] = array(
					'icon' => 'el-icon-book',
					    'title' => __('Documentation', 'redux-framework-demo'),
			        'content' => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
			    );
			}

		}	

		public function setHelpTabs() {

			// Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
			$this->args['help_tabs'][] = array(
			    'id' => 'redux-opts-1',
			    'title' => __('Theme Information 1', 'redux-framework-demo'),
			    'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
			);

			$this->args['help_tabs'][] = array(
			    'id' => 'redux-opts-2',
			    'title' => __('Theme Information 2', 'redux-framework-demo'),
			    'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
			);

			// Set the help sidebar
			$this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo');

		}


		/**
			
			All the possible arguments for Redux.
			For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

		 **/
		public function setArguments() {
			
			$theme = wp_get_theme(); // For use with some settings. Not necessary.

			$this->args = array(
	            
	            // TYPICAL -> Change these values as you need/desire
				'opt_name'          	=> 'redux_demo', // This is where your data is stored in the database and also becomes your global variable name.
				'display_name'			=> $theme->get('Name'), // Name that appears at the top of your panel
				'display_version'		=> $theme->get('Version'), // Version that appears at the top of your panel
				'menu_type'          	=> 'menu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
				'allow_sub_menu'     	=> true, // Show the sections below the admin menu item or not
				'menu_title'			=> __( 'Sample Options', 'redux-framework-demo' ),
	            'page'		 	 		=> __( 'Sample Options', 'redux-framework-demo' ),
	            'google_api_key'   	 	=> '', // Must be defined to add google fonts to the typography module
	            'global_variable'    	=> '', // Set a different name for your global variable other than the opt_name
	            'dev_mode'           	=> true, // Show the time the page took to load, etc
	            'customizer'         	=> true, // Enable basic customizer support

	            // OPTIONAL -> Give you extra features
	            'page_priority'      	=> null, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
	            'page_parent'        	=> 'themes.php', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	            'page_permissions'   	=> 'manage_options', // Permissions needed to access the options panel.
	            'menu_icon'          	=> '', // Specify a custom URL to an icon
	            'last_tab'           	=> '', // Force your panel to always open to a specific tab (by id)
	            'page_icon'          	=> 'icon-themes', // Icon displayed in the admin panel next to your menu_title
	            'page_slug'          	=> '_options', // Page slug used to denote the panel
	            'save_defaults'      	=> true, // On load save the defaults to DB before user clicks save or not
	            'default_show'       	=> false, // If true, shows the default value next to each field that is not the default value.
	            'default_mark'       	=> '', // What to print by the field's title if the value shown is default. Suggested: *


	            // CAREFUL -> These options are for advanced use only
	            'transient_time' 	 	=> 60 * MINUTE_IN_SECONDS,
	            'output'            	=> true, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
	            'output_tab'            => true, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
	            //'domain'             	=> 'redux-framework', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
	            //'footer_credit'      	=> '', // Disable the footer credit of Redux. Please leave if you can help it.
	            

	            // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
	            'database'           	=> '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
	            
	        
	            'show_import_export' 	=> true, // REMOVE
	            'system_info'        	=> false, // REMOVE
	            
	            'help_tabs'          	=> array(),
	            'help_sidebar'       	=> '', // __( '', $this->args['domain'] );            
				);


			// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.		
			$this->args['share_icons'][] = array(
			    'url' => 'https://github.com/ReduxFramework/ReduxFramework',
			    'title' => 'Visit us on GitHub', 
			    'icon' => 'el-icon-github'
			    // 'img' => '', // You can use icon OR img. IMG needs to be a full URL.
			);		
			$this->args['share_icons'][] = array(
			    'url' => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
			    'title' => 'Like us on Facebook', 
			    'icon' => 'el-icon-facebook'
			);
			$this->args['share_icons'][] = array(
			    'url' => 'http://twitter.com/reduxframework',
			    'title' => 'Follow us on Twitter', 
			    'icon' => 'el-icon-twitter'
			);
			$this->args['share_icons'][] = array(
			    'url' => 'http://www.linkedin.com/company/redux-framework',
			    'title' => 'Find us on LinkedIn', 
			    'icon' => 'el-icon-linkedin'
			);

			
	 
			// Panel Intro text -> before the form
			if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false ) {
				if (!empty($this->args['global_variable'])) {
					$v = $this->args['global_variable'];
				} else {
					$v = str_replace("-", "_", $this->args['opt_name']);
				}
				$this->args['intro_text'] = sprintf( __('<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'redux-framework-demo' ), $v );
			} else {
				$this->args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo');
			}

			// Add content after the form.
			$this->args['footer_text'] = __('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'redux-framework-demo');

		}
	}
	new Redux_Framework_sample_config();

}


/** 

	Custom function for the callback referenced above

 */
if ( !function_exists( 'redux_my_custom_field' ) ):
	function redux_my_custom_field($field, $value) {
	    print_r($field);
	    print_r($value);
	}
endif;

/**
 
	Custom function for the callback validation referenced above

**/
if ( !function_exists( 'redux_validate_callback_function' ) ):
	function redux_validate_callback_function($field, $value, $existing_value) {
	    $error = false;
	    $value =  'just testing';
	    /*
	    do your validation
	    
	    if(something) {
	        $value = $value;
	    } elseif(something else) {
	        $error = true;
	        $value = $existing_value;
	        $field['msg'] = 'your custom error message';
	    }
	    */
	    
	    $return['value'] = $value;
	    if($error == true) {
	        $return['error'] = $field;
	    }
	    return $return;
	}
endif;
