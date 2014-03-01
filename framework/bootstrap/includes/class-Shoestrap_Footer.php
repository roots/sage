<?php


if( ! class_exists( 'Shoestrap_Footer' ) ) {
	/**
	* Build the Shoestrap Footer module class.
	*/
	class Shoestrap_Footer {

		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 85 ); 
			add_action( 'wp_enqueue_scripts',    array( $this, 'css' ), 101 );
			add_action( 'shoestrap_footer_html', array( $this, 'html' ) );
			add_action( 'widgets_init',          array( $this, 'widgets_init' ) );
		}

		/*
		 * The footer core options for the Shoestrap theme
		 */
		function options( $sections ) {

			// Branding Options
			$section = array(
				'title' => __( 'Footer', 'shoestrap' ),
				'icon' => 'el-icon-caret-down'
			);

			$fields[] = array( 
				'title'       => __( 'Footer Background Color', 'shoestrap' ),
				'desc'        => __( 'Select the background color for your footer. Default: #282a2b.', 'shoestrap' ),
				'id'          => 'footer_background',
				'default'     => '#282a2b',
				'transparent' => false,    
				'type'        => 'color'
			);
			
			$fields[] = array( 
				'title'       => __( 'Footer Background Opacity', 'shoestrap' ),
				'desc'        => __( 'Select the opacity level for the footer bar. Default: 100%.', 'shoestrap' ),
				'id'          => 'footer_opacity',
				'default'     => 100,
				'min'         => 0,
				'max'         => 100,
				'type'        => 'slider',
				'required'    => array('retina_toggle','=',array('1')),
			);

			$fields[] = array( 
				'title'       => __( 'Footer Text Color', 'shoestrap' ),
				'desc'        => __( 'Select the text color for your footer. Default: #8C8989.', 'shoestrap' ),
				'id'          => 'footer_color',
				'default'     => '#8C8989',
				'transparent' => false,    
				'type'        => 'color'
			);

			$fields[] = array( 
				'title'       => __( 'Footer Text', 'shoestrap' ),
				'desc'        => __( 'The text that will be displayed in your footer. You can use [year] and [sitename] and they will be replaced appropriately. Default: &copy; [year] [sitename]', 'shoestrap' ),
				'id'          => 'footer_text',
				'default'     => '&copy; [year] [sitename]',
				'type'        => 'textarea'
			);

			$fields[] = array( 
				'title'       => 'Footer Border',
				'desc'        => 'Select the border options for your Footer',
				'id'          => 'footer_border',
				'type'        => 'border',
				'all'         => false, 
				'left'        => false, 
				'bottom'      => false, 
				'right'       => false,
				'default'     => array(
					'border-top'      => '0',
					'border-bottom'   => '0',
					'border-style'    => 'solid',
					'border-color'    => '#4B4C4D',
				),
			);

			$fields[] = array( 
				'title'       => __( 'Footer Top Margin', 'shoestrap' ),
				'desc'        => __( 'Select the top margin of footer in pixels. Default: 0px.', 'shoestrap' ),
				'id'          => 'footer_top_margin',
				'default'     => 0,
				'min'         => 0,
				'max'         => 200,
				'type'        => 'slider',
			);

			$fields[] = array( 
				'title'       => __( 'Show social icons in footer', 'shoestrap' ),
				'desc'        => __( 'Show social icons in the footer. Default: On.', 'shoestrap' ),
				'id'          => 'footer_social_toggle',
				'default'     => 0,
				'type'        => 'switch',
			);

			$fields[] = array( 
				'title'       => __( 'Footer social links column width', 'shoestrap' ),
				'desc'        => __( 'You can customize the width of the footer social links area. The footer text width will be adjusted accordingly. Default: 5.', 'shoestrap' ),
				'id'          => 'footer_social_width',
				'required'    => array( 'footer_social_toggle','=',array('1') ),
				'default'     => 6,
				'min'         => 3,
				'step'        => 1,
				'max'         => 10,
				'type'        => 'slider',
			);    

			$fields[] = array( 
				'title'       => __( 'Footer social icons open new window', 'shoestrap' ),
				'desc'        => __( 'Social icons in footer will open a new window. Default: On.', 'shoestrap' ),
				'id'          => 'footer_social_new_window_toggle',
				'required'    => array( 'footer_social_toggle','=',array('1') ),
				'default'     => 1,
				'type'        => 'switch',
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'shoestrap_module_footer_options_modifier', $section );
			
			$sections[] = $section;
			return $sections;
		}

		/**
		 * Register sidebars and widgets
		 */
		function widgets_init() {
			$class        = apply_filters( 'shoestrap_widgets_class', '' );
			$before_title = apply_filters( 'shoestrap_widgets_before_title', '<h3 class="widget-title">' );
			$after_title  = apply_filters( 'shoestrap_widgets_after_title', '</h3>' );

			// Sidebars
			register_sidebar( array(
				'name'          => __( 'Primary Sidebar', 'shoestrap' ),
				'id'            => 'sidebar-primary',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Secondary Sidebar', 'shoestrap' ),
				'id'            => 'sidebar-secondary',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Footer Widget Area 1', 'shoestrap' ),
				'id'            => 'sidebar-footer-1',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Footer Widget Area 2', 'shoestrap' ),
				'id'            => 'sidebar-footer-2',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Footer Widget Area 3', 'shoestrap' ),
				'id'            => 'sidebar-footer-3',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Footer Widget Area 4', 'shoestrap' ),
				'id'            => 'sidebar-footer-4',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));
		}

		/**
		 * If the options selected require the insertion of some custom CSS to the document head, generate that CSS here
		 */

		function css() {
			global $ss_settings;
			$bg         = $ss_settings['footer_background'];
			$cl         = $ss_settings['footer_color'];
			$cl_brand   = $ss_settings['color_brand_primary'];
			$opacity    = ( intval( $ss_settings['footer_opacity'] ) ) / 100;
			$rgb        = Shoestrap_Color::get_rgb( $bg, true );
			$border     = $ss_settings['footer_border'];
			$top_margin = $ss_settings['footer_top_margin'];

			$container_margin = $top_margin * 0.381966011;

			$style = 'footer.content-info {';
				$style .= 'color:' . $cl . ';';

				$style .= ( $opacity != 1 && $opacity != "" ) ? 'background: rgba(' . $rgb . ',' . $opacity . ');' : 'background:' . $bg . ';';
				$style .= ( ! empty($border) && $border['border-top'] > 0 && ! empty($border['border-color']) ) ? 'border-top:' . $border['border-top'] . ' ' . $border['border-style'] . ' ' . $border['border-color'] . ';' : '';
				$style .= 'padding: 18px 10px 18px;';
				$style .= ( ! empty($top_margin) ) ? 'margin-top:'. $top_margin .'px;' : '';
			$style .= '}';

			$style .= 'footer div.container { margin-top:'. $container_margin .'px; }';
			$style .= '#copyright-bar { line-height: 30px; }';
			$style .= '#footer_social_bar { line-height: 30px; font-size: 16px; text-align: right; }';
			$style .= '#footer_social_bar a { margin-left: 9px; padding: 3px; color:' . $cl . '; }';
			$style .= '#footer_social_bar a:hover, #footer_social_bar a:active { color:' . $cl_brand . ' !important; text-decoration:none; }';

			wp_add_inline_style( 'shoestrap_css', $style );
		}

		function html() {
			global $ss_framework, $ss_social, $ss_settings;

			$blog_name  = get_bloginfo( 'name', 'display' );

			if ( isset( $ss_settings['footer_text'] ) ) {
				$ftext = $ss_settings['footer_text'];
			} else {
				$ftext = '&copy; [year] [sitename]';
			}

			$ftext = str_replace( '[year]', date( 'Y' ), $ftext );
			$ftext = str_replace( '[sitename]', $blog_name, $ftext );

			if ( isset( $ss_settings['footer_social_toggle'] ) ) {
				$social = $ss_settings['footer_social_toggle'];
			}

			if ( isset( $ss_settings['footer_social_width'] ) ) {
				$social_width = $ss_settings['footer_social_width'];
			}

			$width = 12;

			// Social is enabled, we're modifying the width!
			if ( isset( $social_width ) && isset( $social ) && intval( $social_width ) > 0 ) {
				$width = 12 - intval( $social_width );
			} else {
				$width = 12;
			}

			if ( isset( $ss_settings['footer_social_new_window_toggle'] ) && ! empty( $ss_settings['footer_social_new_window_toggle'] ) ) {
				$blank = ' target="_blank"';
			} else {
				$blank = null;
			}

			$networks = $ss_social->get_social_links();

			do_action( 'shoestrap_footer_before_copyright' );

			echo '<div id="footer-copyright">';

			echo $ss_framework->make_col( $element = 'div', array( 'large' => $width ), 'copyright-bar' );
			echo $ftext;
			echo '</div>';

			if ( isset( $social ) && ! isset( $networks ) && is_null( $networks ) && count( $networks ) > 0 ) {
				echo $ss_framework->make_col( 'open', 'div', array( 'large' => $social_width ), 'footer_social_bar' );

				foreach ( $networks as $network ) {
					if ( $network['url'] == '' ) {
						continue;
					}

					echo '<a href="' . $network['url'] . '"' . $blank . ' title="' . $network['icon'] . '">';
					echo '<span class="el-icon-' . $network['icon'] . '"></span>';
					echo '</a>';
				}

				echo '</div>';
			}

			echo $ss_framework->clearfix();

			echo '</div>';
		}
	}
}