<?php


if( !class_exists( 'Shoestrap_Footer' ) ) {
	/**
	* Build the Shoestrap Footer module class.
	*/
	class Shoestrap_Footer {

		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 85 ); 
			add_action( 'wp_enqueue_scripts',    array( $this, 'css'  ), 101 );
			add_action( 'shoestrap_footer_html', array( $this, 'html' )      );
		}

		/*
		 * The footer core options for the Shoestrap theme
		 */
		function options( $sections ) {

			// Branding Options
			$section = array(
				'title' => __( 'Footer', 'shoestrap' ),
				'icon' => 'el-icon-caret-down icon-large'
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
				'required'    => array('footer_social_toggle','=',array('1')),
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
				'required'    => array('footer_social_toggle','=',array('1')),
				'default'     => 1,
				'type'        => 'switch',
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'shoestrap_module_footer_options_modifier', $section );
			
			$sections[] = $section;
			return $sections;
		}

		/**
		 * If the options selected require the insertion of some custom CSS to the document head, generate that CSS here
		 */

		function css() {
			$bg         = shoestrap_getVariable( 'footer_background' );
			$cl         = shoestrap_getVariable( 'footer_color' );
			$cl_brand   = shoestrap_getVariable( 'color_brand_primary' );
			$opacity    = ( intval( shoestrap_getVariable( 'footer_opacity' ) ) ) / 100;
			$rgb        = Shoestrap_Color::get_rgb( $bg, true );
			$border     = shoestrap_getVariable( 'footer_border' );
			$top_margin = shoestrap_getVariable( 'footer_top_margin' );

			$container_margin = $top_margin * 0.381966011;

			$style = 'footer.content-info {';
				$style .= 'color:' . $cl . ';';

				$style .= ( $opacity != 1 && $opacity != "" ) ? 'background: rgba(' . $rgb . ',' . $opacity . ');' : 'background:' . $bg . ';';
				$style .= ( !empty($border) && $border['border-top'] > 0 && !empty($border['border-color']) ) ? 'border-top:' . $border['border-top'] . ' ' . $border['border-style'] . ' ' . $border['border-color'] . ';' : '';
				$style .= 'padding: 18px 10px 18px;';
				$style .= ( !empty($top_margin) ) ? 'margin-top:'. $top_margin .'px;' : '';
			$style .= '}';

			$style .= 'footer div.container { margin-top:'. $container_margin .'px; }';
			$style .= '#copyright-bar { line-height: 30px; }';
			$style .= '#footer_social_bar { line-height: 30px; font-size: 16px; text-align: right; }';
			$style .= '#footer_social_bar a { margin-left: 9px; padding: 3px; color:' . $cl . '; }';
			$style .= '#footer_social_bar a:hover, #footer_social_bar a:active { color:' . $cl_brand . ' !important; text-decoration:none; }';

			wp_add_inline_style( 'shoestrap_css', $style );
		}

		function html() {
			global $ss_framework, $ss_social;

			$blog_name  = get_bloginfo( 'name', 'display' );
			$ftext      = shoestrap_getVariable( 'footer_text' );

			$ftext = ( $ftext == '' ) ? '&copy; [year] [sitename]' : $ftext;

			$ftext = str_replace( '[year]', date( 'Y' ), $ftext );
			$ftext = str_replace( '[sitename]', $blog_name, $ftext );

			$social = shoestrap_getVariable( 'footer_social_toggle' );
			$social_width = shoestrap_getVariable( 'footer_social_width' );

			$width = 12;

			// Social is enabled, we're modifying the width!
			$width = ( intval( $social_width ) > 0 && $social ) ? $width - intval( $social_width ) : $width;

			$social_blank = shoestrap_getVariable( 'footer_social_new_window_toggle' );

			$blank = ( $social_blank == 1 ) ? ' target="_blank"' : '';

			$networks = $ss_social->get_social_links();

			do_action( 'shoestrap_footer_before_copyright' );
			?>

			<div id="footer-copyright">
				<article class="<?php echo Shoestrap_Layout::container_class(); ?>">
					<div id="copyright-bar" class="col-lg-<?php echo $width; ?>"><?php echo $ftext; ?></div>
					<?php if ( $social && !is_null( $networks ) && count( $networks ) > 0 ) : ?>
						<?php echo $ss_framework->make_col( 'open', 'div', array( 'large' => $social_width ), 'footer_social_bar' ); ?>">
							<?php foreach ( $networks as $network ) : ?>
								<?php if ( $network['url'] == '' ) continue; ?>
								<a href="<?php echo $network['url']; ?>"<?php echo $blank;?> title="<?php echo $network['icon']; ?>">
									<span class="icon el-icon-<?php echo $network['icon']; ?>"></span>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php echo $ss_framework->clearfix(); ?>
				</article>
			</div>
			<?php
		}
	}
}

$footer = new Shoestrap_Footer();