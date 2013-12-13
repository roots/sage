<?php
/**
 * @package Redux_Tracking
 */

if ( !class_exists( 'ReduxFramework' ) ) {
	return;
}

/**
 * Class that creates the tracking functionality for Redux, as the core class might be used in more plugins,
 * it's checked for existence first.
 *
 * NOTE: this functionality is opt-in. Disabling the tracking in the settings or saying no when asked will cause
 * this file to not even be loaded.
 */


if ( !class_exists( 'Redux_Tracking' ) ) {
	/**
	 * Class Redux_Tracking
	 */
	class Redux_Tracking extends ReduxFramework {

		/**
		 * Class constructor
		 * @param ReduxFramework $parent
		 */
		function __construct($parent){

			$options = get_option( 'Redux_Framework' );

			if ( ! isset( $options['allow_tracking'] ) && isset( $_GET['page'] ) && $_GET['page'] == $parent->args['page_slug'] ) {
				wp_enqueue_style( 'wp-pointer' );
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui' );
				wp_enqueue_script( 'wp-pointer' );
				wp_enqueue_script( 'utils' );
				add_action( 'admin_print_footer_scripts', array( $this, 'tracking_request' ) );
			} 

			if ($options['allow_tracking'] == true) {
				// The tracking checks daily, but only sends new data every 7 days.
				if ( !wp_next_scheduled( 'redux_tracking' ) ) {
					wp_schedule_event( time(), 'daily', 'redux_tracking' );
				}
				add_action( 'redux_tracking', array( $this, 'tracking' ) );
			}
		}


		/**
		 * Shows a popup that asks for permission to allow tracking.
		 */
		function tracking_request() {
			$id    = '#wpadminbar';
			$nonce = wp_create_nonce( 'redux_activate_tracking' );

			$content = '<h3>' . __( 'Help improve Our Panel', 'redux-framework' ) . '</h3>';
			$content .= '<p>' . __( 'Please helps us improve our panel by allowing us to gather anonymous usage stats so we know which configurations, plugins and themes to test with.', 'redux-framework' ) . '</p>';
			$opt_arr = array(
				'content'  => $content,
				'position' => array( 'edge' => 'top', 'align' => 'center' )
			);
			$button2 = __( 'Allow tracking', 'redux-framework' );

			$function2 = 'redux_store_answer("yes","' . $nonce . '")';
			$function1 = 'redux_store_answer("no","' . $nonce . '")';

			$this->print_scripts( $id, $opt_arr, __( 'Do not allow tracking', 'redux-framework' ), $button2, $function2, $function1 );
		}		


		/**
		 * Prints the pointer script
		 *
		 * @param string      $selector         The CSS selector the pointer is attached to.
		 * @param array       $options          The options for the pointer.
		 * @param string      $button1          Text for button 1
		 * @param string|bool $button2          Text for button 2 (or false to not show it, defaults to false)
		 * @param string      $button2_function The JavaScript function to attach to button 2
		 * @param string      $button1_function The JavaScript function to attach to button 1
		 */
		function print_scripts( $selector, $options, $button1, $button2 = false, $button2_function = '', $button1_function = '' ) {
			?>
			<script type="text/javascript">
				//<![CDATA[
				(function ($) {
					$(document).ready(function(){
					var redux_pointer_options = <?php echo json_encode( $options ); ?>, setup;

					function redux_store_answer(input, nonce) {
						var redux_tracking_data = {
							action        : 'redux_allow_tracking',
							allow_tracking: input,
							nonce         : nonce
						}
						jQuery.post(ajaxurl, redux_tracking_data, function () {
							jQuery('#wp-pointer-0').remove();
						});
					}

					redux_pointer_options = $.extend(redux_pointer_options, {
						buttons: function (event, t) {
							button = jQuery('<a id="pointer-close" style="margin-left:5px" class="button-secondary">' + '<?php echo $button1; ?>' + '</a>');
							button.bind('click.pointer', function () {
								t.element.pointer('close');
							});
							return button;
						},
						close  : function () {
						}
					});

					setup = function () {
						$('<?php echo $selector; ?>').pointer(redux_pointer_options).pointer('open');
						<?php if ( $button2 ) { ?>
						jQuery('#pointer-close').after('<a id="pointer-primary" class="button-primary">' + '<?php echo $button2; ?>' + '</a>');
						jQuery('#pointer-primary').click(function () {
							<?php echo $button2_function; ?>
						});
						jQuery('#pointer-close').click(function () {
							<?php if ( $button1_function == '' ) { ?>
							redux_setIgnore("tour", "wp-pointer-0", "<?php echo wp_create_nonce( 'wpseo-ignore' ); ?>");
							<?php } else { ?>
							<?php echo $button1_function; ?>
							<?php } ?>
						});
						<?php } ?>
					};

					if (redux_pointer_options.position && redux_pointer_options.position.defer_loading)
						$(window).bind('load.wp-pointers', setup);
					else
						$(document).ready(setup);
				});	
				})(jQuery);
				//]]>
			</script>
		<?php
		}


		/**
		 * Main tracking function.
		 */
		function tracking() {
			// Start of Metrics
			global $blog_id, $wpdb;

			$hash = get_option( 'Redux_Tracking_Hash' );
			if ( !isset( $hash ) || !$hash || empty( $hash ) ) {
				$hash = md5( site_url() .'-'. $_SERVER['REMOTE_ADDR'] );
				update_option( 'Redux_Tracking_Hash', $hash );
			}

			$data = get_transient( 'redux_tracking_cache' );
			if ( !$data ) {

				$pts = array();
				foreach ( get_post_types( array( 'public' => true ) ) as $pt ) {
					$count    = wp_count_posts( $pt );
					$pts[$pt] = $count->publish;
				}

				$comments_count = wp_count_comments();
            	$theme_data = wp_get_theme();
				$theme      = array(
					'version'  => $theme_data->Version,
					'name'     => $theme_data->Name,
					'author'   => $theme_data->Author,
					'template' => $theme_data->Template,
				);			

				$plugins = array();
				foreach ( get_option( 'active_plugins' ) as $plugin_path ) {
					if ( !function_exists( 'get_plugin_data' ) )
						require_once( ABSPATH . 'wp-admin/includes/admin.php' );

					$plugin_info = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_path );

					$slug           = str_replace( '/' . basename( $plugin_path ), '', $plugin_path );
					$plugins[$slug] = array(
						'version'    => $plugin_info['Version'],
						'name'       => $plugin_info['Name'],
						'plugin_uri' => $plugin_info['PluginURI'],
						'author'     => $plugin_info['AuthorName'],
						'author_uri' => $plugin_info['AuthorURI'],
					);
				}

				$data = array(
					'_id' => $hash,
					'localhost' => ( $_SERVER['REMOTE_ADDR'] === '127.0.0.1' ) ? 1 : 0,
					'site'     => array(
						'hash'      => $hash,
						'version'   => get_bloginfo( 'version' ),
						'multisite' => is_multisite(),
						'users'     => $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->users INNER JOIN $wpdb->usermeta ON ({$wpdb->users}.ID = {$wpdb->usermeta}.user_id) WHERE 1 = 1 AND ( {$wpdb->usermeta}.meta_key = %s )", 'wp_' . $blog_id . '_capabilities' ) ),
						'lang'      => get_locale()
					),
					'pts'      => $pts,
					'comments' => array(
						'total'    => $comments_count->total_comments,
						'approved' => $comments_count->approved,
						'spam'     => $comments_count->spam,
						'pings'    => $wpdb->get_var( "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_type = 'pingback'" ),
					),
					'options'  => apply_filters( 'redux/tracking/options', array() ),
					'theme'    => $theme,
					'developer'=> apply_filters( 'redux/tracking/developer', array() ),
					'plugins'  => $plugins,
				);
				if (empty($data['developer'])) {
					unset($data['developer']);
				}
				$args = array(
					'body' => $data
				);
				$response = wp_remote_post( 'https://redux-tracking.herokuapp.com', $args );

				// Store for a week, then push data again.
				set_transient( 'redux_tracking_cache', true, 7 * 60 * 60 * 24 );
			}
		}
	}


	/**
	 * Adds tracking parameters for Redux settings. Outside of the main class as the class could also be in use in other ways.
	 *
	 * @param array $options
	 * @return array
	 */
	function redux_tracking_additions( $options ) {
		$opt = array();

		$options['redux'] = array(
			'demo_mode' => get_option( 'ReduxFrameworkPlugin'),
		);
		return $options;
	}
	add_filter( 'redux/tracking/options', 'redux_tracking_additions' );


	function redux_allow_tracking_callback() {

		// Verify that the incoming request is coming with the security nonce
		if( wp_verify_nonce( $_REQUEST['nonce'], 'redux_activate_tracking' ) ) {
			$option = get_option('Redux_Framework');
			$option['allow_tracking'] = $_REQUEST['allow_tracking'];
			if ( update_option( 'Redux_Framework', $option ) ) {
				die( '1' );
			} else {
				die( '0' );
			}
		} else {
			// Send -1 if the attempt to save via Ajax was completed invalid.
			die( '-1' );
		} // end if

	}
	add_action('wp_ajax_redux_allow_tracking', 'redux_allow_tracking_callback');

}