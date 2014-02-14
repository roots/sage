<?php


if ( !class_exists( 'ShoestrapHeaders' ) ) {

	/**
	* The Headers module
	*/
	class ShoestrapHeaders {

		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 80 );
			add_action( 'widgets_init',       array( $this, 'header_widgets_init'              ), 30  );
			add_action( 'shoestrap_pre_wrap', array( $this, 'branding'                         ), 3   );
			add_action( 'wp_enqueue_scripts', array( $this, 'header_css'                       ), 101 );
			add_action( 'widgets_init',       array( $this, 'jumbotron_widgets_init'           ), 20  );
			add_action( 'shoestrap_pre_wrap', array( $this, 'jumbotron_content'                ), 5   );
			add_action( 'wp_enqueue_scripts', array( $this, 'jumbotron_css'                    ), 101 );
			add_action( 'wp_footer',          array( $this, 'jumbotron_fittext'                ), 10  );
			add_action( 'wp_enqueue_scripts', array( $this, 'jumbotron_fittext_enqueue_script' ), 101 );
			add_filter( 'shoestrap_compiler', array( $this, 'variables_filter'                 )      );
			add_filter( 'shoestrap_compiler', array( $this, 'styles'                           )      );
		}
		/*
		 * The Headers module options.
		 * Include Jumbotron, Extra Header and more.
		 */
		function options( $sections ) {

			//Background Patterns Reader
			$bg_pattern_images_path = get_template_directory() . '/lib/modules/background/patterns';
			$bg_pattern_images_url  = get_template_directory_uri() . '/lib/modules/background/patterns/';
			$bg_pattern_images      = array();

			if ( is_dir( $bg_pattern_images_path ) && $bg_pattern_images_dir = opendir( $bg_pattern_images_path ) ) {
				while ( ( $bg_pattern_images_file = readdir( $bg_pattern_images_dir ) ) !== false ) :
					if ( stristr( $bg_pattern_images_file, '.png' ) !== false || stristr( $bg_pattern_images_file, '.jpg' ) !== false )
						$bg_pattern_images[] = $bg_pattern_images_url . $bg_pattern_images_file;

				endwhile;
			}

			$url = admin_url( 'widgets.php' );
			$fields[] = array( 
				'id'          => 'help9',
				'title'       => __( 'Extra Branding Area', 'shoestrap' ),
				'desc'        => __( "You can enable an extra branding/header area. In this header you can add your logo, and any other widgets you wish.
													To add widgets on your header, visit <a href='$url'>this page</a> and add your widgets to the <strong>Header</strong> Widget Area.", 'shoestrap' ),
				'type'        => 'info',
			);

			$fields[] = array( 
				'title'       => __( 'Display the Header.', 'shoestrap' ),
				'desc'        => __( 'Turn this ON to display the header. Default: OFF', 'shoestrap' ),
				'id'          => 'header_toggle',
				'customizer'  => array(),
				'default'     => 0,
				'type'        => 'switch',
			);

			$fields[] = array( 
				'title'       => __( 'Display branding on your Header.', 'shoestrap' ),
				'desc'        => __( 'Turn this ON to display branding ( Sitename or Logo )on your Header. Default: ON', 'shoestrap' ),
				'id'          => 'header_branding',
				'customizer'  => array(),
				'default'     => 1,
				'type'        => 'switch',
				'required'    => array('header_toggle','=',array('1')),
			);

			$fields[] = array( 
				'title'       => __( 'Header Background Color', 'shoestrap' ),
				'desc'        => __( 'Select the background color for your header. Default: #EEEEEE.', 'shoestrap' ),
				'id'          => 'header_bg',
				'default'     => '#EEEEEE',
				'customizer'  => array(),
				'transparent' => false,    
				'type'        => 'color',
				'required'    => array('header_toggle','=',array('1')),
			);

			$fields[] = array( 
				'title'       => __( 'Header Background Opacity', 'shoestrap' ),
				'desc'        => __( 'Select the background opacity for your header. Default: 100%.', 'shoestrap' ),
				'id'          => 'header_bg_opacity',
				'default'     => 100,
				'min'         => 0,
				'step'        => 1,
				'max'         => 100,
				'compiler'    => true,
				'type'        => 'slider',
				'required'    => array('header_toggle','=',array('1')),
			);

			$fields[] = array( 
				'title'       => __( 'Header Text Color', 'shoestrap' ),
				'desc'        => __( 'Select the text color for your header. Default: #333333.', 'shoestrap' ),
				'id'          => 'header_color',
				'default'     => '#333333',
				'customizer'  => array(),
				'transparent' => false,    
				'type'        => 'color',
				'required'    => array('header_toggle','=',array('1')),
			);

			$fields[] = array( 
				'title'       => __( 'Header Top Margin', 'shoestrap' ),
				'desc'        => __( 'Select the top margin of header in pixels. Default: 0px.', 'shoestrap' ),
				'id'          => 'header_margin_top',
				'default'     => 0,
				'min'         => 0,
				'max'         => 200,
				'type'        => 'slider',
				'required'    => array('header_toggle','=',array('1')),
			);

			$fields[] = array( 
				'title'       => __( 'Header Bottom Margin', 'shoestrap' ),
				'desc'        => __( 'Select the bottom margin of header in pixels. Default: 0px.', 'shoestrap' ),
				'id'          => 'header_margin_bottom',
				'default'     => 0,
				'min'         => 0,
				'max'         => 200,
				'type'        => 'slider',
				'required'    => array('header_toggle','=',array('1')),
			);

			// Jumbotron Options
			$section = array(
				'title' => __('Headers', 'shoestrap'),
				'icon'  => 'el-icon-bullhorn icon-large'
			);

			$url = admin_url( 'widgets.php' );
			$fields[] = array(
				'id'        => 'help8',
				'title'     => __( 'Jumbotron', 'shoestrap'),
				'desc'      => __( "A 'Jumbotron', also known as 'Hero' area,
												is an area in your site where you can display in a prominent position things that matter to you.
												This can be a slideshow, some text or whatever else you wish.
												This area is implemented as a widget area, so in order for something to be displayed
												you will have to add a widget from <a href='$url'>here</a>.", 'shoestrap' ),
				'type'      => 'info'
			);

			$fields[] = array(
				'title'     => __('Jumbotron Background Color', 'shoestrap'),
				'desc'      => __('Select the background color for your Jumbotron area. Please note that this area will only be visible if you assign a widget to the \'Jumbotron\' Widget Area. Default: #EEEEEE.', 'shoestrap'),
				'id'        => 'jumbotron_bg',
				'default'   => '#EEEEEE',
				'compiler'  => true,
				'transparent'=> false,    
				'type'      => 'color'
			);


			$fields[] = array(
				'title'     => __('Background position', 'shoestrap'),
				'desc'      => __('Changes how the background image or pattern is displayed from scroll to fixed position. Default: Fixed.', 'shoestrap'),
				'id'        => 'jumbotron_background_fixed_toggle',
				'default'   => 1,
				'on'        => __('Fixed', 'shoestrap'),
				'off'       => __('Scroll', 'shoestrap'),
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __('Use a Background Image', 'shoestrap'),
				'desc'      => __('Enable this option to upload a custom background image for your site. This will override any patterns you may have selected. Default: OFF.', 'shoestrap'),
				'id'        => 'jumbotron_background_image_toggle',
				'default'   => 0,
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __('Upload a Custom Background Image', 'shoestrap'),
				'desc'      => __('Upload a Custom Background image using the media uploader, or define the URL directly.', 'shoestrap'),
				'id'        => 'jumbotron_background_image',
				'required'  => array('jumbotron_background_image_toggle','=',array('1')),
				'default'   => '',
				'type'      => 'media',
				'customizer'=> array(),
			);

			$fields[] = array(
				'title'     => __('Background Image Positioning', 'shoestrap'),
				'desc'      => __('Allows the user to modify how the background displays. By default it is full width and stretched to fill the page. Default: Full Width.', 'shoestrap'),
				'id'        => 'jumbotron_background_image_position_toggle',
				'default'   => 0,
				'required'  => array('jumbotron_background_image_toggle','=',array('1')),
				'on'        => __('Custom', 'shoestrap'),
				'off'       => __('Full Width', 'shoestrap'),
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __('Background Repeat', 'shoestrap'),
				'desc'      => __('Select how (or if) the selected background should be tiled. Default: Tile', 'shoestrap'),
				'id'        => 'jumbotron_background_repeat',
				'required'  => array('jumbotron_background_image_position_toggle','=',array('1')),
				'default'   => 'repeat',
				'type'      => 'select',
				'options'   => array(
					'no-repeat'  => __( 'No Repeat', 'shoestrap' ),
					'repeat'     => __( 'Tile', 'shoestrap' ),
					'repeat-x'   => __( 'Tile Horizontally', 'shoestrap' ),
					'repeat-y'   => __( 'Tile Vertically', 'shoestrap' ),
				),
			);

			$fields[] = array(
				'title'     => __('Background Alignment', 'shoestrap'),
				'desc'      => __('Select how the selected background should be horizontally aligned. Default: Left', 'shoestrap'),
				'id'        => 'jumbotron_background_position_x',
				'required'  => array('jumbotron_background_image_position_toggle','=',array('1')),
				'default'   => 'repeat',
				'type'      => 'select',
				'options'   => array(
					'left'    => __( 'Left', 'shoestrap' ),
					'right'   => __( 'Right', 'shoestrap' ),
					'center'  => __( 'Center', 'shoestrap' ),
				),
			);

			$fields[] = array(
				'title'     => __('Use a Background Pattern', 'shoestrap'),
				'desc'      => __('Select one of the already existing Background Patterns. Default: OFF.', 'shoestrap'),
				'id'        => 'jumbotron_background_pattern_toggle',
				'default'   => 0,
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __('Choose a Background Pattern', 'shoestrap'),
				'desc'      => __('Select a background pattern.', 'shoestrap'),
				'id'        => 'jumbotron_background_pattern',
				'required'  => array('jumbotron_background_pattern_toggle','=',array('1')),
				'default'   => '',
				'tiles'     => true,
				'type'      => 'image_select',
				'options'   => $bg_pattern_images,
			);

			$fields[] = array(
				'title'     => __('Display Jumbotron only on the Frontpage.', 'shoestrap'),
				'desc'      => __('When Turned OFF, the Jumbotron area is displayed in all your pages. If you wish to completely disable the Jumbotron, then please remove the widgets assigned to its area and it will no longer be displayed. Default: ON', 'shoestrap'),
				'id'        => 'jumbotron_visibility',
				'customizer'=> array(),
				'default'   => 1,
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __('Full-Width', 'shoestrap'),
				'desc'      => __('When Turned ON, the Jumbotron is no longer restricted by the width of your page, taking over the full width of your screen. This option is useful when you have assigned a slider widget on the Jumbotron area and you want its width to be the maximum width of the screen. Default: OFF.', 'shoestrap'),
				'id'        => 'jumbotron_nocontainer',
				'customizer'=> array(),
				'default'   => 1,
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __('Use fittext script for the title.', 'shoestrap'),
				'desc'      => __('Use the fittext script to enlarge or scale-down the font-size of the widget title to fit the Jumbotron area. Default: OFF', 'shoestrap'),
				'id'        => 'jumbotron_title_fit',
				'customizer'=> array(),
				'default'   => 0,
				'type'      => 'switch',
			);

			$fields[] = array(
				'title'     => __('Center-align the content.', 'shoestrap'),
				'desc'      => __('Turn this on to center-align the contents of the Jumbotron area. Default: OFF', 'shoestrap'),
				'id'        => 'jumbotron_center',
				'customizer'=> array(),
				'default'   => 0,
				'type'      => 'switch',
			);

			$fields[] = array(
				'title'     => __('Jumbotron Font', 'shoestrap'),
				'desc'      => __('The font used in jumbotron.', 'shoestrap'),
				'id'        => 'font_jumbotron',
				'compiler'  => true,
				'default'   => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'font-size'   => 20,
					'color'       => '#333333',
					'google'      => 'false',
					'units'       => 'px'
				),
				'preview'   => array(
					'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
					'size'    => '30px' //this is the text size from preview box
				),
				'type'      => 'typography',
			);

			$fields[] = array(
				'title'     => __('Jumbotron Header Overrides', 'shoestrap'),
				'desc'      => __('By enabling this you can specify custom values for each <h*> tag. Default: Off', 'shoestrap'),
				'id'        => 'font_jumbotron_heading_custom',
				'default'   => 0,
				'compiler'  => true,
				'type'      => 'switch',
				'customizer'=> array(),
			);

			$fields[] = array(
				'title'     => __('Jumbotron Headers Font', 'shoestrap'),
				'desc'      => __('The main font for your site.', 'shoestrap'),
				'id'        => 'font_jumbotron_headers',
				'compiler'  => true,
				'default'   => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'color'       => '#333333',
					'google'      => 'false'
				),
				'preview'   => array(
					'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
					'size'    => '30px' //this is the text size from preview box
				),
				'type'      => 'typography',
				'required'  => array('font_jumbotron_heading_custom','=',array('1')),
			);

			$fields[] = array(
				'title'     => 'Jumbotron Border',
				'desc'      => __('Select the border options for your Jumbotron', 'shoestrap'),
				'id'        => 'jumbotron_border',
				'type'      => 'border',
				'all'       => false, 
				'left'      => false, 
				'top'       => false, 
				'right'     => false,
				'default'   => array(
					'border-top'      => '0',
					'border-bottom'   => '0',
					'border-style'    => 'solid',
					'border-color'    => '#428bca',
				),
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'shoestrap_module_headers_options_modifier', $section );
			
			$sections[] = $section;
			return $sections;

		}

		/**
		 * Register sidebars and widgets
		 */
		function header_widgets_init() {
			register_sidebar( array(
				'name'          => __( 'Header Area', 'shoestrap' ),
				'id'            => 'header-area',
				'before_widget' => '<div class="container">',
				'after_widget'  => '</div>',
				'before_title'  => '<h1>',
				'after_title'   => '</h1>',
			));
		}

		/*
		 * The Header template
		 */
		function branding() {
			if ( shoestrap_getVariable( 'header_toggle' ) == 1 ) { ?>
				<div class="before-main-wrapper">

					<?php if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) : ?>
						<div class="container">
					<?php endif; ?>

						<div class="header-wrapper">

							<?php if ( shoestrap_getVariable( 'site_style' ) == 'wide' ) : ?>
								<div class="container">
							<?php endif; ?>

								<?php if ( shoestrap_getVariable( 'header_branding' ) == 1 ) : ?>
									<a class="brand-logo" href="<?php echo home_url(); ?>/">
										<h1><?php if ( class_exists( 'ShoestrapBranding' ) ) echo ShoestrapBranding::logo(); ?></h1>
									</a>
								<?php endif; ?>

								<?php $pullclass = ( shoestrap_getVariable( 'header_branding' ) == 1 ) ? ' class="pull-right"' : ''; ?>

								<div<?php echo $pullclass; ?>>
									<?php dynamic_sidebar( 'header-area' ); ?>
								</div >

							<?php if ( shoestrap_getVariable( 'site_style' ) == 'wide' ) : ?>
								</div>
							<?php endif; ?>
						</div>

					<?php if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) : ?>
						</div>
					<?php endif; ?>
				</div>
				<?php
			}
		}

		/*
		 * Any necessary extra CSS is generated here
		 */
		function header_css() {
			$bg = shoestrap_getVariable( 'header_bg' );
			$cl = shoestrap_getVariable( 'header_color' );
			
			$header_margin_top    = shoestrap_getVariable( 'header_margin_top' );
			$header_margin_bottom = shoestrap_getVariable( 'header_margin_bottom' );
			
			$opacity  = (intval(shoestrap_getVariable( 'header_bg_opacity' )))/100;
			$rgb      = ShoestrapColor::get_rgb( $bg, true );

			if ( shoestrap_getVariable( 'header_toggle' ) == 1 ) {
				$style = '.header-wrapper{ color: '.$cl.';';

				$style .= ( $opacity != 1 && $opacity != '' ) ? 'background: rgb('.$rgb.'); background: rgba('.$rgb.', '.$opacity.');' : 'background: '.$bg.';';
				$style .= 'margin-top:'.$header_margin_top.'px; margin-bottom:'.$header_margin_bottom.'px; }';

				wp_add_inline_style( 'shoestrap_css', $style );
			}
		}


		/**
		 * Register sidebars and widgets
		 */
		function jumbotron_widgets_init() {
			register_sidebar( array(
				'name'          => __( 'Jumbotron', 'shoestrap' ),
				'id'            => 'jumbotron',
				'before_widget' => '<section id="%1$s"><div class="section-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h1>',
				'after_title'   => '</h1>',
			));
		}



		/*
		 * The content of the Jumbotron region
		 * according to what we've entered in the customizer
		 */
		function jumbotron_content() {
			$hero         = false;
			$site_style   = shoestrap_getVariable( 'site_style' );
			$visibility   = shoestrap_getVariable( 'jumbotron_visibility' );
			$nocontainer  = shoestrap_getVariable( 'jumbotron_nocontainer' );

			if ( ( ( $visibility == 1 && is_front_page() ) || $visibility !== 1 ) && is_active_sidebar( 'jumbotron' ) )
				$hero = true;
			?>

			<div class="clearfix"></div>
			<div class="before-main-wrapper">

				<?php if ( $hero == true ) : ?>
					<?php if ( $site_style == 'boxed' && $nocontainer != 1 ) : ?>
						<div class="<?php echo shoestrap_container_class(); ?>">
					<?php endif; ?>

					<div class="jumbotron">

						<?php if ( $nocontainer != 1 && $site_style == 'wide' || $site_style == 'boxed' ) : ?>
							<div class="<?php echo shoestrap_container_class(); ?>">
						<?php endif; ?>

							<?php dynamic_sidebar('Jumbotron'); ?>

						<?php if ( $nocontainer != 1 && $site_style == 'wide' || $site_style == 'boxed' ) : ?>
							</div>
						<?php endif; ?>

					<?php if ( $site_style == 'boxed' && $nocontainer != 1 ) : ?>
						</div>
					<?php endif; ?>

					</div>
				</div>
			<?php endif;
		}




		/**
		 * Any Jumbotron-specific CSS that can't be added in the .less stylesheet is calculated here.
		 */
		function jumbotron_css() {
			$center = shoestrap_getVariable( 'jumbotron_center' );
			$border = shoestrap_getVariable( 'jumbotron_border' );

			$repeat   = '';
			$position = '';
			// $background is the saved custom image, or the default image.
			if ( shoestrap_getVariable( 'jumbotron_background_image_toggle' ) == 1 ) {

				if ( shoestrap_getVariable( 'jumbotron_background_custom_image' ) != '' ) {
					$jVar = shoestrap_getVariable( 'jumbotron_background_custom_image' );
					$background = set_url_scheme( $jVar['url'] );
				} elseif ( shoestrap_getVariable( 'jumbotron_background_image' ) != '' ) {
					$jVar = shoestrap_getVariable( 'jumbotron_background_image' );
					$background = set_url_scheme( $jVar['url'] );
				}
				
			} elseif ( shoestrap_getVariable( 'jumbotron_background_pattern_toggle' ) == 1 && shoestrap_getVariable( 'jumbotron_background_pattern' ) != '' ) {
				$background = shoestrap_getVariable( 'jumbotron_background_pattern' );
			}

			$color = '';
			if ( shoestrap_getVariable( 'jumbotron_background_color' ) != '' )
				$color = '#' . str_replace( '#', '', shoestrap_getVariable( 'jumbotron_background_color' ) );

			if ( !isset( $background ) && !isset( $color ) )
				return;

			$style = $color ? "background-color: $color;" : '';

			if ( shoestrap_getVariable( 'jumbotron_background_fixed_toggle' ) == 1 )
				$style .= 'background-attachment: fixed;';

			$image = ( isset($background) && $background ) ? "background-image: url( '$background' );" : '';

			if ( shoestrap_getVariable( 'jumbotron_background_image_toggle' ) == 1 && ( shoestrap_getVariable( 'jumbotron_background_custom_image' ) != '' || shoestrap_getVariable( 'jumbotron_background_image' ) != '' ) ) {
				if ( shoestrap_getVariable( 'jumbotron_background_image_position_toggle' ) == 0 ) {
					$style .= "background-size: cover;";
					$style .= "-webkit-background-size: cover;";
					$style .= "-moz-background-size: cover;";
					$style .= "-o-background-size: cover;";
					$style .= "background-position: 50% 50%;";

					if ( shoestrap_getVariable( 'jumbotron_background_fixed_toggle' ) == 0 )
						$style .= "background-repeat: no-repeat;";

				} else { // Not fixed position, custom
					$repeat = shoestrap_getVariable( 'jumbotron_background_repeat' );

					if ( !in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
						$repeat = 'repeat';

					if ( $repeat == 'no-repeat' )
						$style .= "background-size: auto;";

					$repeat = " background-repeat: $repeat;";
					$position = shoestrap_getVariable( 'jumbotron_background_position_x', 'left' );
					
					if ( !in_array( $position, array( 'center', 'right', 'left' ) ) )
						$position = 'left';

					$position = " background-position: top $position;";
				}
			}

			$style .= $image . $repeat . $position;

			if ( $center == 1 )
				$style .= 'text-align: center;';

			if ( !empty($border) && $border['border-bottom'] > 0 && !empty($border['border-color']) )
				$style .= 'border-bottom:' . $border['border-bottom'] . ' ' . $border['border-style'] . ' ' . $border['border-color'] . ';';

			$style .= 'margin-bottom: 0px;';

			$theCSS = '.jumbotron {' . trim( $style ) . '}';
			$theCSS .= $color ? ".jumbotron{background: $color;}" : '';
			
			wp_add_inline_style( 'shoestrap_css', $theCSS );
		}

		/*
		 * Enables the fittext.js for h1 headings
		 */
		function jumbotron_fittext() {
			$fittext_toggle   = shoestrap_getVariable( 'jumbotron_title_fit' );
			$jumbo_visibility = shoestrap_getVariable( 'jumbotron_visibility' );

			// Should only show on the front page if it's enabled, or site-wide when appropriate
			if ( $fittext_toggle == 1 && ( $jumbo_visibility == 0 && ( $jumbo_visibility == 1 && is_front_page() ) ) )
				echo '<script>jQuery(".jumbotron h1").fitText(1.3);</script>';
		}

		/*
		 * Enqueues fittext.js when needed
		 */
		function jumbotron_fittext_enqueue_script() {
			$fittext_toggle   = shoestrap_getVariable( 'jumbotron_title_fit' );
			$jumbo_visibility = shoestrap_getVariable( 'jumbotron_visibility' );

			if ( $fittext_toggle == 1 && ( $jumbo_visibility == 0 && ( $jumbo_visibility == 1 && is_front_page() ) ) ) {
				wp_register_script('fittext', get_template_directory_uri() . '/assets/js/vendor/jquery.fittext.js', false, null, false);
				wp_enqueue_script('fittext');
			}
		}

		/**
		 * Variables to use for the compiler.
		 * These override the default Bootstrap Variables.
		 */
		function variables() {
			$font_jumbotron         = shoestrap_process_font( shoestrap_getVariable( 'font_jumbotron', true ) );
			$jumbotron_bg     			= '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( shoestrap_getVariable( 'jumbotron_bg', true ) ) );
			$jumbotron_text_color   = '#' . str_replace( '#', '', $font_jumbotron['color'] );

			if ( shoestrap_getVariable( 'font_jumbotron_heading_custom', true ) == 1 ) {
				$font_jumbotron_headers = shoestrap_process_font( shoestrap_getVariable( 'font_jumbotron_headers', true ) );

				$font_jumbotron_headers_face   = $font_jumbotron_headers['font-family'];
				$font_jumbotron_headers_weight = $font_jumbotron_headers['font-weight'];
				$font_jumbotron_headers_style  = $font_jumbotron_headers['font-style'];
				$jumbotron_headers_text_color  = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( $font_jumbotron_headers['color'] ) );

			} else {
				$font_jumbotron_headers_face   = $font_jumbotron['font-family'];
				$font_jumbotron_headers_weight = $font_jumbotron['font-weight'];
				$font_jumbotron_headers_style  = $font_jumbotron['font-style'];
				$jumbotron_headers_text_color  = $jumbotron_text_color;
			}

			$variables = '';

			$variables .= '@jumbotron-color:         ' . $jumbotron_text_color . ';';
			$variables .= '@jumbotron-bg:            ' . $jumbotron_bg . ';';
			$variables .= '@jumbotron-heading-color: ' . $jumbotron_headers_text_color . ';';
			$variables .= '@jumbotron-font-size:     ' . $font_jumbotron['font-size'] . 'px;';

			// Shoestrap-specific variables
			// --------------------------------------------------

			$variables .= '@jumbotron-font-weight:       ' . $font_jumbotron['font-weight'] . ';';
			$variables .= '@jumbotron-font-style:        ' . $font_jumbotron['font-style'] . ';';
			$variables .= '@jumbotron-font-family:       ' . $font_jumbotron['font-family'] . ';';

			$variables .= '@jumbotron-headers-font-weight:       ' . $font_jumbotron_headers_weight . ';';
			$variables .= '@jumbotron-headers-font-style:        ' . $font_jumbotron_headers_style . ';';
			$variables .= '@jumbotron-headers-font-family:       ' . $font_jumbotron_headers_face . ';';

			return $variables;
		}

		/**
		 * Add the variables to the compiler
		 */
		function variables_filter( $variables ) {
			return $variables . self::variables();
		}

		function styles( $bootstrap ) {
			return $bootstrap . '
			@import "' . SHOESTRAP_MODULES_PATH . '/headers/assets/less/styles.less";';
		}
	}
}

$headers = new ShoestrapHeaders();