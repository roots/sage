<?php


if ( !class_exists( 'SS_Foundation_Header' ) ) {

	/**
	* The Header module
	*/
	class SS_Foundation_Header {

		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 80 );
			add_action( 'widgets_init',       array( $this, 'header_widgets_init'              ), 30  );
			add_action( 'shoestrap_pre_wrap', array( $this, 'branding'                         ), 3   );
		}
		/*
		 * The Header module options.
		 */
		function options( $sections ) {
			// Header Options
			$section = array(
				'title' => __( 'Header', 'shoestrap'),
				'icon'  => 'el-icon-eye-open'
			);

			$fields[] = array( 
				'id'          => 'help9',
				'title'       => __( 'Extra Branding Area', 'shoestrap' ),
				'desc'        => __( 'You can enable an extra branding/header area. In this header you can add your logo, and any other widgets you wish.', 'shoestrap' ),
				'type'        => 'info',
			);

			$fields[] = array( 
				'title'       => __( 'Display the Header.', 'shoestrap' ),
				'desc'        => __( 'Turn this ON to display the header. Default: OFF', 'shoestrap' ),
				'id'          => 'header_toggle',
				'default'     => 0,
				'type'        => 'switch',
				'compiler'    => false,
			);

			$fields[] = array( 
				'title'       => __( 'Display branding on your Header.', 'shoestrap' ),
				'desc'        => __( 'Turn this ON to display branding ( Sitename or Logo )on your Header.', 'shoestrap' ),
				'id'          => 'header_branding',
				'default'     => 1,
				'type'        => 'switch',
				'required'    => array( 'header_toggle', '=', array( '1' ) ),
				'compiler'    => false,
			);

			$fields[] = array(
				'title'       => __( 'Branding width', 'shoestrap' ),
				'desc'        => '',
				'id'          => 'header-branding-width',
				'default'     => 6,
				'min'         => 0,
				'step'        => 1,
				'max'         => 12,
				'type'        => 'slider',
				'required'    => array( 'header_toggle', '=', array( '1' ) ),
				'compiler'    => false,
			);

			$fields[] = array( 
				'title'       => __( 'Header Background', 'shoestrap' ),
				'desc'        => __( 'Specify the background for your header.', 'shoestrap' ),
				'id'          => 'header-bg',
				'default'     => array( 'background-color' => '#ffffff' ),
				'output'      => '.header-wrapper',
				'type'        => 'background',
				'required'    => array( 'header_toggle','=',array( '1' ) ),
				'compiler'    => false,
			);

			$fields[] = array( 
				'title'       => __( 'Header Text Color', 'shoestrap' ),
				'desc'        => __( 'Select the text color for your header. Default: #333333.', 'shoestrap' ),
				'id'          => 'header_color',
				'default'     => '#333333',
				'transparent' => false,    
				'type'        => 'color',
				'required'    => array( 'header_toggle', '=', array( '1' ) ),
				'compiler'    => false,
			);

			$fields[] = array( 
				'title'       => __( 'Header Top Margin', 'shoestrap' ),
				'desc'        => __( 'Select the top margin of header in pixels. Default: 0px.', 'shoestrap' ),
				'id'          => 'header_margin_top',
				'default'     => 0,
				'min'         => 0,
				'max'         => 200,
				'type'        => 'slider',
				'required'    => array( 'header_toggle', '=', array( '1' ) ),
				'compiler'    => false,
			);

			$fields[] = array( 
				'title'       => __( 'Header Bottom Margin', 'shoestrap' ),
				'desc'        => __( 'Select the bottom margin of header in pixels. Default: 0px.', 'shoestrap' ),
				'id'          => 'header_margin_bottom',
				'default'     => 0,
				'min'         => 0,
				'max'         => 200,
				'type'        => 'slider',
				'required'    => array( 'header_toggle', '=', array( '1' ) ),
				'compiler'    => false,
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'shoestrap_module_header_options_modifier', $section );
			
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
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '<h1>',
				'after_title'   => '</h1>',
			));
		}

		/*
		 * The Header template
		 */
		function branding() {
			global $ss_settings;

			if ( $ss_settings['header_toggle'] == 1 ) { ?>
				<div class="header-wrapper">
					<div class="row">
						<div class="medium-<?php echo $ss_settings['header-branding-width']; ?> columns">
							<?php if ( $ss_settings['header_branding'] == 1 ) : ?>
								<a class="brand-logo left" href="<?php echo home_url(); ?>/">
									<h1 class="brand"><?php echo Shoestrap_Framework::logo(); ?></h1>
								</a>
							<?php endif; ?>
						</div>

						<div class="medium-<?php echo 12 - $ss_settings['header-branding-width']; ?> columns">
							<div<?php echo $pullclass; ?>>
								<?php dynamic_sidebar( 'header-area' ); ?>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}
}

$headers = new SS_Foundation_Header();