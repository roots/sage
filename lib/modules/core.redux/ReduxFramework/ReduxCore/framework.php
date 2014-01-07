<?php

/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     Redux_Framework
 * @subpackage  Core
 * @author      Redux Framework Team
 * @version     3.1.4 
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if ( !function_exists( 'wp_get_current_user' ) ) {
    // Fix from @kprovance. Bug #265.
    //require( ABSPATH . WPINC . '/pluggable.php' );
}

// Fix for the GT3 page builder: http://www.gt3themes.com/wordpress-gt3-page-builder-plugin/
/** @global string $pagenow */
if(has_action('ecpt_field_options_')) {
    global $pagenow;
    if ( $pagenow === 'admin.php' ) {
        /** @noinspection PhpUndefinedCallbackInspection */
        remove_action( 'admin_init', 'pb_admin_init' );
    }
}


// Don't duplicate me!
if( !class_exists( 'ReduxFramework' ) ) {

    /**
     * Main ReduxFramework class
     *
     * @since       1.0.0
     */
    class ReduxFramework {

        public static $_version = '3.1.4';
        public static $_dir;
        public static $_url;
        public static $_properties;

        static function init() {

			// Windows-proof constants: replace backward by forward slashes. Thanks to: @peterbouwmeester
			self::$_dir     = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
			$wp_content_dir = trailingslashit( str_replace( '\\', '/', WP_CONTENT_DIR ) );
			$relative_url   = str_replace( $wp_content_dir, '', self::$_dir );
			$wp_content_url = ( is_ssl() ? str_replace( 'http://', 'https://', WP_CONTENT_URL ) : WP_CONTENT_URL );
			self::$_url     = trailingslashit( $wp_content_url ) . $relative_url;

/**
        Still need to port these.

            $defaults['footer_credit']      = '<span id="footer-thankyou">' . __( 'Options panel created using', $this->args['domain']) . ' <a href="' . $this->framework_url . '" target="_blank">' . __('Redux Framework', $this->args['domain']) . '</a> v' . self::$_version . '</span>';
            $defaults['help_tabs']          = array();
            $defaults['help_sidebar']       = ''; // __( '', $this->args['domain'] );
            $defaults['database']           = ''; // possible: options, theme_mods, theme_mods_expanded, transient
            $defaults['customizer']         = false; // setting to true forces get_theme_mod_expanded
            $defaults['global_variable']    = '';
            $defaults['output']             = true; // Dynamically generate CSS
            $defaults['transient_time']     = 60 * MINUTE_IN_SECONDS;

            // The defaults are set so it will preserve the old behavior.
            $defaults['default_show']       = false; // If true, it shows the default value
            $defaults['default_mark']       = ''; // What to print by the field's title if the value shown is default
**/

            self::$_properties = array( 
                'args' => array(
                    'opt_name' => array(
                            'required', 
                            'data_type'=>'string', 
                            'label'=>'Option Name', 
                            'desc'=>'Must be defined by theme/plugin. Is the unique key allowing multiple instance of Redux within a single Wordpress instance.', 
                            'default'=>''
                        ),
                    'google_api_key' => array(
                            'data_type'=>'string', 
                            'label'=>'Google Web Fonts API Key', 
                            'desc'=>'Key used to request Google Webfonts. Google fonts are omitted without this.', 
                            'default'=>''
                        ),
                    'last_tab' => array( // Do we need this?
                            'data_type'=>'string', 
                            'label'=>'Last Tab', 
                            'desc'=>'Last tab used.', 
                            'default'=>'0'
                        ),  
                    'menu_icon' => array( 
                            'data_type'=>'string', 
                            'label'=>'Default Menu Icon', 
                            'desc'=>'Default menu icon used by sections when one is not specified.', 
                            'default'=> self::$_url . 'assets/img/menu_icon.png'
                        ),                  

                    'menu_title' => array( 
                            'data_type'=>'string', 
                            'label'=>'Menu Title', 
                            'desc'=>'Label displayed when the admin menu is available.', 
                            'default'=> __( 'Options', 'redux-framework' )
                        ),              
                    'page_title' => array( 
                            'data_type'=>'string', 
                            'label'=>'Page Title', 
                            'desc'=>'Title used on the panel page.', 
                            'default'=> __( 'Options', 'redux-framework' )
                        ),  
                   'page_icon' => array( 
                            'data_type'=>'string', 
                            'label'=>'Page Title', 
                            'desc'=>'Icon class to be used on the options page.', 
                            'default'=> 'icon-themes'
                        ),      
                   'page_slug' => array( 
                            'required', 
                            'data_type'=>'string', 
                            'label'=>'Page Slug', 
                            'desc'=>'Slug used to access options panel.', 
                            'default'=> '_options'
                        ),    
                   'page_permissions' => array( 
                            'required', 
                            'data_type'=>'string', 
                            'label'=>'Page Capabilities', 
                            'desc'=>'Permissions needed to access the options panel.', 
                            'default'=> 'manage_options'
                        ),  
                    'menu_type' => array(
                        'required', 
                        'data_type' => 'varchar',
                        'label' => 'Page Type',
                        'desc' => 'Specify if the admin menu should appear or not.',
                        'default' => 'menu',
                        'form' => array('type' => 'select', 'options' => array('menu' => 'Admin Menu', 'submenu' => 'Submenu Only')),
                        'validation' => array('required'),
                    ), 
                    'page_parent' => array(
                        'required', 
                        'data_type' => 'varchar',
                        'label' => 'Page Parent',
                        'desc' => 'Specify if the admin menu should appear or not.',
                        'default' => 'themes.php',
                        'form' => array('type' => 'select', 'options' => array('index.php' => 'Dashboard', 'edit.php' => 'Posts', 'upload.php' => 'Media', 'link-manager.php' => 'Links', 'edit.php?post_type=page' => 'pages', 'edit-comments.php' => 'Comments', 'themes.php' => 'Appearance', 'plugins.php' => 'Plugins', 'users.php' => 'Users', 'tools.php' => 'Tools', 'options-general.php' => 'Settings', )),
                        'validation' => array('required'),
                    ),                       
                   'page_priority' => array( 
                            'type'=>'int', 
                            'label'=>'Page Position', 
                            'desc'=>'Location where this menu item will appear in the admin menu. Warning, beware of overrides.', 
                            'default'=> null
                        ),  
                    'output' => array(
                            'required', 
                            'data_type'=>'bool',
                            'form' => array('type' => 'radio', 'options' => array(true => 'Enabled', false => 'Disabled')),
                            'label'=>'Output/Generate CSS', 
                            'desc'=>'Global shut-off for dynamic CSS output by the framework',
                            'default'=>true
                        ),
                    'allow_sub_menu' => array(
                            'data_type'=>'bool',
                            'form' => array('type' => 'radio', 'options' => array(true => 'Enabled', false => 'Disabled')),
                            'label'=>'Allow Submenu', 
                            'desc'=>'Turn on or off the submenu that will typically be shown under Appearance.', 
                            'default'=>true
                        ),                        
                    'show_import_export' => array(
                            'data_type'=>'bool',
                            'form' => array('type' => 'radio', 'options' => array(true => 'Show', false => 'Hide')),
                            'label'=>'Show Import/Export', 
                            'desc'=>'Show/Hide the import/export tab.', 
                            'default'=>true
                        ),  
                    'dev_mode' => array(
                            'data_type'=>'bool',
                            'form' => array('type' => 'radio', 'options' => array(true => 'Enabled', false => 'Disabled')),
                            'label'=>'Developer Mode', 
                            'desc'=>'Turn on or off the dev mode tab.', 
                            'default'=>false
                        ), 
                    'system_info' => array(
                            'data_type'=>'bool',
                            'form' => array('type' => 'radio', 'options' => array(true => 'Enabled', false => 'Disabled')),
                            'label'=>'System Info', 
                            'desc'=>'Turn on or off the system info tab.', 
                            'default'=>false
                        ),                                                         
                ),
            );  

        }      

        public $framework_url       = 'http://www.reduxframework.com/';
		public $instance			= null;
        public $page                = '';
        public $args                = array(
            'opt_name'           => '', // Must be defined by theme/plugin
            'domain'             => 'redux-framework', // Translation domain key
            'google_api_key'     => '', // Must be defined to add google fonts to the typography module
            'last_tab'           => '',
            'menu_icon'          => '',
            'menu_title'         => '',
            'page_icon'          => 'icon-themes',
            'page_title'         => '',
            'page_slug'          => '_options',
            'page_permissions'   => 'manage_options',
            'menu_type'          => 'menu',
            'page_parent'        => 'themes.php',
            'page_priority'      => null,
            'allow_sub_menu'     => true,
            'save_defaults'      => true, // Save defaults to the DB on it if empty
            'show_import_export' => true, // REMOVE
            'dev_mode'           => false, // REMOVE
            'system_info'        => false, // REMOVE
            'footer_credit'      => '',
            'help_tabs'          => array(),
            'help_sidebar'       => '', // __( '', $this->args['domain'] );
            'database'           => '', // possible: options, theme_mods, theme_mods_expanded, transient
            'customizer'         => false, // setting to true forces get_theme_mod_expanded
            'global_variable'    => '',
            'output'             => true, // Dynamically generate CSS
            'output_tag'         => true, // Print Output Tag
            'transient_time'     => '',
            'default_show'       => false, // If true, it shows the default value
            'default_mark'       => '', // What to print by the field's title if the value shown is default
        );

        public $sections            = array(); // Sections and fields
        public $errors              = array(); // Errors
        public $warnings            = array(); // Warnings
        public $options             = array(); // Option values
        public $options_defaults    = null; // Option defaults
        public $localize_data       = array(); // Information that needs to be localized
		public $folds    			= array(); // The itms that need to fold.
		public $path 				= '';
		public $output 				= array(); // Fields with CSS output selectors
        public $outputCSS           = null; // CSS that get auto-appended to the header
        public $compilerCSS           = null; // CSS that get sent to the compiler hook
        public $customizerCSS       = null; // CSS that goes to the customizer
        public $fieldsValues        = array(); //all fields values in an id=>value array so we can check dependencies
        public $fieldsHidden        = array(); //all fields that didn't pass the dependency test and are hidden
        public $toHide              = array(); // Values to hide on page load

		/**
		 * Class Constructor. Defines the args for the theme options class
		 * @since       1.0.0
		 * @param       array $sections   Panel sections.
		 * @param       array $args       Class constructor arguments.
		 * @param       array $extra_tabs Extra panel tabs. // REMOVE
		 * @return \ReduxFramework
		 */
        public function __construct( $sections = array(), $args = array(), $extra_tabs = array() ) {
            
            global $wp_version;
            
            // Set values
            $this->args = wp_parse_args( $args, $this->args );
            if ( empty( $this->args['transient_time'] ) ) {
                $this->args['transient_time'] = 60 * MINUTE_IN_SECONDS;
            }
            if ( empty( $this->args['footer_credit'] ) ) {
                $this->args['footer_credit'] = '<span id="footer-thankyou">' . sprintf( __( 'Options panel created using %1$s', $this->args['domain'] ), '<a href="'.esc_url( $this->framework_url ).'" target="_blank">'.__( 'Redux Framework', $this->args['domain'] ).'</a> v'.self::$_version ) . '</span>';
            }
            if ( empty( $this->args['menu_title'] ) ) {
                $this->args['menu_title'] = __( 'Options', $this->args['domain'] );
            }
            if ( empty( $this->args['page_title'] ) ) {
                $this->args['page_title'] = __( 'Options', $this->args['domain'] );
            }
            $this->args = apply_filters( 'redux/args/' . $this->args['opt_name'], $this->args ); // Filter the args
               


            if ( !empty( $this->args['opt_name'] ) ) {
                /**
                 
                    SHIM SECTION
                    Old variables and ways of doing things that need correcting.  ;) 

                 **/
                // Variable name change
                if ( !empty( $this->args['page_cap'] ) ) {
                    $this->args['page_permissions'] = $this->args['page_cap'];
                    unset( $this->args['page_cap'] );
                }
                if ( !empty( $this->args['page_position'] ) ) {
                    $this->args['page_priority'] = $this->args['page_position'];
                    unset( $this->args['page_position'] );
                }
                if ( !empty( $this->args['page_type'] ) ) {
                    $this->args['menu_type'] = $this->args['page_type'];
                    unset( $this->args['page_type'] );
                }

                // Get rid of extra_tabs! Not needed.
                if( is_array( $extra_tabs ) && !empty( $extra_tabs ) ) {
                    foreach( $extra_tabs as $tab ) {
                        array_push($this->sections, $tab);
                    }
                }            

                // Move to the first loop area!
                $this->sections = apply_filters('redux-sections',$sections); // REMOVE LATER
                $this->sections = apply_filters('redux-sections-'.$this->args['opt_name'],$this->sections); // REMOVE LATER
                $this->sections = apply_filters('redux/options/'.$this->args['opt_name'].'/sections',$this->sections);

                // Construct hook
                do_action( 'redux/contruct', $this );

                // Set the default values
                $this->_set_default_options(); 
                $this->_internationalization();


                // Register extra extensions
                $this->_register_extensions(); 
                
                $this->_tracking();

                // Set option with defaults
                //add_action( 'init', array( &$this, '_set_default_options' ), 101 );

                // Options page
                add_action( 'admin_menu', array( &$this, '_options_page' ) );

                // Register setting
                add_action( 'admin_init', array( &$this, '_register_settings' ) );

                // Enqueue the admin page CSS and JS
                add_action( 'admin_enqueue_scripts', array( &$this, '_enqueue' ) );

                // Any dynamic CSS output, let's run
                add_action( 'wp_head', array( &$this, '_enqueue_output' ), 150 );
                
                // Add tracking. PLEASE leave this in tact! It helps us gain needed statistics of uses. Opt-in of course.
                //add_action( 'init', array( &$this, '_tracking' ), 200 );   

                // Start internationalization
                //add_action( 'init', array( &$this, '_internationalization' ), 100 );            

                // Hook into the WP feeds for downloading exported settings
                add_action( 'do_feed_reduxopts-' . $this->args['opt_name'], array( &$this, '_download_options' ), 1, 1 );

            }

            
            // Loaded hook
            do_action( 'redux/loaded', $this );

		}

		/**
		 * Load the plugin text domain for translation.
		 * @param string $opt_name
		 * @since    3.0.5
		 */
		public function _internationalization() {
            $locale = apply_filters( 'redux/textdomain/'. $this->args['opt_name'], get_locale(), $this->args['domain'] );
            if (strpos($locale, '_') === false ) {
                if ( file_exists( trailingslashit( WP_LANG_DIR ) . $this->args['domain'] . '/' . $this->args['domain'] . '-' . strtolower($locale).'_'.strtoupper($locale) . '.mo' ) ) {
                    $locale = strtolower($locale).'_'.strtoupper($locale);    
                }
            }
            load_textdomain( $this->args['domain'], trailingslashit( WP_LANG_DIR ) . $this->args['domain'] . '/' . $this->args['domain'] . '-' . $locale . '.mo' );
            load_textdomain( $this->args['domain'], dirname( __FILE__ ) . '/languages/' . $this->args['domain'] . '-' . $locale . '.mo' );
        }

		/**
		 * @return ReduxFramework
		 */
		public function get_instance() {
        	return $this->instance;
        }

        public function _tracking() {
            include_once( dirname( __FILE__ ) . '/inc/tracking.php' );
			new Redux_Tracking($this);
        }

        /**
         * ->_get_default(); This is used to return the default value if default_show is set
         *
         * @since       1.0.1
         * @access      public
         * @param       string $opt_name The option name to return
         * @param       mixed $default (null)  The value to return if default not set
         * @return      mixed $default
         */
        public function _get_default( $opt_name, $default = null ) {
            if( $this->args['default_show'] == true ) {

                if( is_null( $this->options_defaults ) ) {
                	$this->_default_values(); // fill cache
                }

                $default = array_key_exists( $opt_name, $this->options_defaults ) ? $this->options_defaults[$opt_name] : $default;
            }
            return $default;
        }

        /**
         * ->get(); This is used to return and option value from the options array
         *
         * @since       1.0.0
         * @access      public
         * @param       string $opt_name The option name to return
         * @param       mixed $default (null) The value to return if option not set
         * @return      mixed
         */
        public function get( $opt_name, $default = null ) {
            return ( !empty( $this->options[$opt_name] ) ) ? $this->options[$opt_name] : $this->_get_default( $opt_name, $default );
        }

        /**
         * ->set(); This is used to set an arbitrary option in the options array
         *
         * @since       1.0.0
         * @access      public
         * @param       string $opt_name The name of the option being added
         * @param       mixed $value The value of the option being added
         * @return      void
         */
        public function set( $opt_name = '', $value = '' ) {
            if( $opt_name != '' ) {
                $this->options[$opt_name] = $value;
				$this->set_options( $this->options );
            }
        }


		/**
		 * ->set_options(); This is used to set an arbitrary option in the options array
		 *
		 * @since ReduxFramework 3.0.0
		 * @param mixed $value the value of the option being added
		 */
		function set_options( $value = '' ) {
			$value['REDUX_last_saved'] = time();
			if( !empty($value) ) {
				if ( $this->args['database'] === 'transient' ) {
					set_transient( $this->args['opt_name'] . '-transient', $value, $this->args['transient_time'] );
				} else if ( $this->args['database'] === 'theme_mods' ) {
					set_theme_mod( $this->args['opt_name'] . '-mods', $value );	
				} else if ( $this->args['database'] === 'theme_mods_expanded' ) {
					foreach ( $value as $k=>$v ) {
						set_theme_mod( $k, $v );
					}
				} else {
					update_option( $this->args['opt_name'], $value );
				}
				// Set a global variable by the global_variable argument.
				if ( $this->args['global_variable'] ) {
					$options = $this->args['global_variable'];
					global $$options;
                    $value = apply_filters( 'redux/options/'.$this->args['opt_name'].'/global_variable', $value );
					$$options = $value;					
				}
				do_action( 'redux-saved-' . $this->args['opt_name'] , $value ); // REMOVE
                do_action( 'redux/options/'.$this->args['opt_name'].'/saved', $value );
			}
		}

		/**
		 * ->get_options(); This is used to get options from the database
		 *
		 * @since ReduxFramework 3.0.0
		 */
		function get_options() {
			$defaults = false;
			if ( !empty( $this->defaults ) ) {
				$defaults = $this->defaults;
			}			

			if ( $this->args['database'] === "transient" ) {
				$result = get_transient( $this->args['opt_name'] . '-transient' );
			} else if ($this->args['database'] === "theme_mods" ) {
				$result = get_theme_mod( $this->args['opt_name'] . '-mods' );
			} else if ( $this->args['database'] === 'theme_mods_expanded' ) {
				$result = get_theme_mods();
			} else {
				$result = get_option( $this->args['opt_name']);
			}
			if ( empty( $result ) && !empty( $defaults ) ) {
				$results = $defaults;
				$this->set_options($results);
			}			
			// Set a global variable by the global_variable argument.
			if ( $this->args['global_variable'] ) {
				$options = $this->args['global_variable'];
				global $$options;
                $result = apply_filters( 'redux/options/'.$this->args['opt_name'].'/global_variable', $result );
				$$options = $result;			
			}
			//print_r($result);
			return $result;
		}

        /**
		 * ->get_options(); This is used to get options from the database
		 *
		 * @since ReduxFramework 3.0.0
		 */
		function get_wordpress_data($type = false, $args = array()) {
			
            $data = "";

            $data = apply_filters( 'redux/options/'.$this->args['opt_name'].'/wordpress_data/'.$type.'/', $data ); // REMOVE LATER
            $data = apply_filters( 'redux/options/'.$this->args['opt_name'].'/data/'.$type, $data ); 

            if ( empty( $data ) && isset( $this->wp_data[$type.implode( '-' , $args )] ) ) {
                $data = $this->wp_data[$type.implode( '-' , $args )];
            }

			if ( empty($data) && !empty($type) ) {
   
				/**
					Use data from Wordpress to populate options array
				**/
				if (!empty($type) && empty($data)) {
					if (empty($args)) {
						$args = array();
					}
					$data = array();
					$args = wp_parse_args($args, array());	
					if ($type == "categories" || $type == "category") {
						$cats = get_categories($args); 
						if (!empty($cats)) {		
							foreach ( $cats as $cat ) {
								$data[$cat->term_id] = $cat->name;
							}//foreach
						} // If
					} else if ($type == "menus" || $type == "menu") {
						$menus = wp_get_nav_menus($args);
						if(!empty($menus)) {
							foreach ($menus as $item) {
								$data[$item->term_id] = $item->name;
							}//foreach
						}//if
					} else if ($type == "pages" || $type == "page") {
						$pages = get_pages($args); 
						if (!empty($pages)) {
							foreach ( $pages as $page ) {
								$data[$page->ID] = $page->post_title;
							}//foreach
						}//if
                    } else if ($type == "terms" || $type == "term") {
                        $taxonomies = $args['taxonomies'];
                        unset($args['taxonomies']);
                        if (empty($args)) {
                            $args = array();
                        }
                        if (empty($args['args'])) {
                            $args['args'] = array();
                        }                        
                        $terms = get_terms($taxonomies, $args['args']); // this will get nothing
                        if (!empty($terms)) {       
                            foreach ( $terms as $term ) {
                                $data[$term->term_id] = $term->name;
                            }//foreach
                        } // If
                    } else if ($type == "taxonomy" || $type == "taxonomies") {
                        $taxonomies = get_taxonomies($args); 
						if (!empty($taxonomies)) {
							foreach ( $taxonomies as $key => $taxonomy ) {
								$data[$key] = $taxonomy;
							}//foreach
						} // If
                    } else if ($type == "posts" || $type == "post") {
						$posts = get_posts($args); 
						if (!empty($posts)) {
							foreach ( $posts as $post ) {
								$data[$post->ID] = $post->post_title;
							}//foreach
						}//if
					} else if ($type == "post_type" || $type == "post_types") {
                        global $wp_post_types;
                        $defaults = array(
                            'public' => true,
                            'publicly_queryable' => true,
                            'exclude_from_search' => false,
                            '_builtin' => false,
                        );
                        $args = wp_parse_args( $args, $defaults );
                        $output = 'names';
                        $operator = 'and';
                        $post_types = get_post_types($args, $output, $operator);
                        $post_types['page'] = 'page';
                        $post_types['post'] = 'post';
                        ksort($post_types);

                        foreach ( $post_types as $name => $title ) {
                            if ( isset($wp_post_types[$name]->labels->menu_name) ) {
                                $data[$name] = $wp_post_types[$name]->labels->menu_name;
                            } else {
                                $data[$name] = ucfirst($name);
                            }
                        }
					} else if ($type == "tags" || $type == "tag") { // NOT WORKING!
						$tags = get_tags($args); 
						if (!empty($tags)) {
							foreach ( $tags as $tag ) {
								$data[$tag->term_id] = $tag->name;
							}//foreach
						}//if
					} else if ($type == "menu_location" || $type == "menu_locations") {
						global $_wp_registered_nav_menus;
						foreach($_wp_registered_nav_menus as $k => $v) {
		           			$data[$k] = $v;
		        		}
					}//if
					else if ($type == "elusive-icons" || $type == "elusive-icon" || $type == "elusive" || 
							 $type == "font-icon" || $type == "font-icons" || $type == "icons") {
						$font_icons = apply_filters('redux-font-icons',array()); // REMOVE LATER
                        $font_icons = apply_filters('redux/font-icons',$font_icons);
						foreach($font_icons as $k) {
		           			$data[$k] = $k;
		        		}
					}else if ($type == "roles") {
						/** @global WP_Roles $wp_roles */
						global $wp_roles;
                        $data = $wp_roles->get_names();
					}else if ($type == "sidebars" || $type == "sidebar") {
                        /** @global array $wp_registered_sidebars */
                        global $wp_registered_sidebars;
                        foreach ($wp_registered_sidebars as $key=>$value) {
                            $data[$key] = $value['name'];
                        }
                    }else if ($type == "capabilities") {
						/** @global WP_Roles $wp_roles */
						global $wp_roles;
                        foreach( $wp_roles->roles as $role ){
                            foreach( $role['capabilities'] as $key => $cap ){
                                $data[$key] = ucwords(str_replace('_', ' ', $key));
                            }
                        }
					}else if ($type == "callback") {
						$data = call_user_func($args[0]);
					}//if			
				}//if
                
                $this->wp_data[$type.implode( '-' , $args )] = $data;

			}//if
            
			return $data;
		}		

        /**
         * ->show(); This is used to echo and option value from the options array
         *
         * @since       1.0.0
         * @access      public
         * @param       string $opt_name The name of the option being shown
         * @param       mixed $default The value to show if $opt_name isn't set
         * @return      void
         */
        public function show( $opt_name, $default = '' ) {
            $option = $this->get( $opt_name );
            if( !is_array( $option ) && $option != '' ) {
                echo $option;
            } elseif( $default != '' ) {
                echo $this->_get_default( $opt_name, $default );
            }
        }

        /**
         * Get default options into an array suitable for the settings API
         *
         * @since       1.0.0
         * @access      public
         * @return      array $this->options_defaults
         */
        public function _default_values() {
            if( !is_null( $this->sections ) && is_null( $this->options_defaults ) ) {
                // fill the cache
                foreach( $this->sections as $section ) {
                    if( isset( $section['fields'] ) ) {
                        foreach( $section['fields'] as $field ) {
                            if( isset( $field['default'] ) ) {
                            	$this->options_defaults[$field['id']] = $field['default'];
                            }
                        }
                    }
                }
            }
            $this->options_defaults = apply_filters( 'redux/options/'.$this->args['opt_name'].'/defaults', $this->options_defaults );

            return $this->options_defaults;
        }


		/**
		 * Get fold values into an array suitable for setting folds
		 *
		 * @since ReduxFramework 1.0.0
		 */
		function _fold_values() {

		   /*
		    Folds work by setting the folds value like so
		    $this->folds['parentID']['parentValue'][] = 'childId'
		   */ 
//		    $folds = array();
		    if( !is_null( $this->sections ) ) {

				foreach( $this->sections as $section ) {
				    if( isset( $section['fields'] ) ) {
						foreach( $section['fields'] as $field ) {
                            //if we have required option in group field
                            if(isset($field['subfields']) && is_array($field['subfields'])){
                                foreach ($field['subfields'] as $subfield) {
                                    if(isset($subfield['required']))
                                        $this->get_fold($subfield);
                                }
                            }
						    if( isset( $field['required'] ) ) {
                                $this->get_fold($field);
						    }
						}
				    }
				}
			}
			
            
			$parents = array();
			
			foreach ($this->folds as $k=>$fold) { // ParentFolds WITHOUT parents
				if ( empty( $fold['children'] ) || !empty( $fold['children']['parents'] ) ) {
					continue;
				}
				$fold['value'] = $this->options[$k];
				foreach ($fold['children'] as $key =>$value) {
					if ($key == $fold['value']) {
						unset($fold['children'][$key]);
					}
				}
				if (empty($fold['children'])) {
					continue;
				}
				foreach ($fold['children'] as $key => $value) {
					foreach ($value as $k=> $hidden) {
                        if ( !in_array( $hidden, $this->toHide ) ) {
                            $this->toHide[] = $hidden;    
                        }
					}
				}				
				$parents[] = $fold;
			}


//*/
            
			return $this->folds;
		    
		}

		/**
		 * @param array $field
		 * @return array
		 */
		function get_fold($field){
            if ( !is_array( $field['required'] ) ) {
                /*
                Example variable:
                    $var = array(
                    'fold' => 'id'
                    );
                */
                $this->folds[$field['required']]['children'][1][] = $field['id'];
                $this->folds[$field['id']]['parent'] = $field['required'];
            } else {
//                $parent = $foldk = $field['required'][0];
                $foldk = $field['required'][0];
//                $comparison = $field['required'][1];
                $value = $foldv = $field['required'][2];                                                                                    
                //foreach( $field['required'] as $foldk=>$foldv ) {
                    

                    if ( is_array( $value ) ) {
                        /*
                        Example variable:
                            $var = array(
                            'fold' => array( 'id' , '=', array(1, 5) )
                            );
                        */
                        
                        foreach ($value as $foldvValue) {
                            //echo 'id: '.$field['id']." key: ".$foldk.' f-val-'.print_r($foldv)." foldvValue".$foldvValue;
                            $this->folds[$foldk]['children'][$foldvValue][] = $field['id'];
                            $this->folds[$field['id']]['parent'] = $foldk;
                        }
                    } else {
                        
                        //!DOVY If there's a problem, this is where it's at. These two cases.
                        //This may be able to solve this issue if these don't work
                        //if (count($field['fold']) == count($field['fold'], COUNT_RECURSIVE)) {
                        //}

                        if (count($field['required']) === 1 && is_numeric($foldk)) {
                            /*
                            Example variable:
                                $var = array(
                                'fold' => array( 'id' )
                                );
                            */  
                            $this->folds[$field['id']]['parent'] = $foldk;
                            $this->folds[$foldk]['children'][1][] = $field['id'];
                        } else {
                            /*
                            Example variable:
                                $var = array(
                                'fold' => array( 'id' => 1 )
                                );
                            */                      
                            if (empty($foldv)) {
                                $foldv = 0;
                            }
                            $this->folds[$field['id']]['parent'] = $foldk;
                            $this->folds[$foldk]['children'][$foldv][] = $field['id'];    
                        }
                    }
                //}
            }
            return $this->folds;
        }

        /**
         * Set default options on admin_init if option doesn't exist
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function _set_default_options() {

        	$this->instance = $this;

		    // Fix the global variable name
            if ( $this->args['global_variable'] == "" && $this->args['global_variable'] !== false ) {
            	$this->args['global_variable'] = str_replace('-', '_', $this->args['opt_name']);
            }

		    // Grab database values
		    $this->options = $this->get_options();

		    // Set defaults if empty
		    if( empty( $this->options ) && !empty( $this->sections ) ) {
                if ( empty( $this->options_defaults ) ) {
                    $this->options_defaults = $this->_default_values();    
                }
                if ( $this->args['save_defaults'] == true ) {
                    $this->set_options( $this->options_defaults ); // Only save these defaults to the DB if this argument is set
                }
				$this->options = $this->options_defaults;
		    }
	    
        }

		/**
		 * Class Options Page Function, creates main options page.
		 * @since       1.0.0
		 * @access      public
		 * @return void
		 */
        function _options_page() {

            if( $this->args['menu_type'] == 'submenu' ) {
                $this->page = add_submenu_page(
                    $this->args['page_parent'],
                    $this->args['page_title'],
                    $this->args['menu_title'],
                    $this->args['page_permissions'],
                    $this->args['page_slug'],
                    array( &$this, '_options_page_html' )
                );
            } else {
                $this->page = add_menu_page(
                    $this->args['page_title'],
                    $this->args['menu_title'],
                    $this->args['page_permissions'],
                    $this->args['page_slug'],
                    array( &$this, '_options_page_html' ),
                    $this->args['menu_icon'],
                    $this->args['page_priority']
                );

                if( true === $this->args['allow_sub_menu'] ) {
                    if( !isset( $section['type'] ) || $section['type'] != 'divide' ) {

                        foreach( $this->sections as $k => $section ) {
                            if ( !isset( $section['title'] ) )
                                continue;

                            if ( isset( $section['submenu'] ) && $section['submenu'] == false )
                                continue;

                            add_submenu_page(
                                $this->args['page_slug'],
                                $section['title'],
                                $section['title'],
                                $this->args['page_permissions'],
                                $this->args['page_slug'] . '&tab=' . $k,
                                create_function( '$a', "return null;" )
                            );
                        }

                        // Remove parent submenu item instead of adding null item.
                        remove_submenu_page( $this->args['page_slug'], $this->args['page_slug'] );
                    }

                    if( true === $this->args['show_import_export'] ) {
                        add_submenu_page(
                            $this->args['page_slug'],
                            __( 'Import / Export', $this->args['domain'] ),
                            __( 'Import / Export', $this->args['domain'] ),
                            $this->args['page_permissions'],
                            $this->args['page_slug'] . '&tab=import_export_default', 
                            create_function( '$a', "return null;" )
                        );
                    }

                    if( true === $this->args['dev_mode'] ) {
                        add_submenu_page(
                            $this->args['page_slug'],
                            __( 'Options Object', $this->args['domain'] ),
                            __( 'Options Object', $this->args['domain'] ),
                            $this->args['page_permissions'],
                            $this->args['page_slug'] . '&tab=dev_mode_default',
                            create_function('$a', "return null;")
                        );
                    }

                    if( true === $this->args['system_info'] ) {
                        add_submenu_page(
                            $this->args['page_slug'],
                            __( 'System Info', $this->args['domain'] ),
                            __( 'System Info', $this->args['domain'] ),
                            $this->args['page_permissions'],
                            $this->args['page_slug'] . '&tab=system_info_default',
                            create_function( '$a', "return null;" )
                        );
                    }
                }
            }
            
            add_action( 'load-' . $this->page, array( &$this, '_load_page' ) );
        }

        /**
         * Enqueue CSS/JS for options page
         *
         * @since       1.0.0
         * @access      public
         * @global      $wp_styles
         * @return      void
         */
        public function _enqueue_output() {

            if( $this->args[ 'output' ] == false ) {
                return;
            }

			/** @noinspection PhpUnusedLocalVariableInspection */
			foreach( $this->sections as $k => $section ) {
                if( isset($section['type'] ) && ( $section['type'] == 'divide' ) ) {
                    continue;
                }
                if( isset( $section['fields'] ) ) {
					/** @noinspection PhpUnusedLocalVariableInspection */
					foreach( $section['fields'] as $fieldk => $field ) {
						if( isset( $field['type'] ) && $field['type'] != "callback"  ) {
                            $field_class = 'ReduxFramework_' . $field['type'];
                            if( !class_exists( $field_class ) ) {

                                if ( !isset( $field['compiler'] ) ) {
                                    $field['compiler'] = "";
                                }

//                                $class_file = apply_filters( 'redux/field/class/'.$field['type'], self::$_dir . 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.php', $field ); // REMOVE
                                $class_file = apply_filters( 'redux/'.$this->args['opt_name'].'/field/class/'.$field['type'], self::$_dir . 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.php', $field );
                                
                                if( $class_file && file_exists($class_file) && !class_exists( $field_class ) ) {
                                    /** @noinspection PhpIncludeInspection */
                                    require_once( $class_file );
                                }
                            }	

                            if( !empty( $this->options[$field['id']] ) && class_exists( $field_class ) && method_exists( $field_class, 'output' ) ) {
                            	
                                if ( !empty($field['output']) && !is_array( $field['output'] ) ) {
                					$field['output'] = array( $field['output'] );
                				}
								$value = isset($this->options[$field['id']])?$this->options[$field['id']]:'';
                				$enqueue = new $field_class( $field, $value, $this );
								/** @noinspection PhpUndefinedMethodInspection */
								$enqueue->output();
                            }
                        }       	
                    }
                    
                }
            }
            if ( !empty( $this->outputCSS ) && $this->args['output_tag'] == true ) {
                echo '<style type="text/css" class="options-output">'.$this->outputCSS.'</style>';  
            }

            
            if ( !empty( $this->typography ) && !empty( $this->typography ) && filter_var( $this->args['output'], FILTER_VALIDATE_BOOLEAN ) ) {
                $version = !empty( $this->options['REDUX_last_saved'] ) ? $this->options['REDUX_last_saved'] : '';
                $typography = new ReduxFramework_typography( null, null, $this );
                echo '<link rel="stylesheet" id="options-google-fonts"  href="'.$typography->makeGoogleWebfontLink( $this->typography ).'&amp;v='.$version.'" type="text/css" media="all" />';
                //wp_register_style( 'redux-google-fonts', $typography->makeGoogleWebfontLink( $this->typography ), '', $version );
                //wp_enqueue_style( 'redux-google-fonts' ); 
            }

        }        

        /**
         * Enqueue CSS/JS for options page
         *
         * @since       1.0.0
         * @access      public
         * @global      $wp_styles
         * @return      void
         */
        public function _enqueue() {
            global $pagenow;

            //echo $pagenow;
            //echo $this->args['page_parent'];

            if ( !isset( $_GET['page'] ) || $_GET['page'] != $this->args['page_slug'] ) {
                return;
            }

            global $wp_styles;

            wp_register_style(
                'redux-css',
                self::$_url . 'assets/css/redux.css',
                array( 'farbtastic' ),
                filemtime( self::$_dir . 'assets/css/redux.css' ),
                'all'
            );

            wp_register_style(
                'redux-elusive-icon',
                self::$_url . 'assets/css/vendor/elusive-icons/elusive-webfont.css',
                array(),
                filemtime( self::$_dir . 'assets/css/vendor/elusive-icons/elusive-webfont.css' ),
                'all'
            );

            wp_register_style(
                'redux-elusive-icon-ie7',
                self::$_url . 'assets/css/vendor/elusive-icons/elusive-webfont-ie7.css',
                array(),
                filemtime( self::$_dir . 'assets/css/vendor/elusive-icons/elusive-webfont-ie7.css' ),
                'all'
            );

            wp_register_style(
                'select2-css',
                self::$_url . 'assets/js/vendor/select2/select2.css',
                array(),
                filemtime( self::$_dir . 'assets/js/vendor/select2/select2.css' ),
                'all'
            );          

            $wp_styles->add_data( 'redux-elusive-icon-ie7', 'conditional', 'lte IE 7' );

            wp_register_style(
                'jquery-ui-css',
                apply_filters( 'redux/page/'.$this->args['opt_name'].'/enqueue/jquery-ui-css', self::$_url . 'assets/css/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css' ),
                '',
                filemtime( self::$_dir . 'assets/css/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css' ),
                'all'
            );

            wp_enqueue_style( 'jquery-ui-css' );

            wp_enqueue_style( 'redux-lte-ie8' );

            wp_enqueue_style( 'redux-css' );

            wp_enqueue_style( 'select2-css' );

            wp_enqueue_style( 'redux-elusive-icon' );
            wp_enqueue_style( 'redux-elusive-icon-ie7' );

            if(is_rtl()){
                wp_register_style(
                    'redux-rtl-css',
                    self::$_url . 'assets/css/rtl.css',
                    '',
                    filemtime( self::$_dir . 'assets/css/rtl.css' ),
                    'all'
                );
                wp_enqueue_style( 'redux-rtl-css' );
            } 

            if ( $this->args['dev_mode'] === true) { // Pretty object output
                /*
	            wp_enqueue_script(
	                'json-view-js',
	                self::$_url . 'assets/js/vendor/jsonview.min.js',
	                array( 'jquery' ),
	                time(),
	                true
	            );
                */
            }

            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_style('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_script('jquery-ui-slider');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_style( 'wp-color-picker' );

            if ( function_exists( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            } else {
                wp_enqueue_script( 'media-upload' );
            }

            add_thickbox();

            wp_register_script( 
                'select2-js', 
                self::$_url . 'assets/js/vendor/select2/select2.min.js',
                array( 'jquery' ),
                filemtime( self::$_dir . 'assets/js/vendor/select2/select2.min.js' ),
                true
            );

            wp_register_script( 
                'ace-editor-js', 
                self::$_url . 'assets/js/vendor/ace_editor/ace.js',
                array( 'jquery' ),
                filemtime( self::$_dir . 'assets/js/vendor/ace_editor/ace.js' ),
                true
            );          
            
            // Embed the compress version unless in dev mode
            if ( isset($this->args['dev_mode'] ) && $this->args['dev_mode'] === true) {
                wp_register_script(
                    'redux-vendor',
                    self::$_url . 'assets/js/vendor.min.js',
                    array( 'jquery'),
                    time(),
                    true
                );                                        
                wp_register_script(
                    'redux-js',
                    self::$_url . 'assets/js/redux.js',
                    array( 'jquery', 'select2-js', 'ace-editor-js', 'redux-vendor' ),
                    time(),
                    true
                );
            } else {
                if ( file_exists( self::$_dir . 'assets/js/redux.min.js' ) ) {
                	wp_register_script(
                        'redux-js',
                        self::$_url . 'assets/js/redux.min.js',
                        array( 'jquery', 'select2-js', 'ace-editor-js' ),
                        filemtime( self::$_dir . 'assets/js/redux.min.js' ),
                        true
                    );
                }
            }
  
            
            foreach( $this->sections as $section ) {
                if( isset( $section['fields'] ) ) {
                    foreach( $section['fields'] as $field ) {
                        if( isset( $field['type'] ) && $field['type'] != 'callback' ) {
                            $field_class = 'ReduxFramework_' . $field['type'];
                            $class_file = apply_filters( 'redux/'.$this->args['opt_name'].'/field/class/'.$field['type'], self::$_dir . 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.php', $field );
                            if( $class_file ) {
                                if( !class_exists($field_class) ) {
                                    /** @noinspection PhpIncludeInspection */
                                    require_once( $class_file );
                                }

                                

                                if ( ( method_exists( $field_class, 'enqueue' ) ) || method_exists( $field_class, 'localize' ) ) {
                                    if ( !isset( $this->options[$field['id']] ) ) {
                                        $this->options[$field['id']] = "";
                                    }
                                    $theField = new $field_class( $field, $this->options[$field['id']], $this );
                                    
                                    if ( !wp_script_is( 'redux-field-'.$field['type'].'-js', 'enqueued' ) && class_exists($field_class) && $this->args['dev_mode'] === true && method_exists( $field_class, 'enqueue' ) ) {
                                        /** @noinspection PhpUndefinedMethodInspection */
                                        //echo "DOVY";
                                        $theField->enqueue();    
                                    }
                                    if ( method_exists( $field_class, 'localize' ) ) {
                                        /** @noinspection PhpUndefinedMethodInspection */
                                        $params = $theField->localize();
                                        if ( !isset( $this->localize_data[$field['type']] ) ) {
                                            $this->localize_data[$field['type']] = array();
                                        }
                                        $this->localize_data[$field['type']][$field['id']] = $theField->localize();
                                    } 
                                    unset($theField);                               
                                }
                            }
                        }
                    }
                }
            }


            $this->localize_data['folds'] = $this->folds;
            $this->localize_data['fieldsHidden'] = $this->fieldsHidden;
            $this->localize_data['options'] = $this->options;
            $this->localize_data['defaults'] = $this->options_defaults;
            $this->localize_data['args'] = array(
                'save_pending'      	=> __( 'You have changes that are not saved. Would you like to save them now?', $this->args['domain'] ), 
                'reset_confirm'     	=> __( 'Are you sure? Resetting will lose all custom values.', $this->args['domain'] ), 
                'reset_section_confirm' => __( 'Are you sure? Resetting will lose all custom values in this section.', $this->args['domain'] ), 
                'preset_confirm'    	=> __( 'Your current options will be replaced with the values of this preset. Would you like to proceed?', $this->args['domain'] ), 
                'opt_name'          	=> $this->args['opt_name'],
                'slug'              	=> $this->args['page_slug']
            );       

            $notices = get_transient( 'redux-notices-' . $this->args['opt_name'] );

            // Construct the errors array. 
            if( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' && !empty( $notices['errors'] ) ) {
                $theTotal = 0;
                $theErrors = array();
                foreach( $notices['errors'] as $error ) {
                    $theErrors[$error['section_id']]['errors'][] = $error;
                    if (!isset($theErrors[$error['section_id']]['total'])) {
                        $theErrors[$error['section_id']]['total'] = 0;
                    }
                    $theErrors[$error['section_id']]['total']++;
                    $theTotal++;
                }
                $this->localize_data['errors'] = array('total'=>$theTotal, 'errors'=>$theErrors);
            }

            // Construct the warnings array. 
            if( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' && !empty( $notices['warnings'] ) ) {
                $theTotal = 0;
                $theWarnings = array();
                foreach( $notices['warnings'] as $warning ) {
                    $theWarnings[$warning['section_id']]['warnings'][] = $warning;
                    if (!isset($theWarnings[$warning['section_id']]['total'])) {
                        $theWarnings[$warning['section_id']]['total'] = 0;
                    }
                    $theWarnings[$warning['section_id']]['total']++;
                    $theTotal++;
                }
                $this->localize_data['warnings'] = array('total'=>$theTotal, 'warnings'=>$theWarnings);
            }

            // Delete the notices transient
            if ( !empty( $notices['warnings'] ) || !empty( $notices['errors'] ) ) {
                delete_transient( 'redux-notices-' . $this->args['opt_name'] );
            }
            
            // Values used by the javascript
            wp_localize_script(
                'redux-js', 
                'redux', 
                $this->localize_data
            );

            wp_enqueue_script('redux-js'); // Enque the JS now

            do_action( 'redux-enqueue-' . $this->args['opt_name'], $this ); // REMOVE
            do_action( 'redux/page/' . $this->args['opt_name'] . '/enqueue' );

        }

        /**
         * Download the options file, or display it
         *
         * @since       3.0.0
         * @access      public
         * @return      void
         */
        public function _download_options(){
            /** @noinspection PhpUndefinedConstantInspection */
            if( !isset( $_GET['secret'] ) || $_GET['secret'] != md5( AUTH_KEY . SECURE_AUTH_KEY ) ) {
                wp_die( 'Invalid Secret for options use' );
                exit;
            }

            if( !isset( $_GET['feed'] ) ){
                wp_die( 'No Feed Defined' );
                exit;
            }

            $backup_options = $this->get_options( str_replace( 'redux-', '', $_GET['feed'] ) );
            $backup_options['redux-backup'] = '1';
            $content = json_encode( $backup_options );

            if( isset( $_GET['action'] ) && $_GET['action'] == 'download_options' ) {
                header( 'Content-Description: File Transfer' );
                header( 'Content-type: application/txt' );
                header( 'Content-Disposition: attachment; filename="' . str_replace( 'redux-', '', $_GET['feed'] ) . '_backup_' . date( 'd-m-Y' ) . '.json"' );
                header( 'Content-Transfer-Encoding: binary' );
                header( 'Expires: 0' );
                header( 'Cache-Control: must-revalidate' );
                header( 'Pragma: public' );
                echo $content;
                exit;
            } else {
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
                header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
                header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
                header( 'Cache-Control: no-store, no-cache, must-revalidate' );
                header( 'Cache-Control: post-check=0, pre-check=0', false );
                header( 'Pragma: no-cache' );

                // Can't include the type. Thanks old Firefox and IE. BAH.
                //header("Content-type: application/json");
                echo $content;
                exit;
            }
        }

        /**
         * Show page help
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function _load_page() {

            // Do admin head action for this page
            add_action( 'admin_head', array( &$this, 'admin_head' ) );

            // Do admin footer text hook
            add_filter( 'admin_footer_text', array( &$this, 'admin_footer_text' ) );

            $screen = get_current_screen();

            if( is_array( $this->args['help_tabs'] ) ) {
                foreach( $this->args['help_tabs'] as $tab ) {
                    $screen->add_help_tab( $tab );
                }
            }

            if( $this->args['help_sidebar'] != '' )
                $screen->set_help_sidebar( $this->args['help_sidebar'] );

            do_action( 'redux-load-page-' . $this->args['opt_name'], $screen ); // REMOVE
            do_action( 'redux/page/' . $this->args['opt_name'] . '/load' , $screen );
        }

        /**
         * Do action redux-admin-head for options page
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function admin_head() {
            do_action( 'redux-admin-head-' . $this->args['opt_name'], $this ); // REMOVE
            do_action( 'redux/page/' . $this->args['opt_name'] . '/header', $this );
        }

        /**
         * Return footer text
         *
         * @since       2.0.0
         * @access      public
         * @return      string $this->args['footer_credit']
         */
        public function admin_footer_text( ) {
            return $this->args['footer_credit'];
        }

        /**
         * Register Option for use
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function _register_settings() {

            register_setting( $this->args['opt_name'] . '_group', $this->args['opt_name'], array( &$this,'_validate_options' ) );

            if( is_null( $this->sections ) ) return;

            $runUpdate = false;

            foreach( $this->sections as $k => $section ) {

                if( isset($section['type'] ) && $section['type'] == 'divide' ) {
                    continue;
                }

				if ( empty( $section['id'] ) ) {
                    $section['id'] = sanitize_html_class( $section['title'] );	
                }                   

                // DOVY! Replace $k with $section['id'] when ready
                $section = apply_filters( 'redux-section-' . $k . '-modifier-' . $this->args['opt_name'], $section );
                $section = apply_filters( 'redux/options/'.$this->args['opt_name'].'/section/' . $section['id'] , $section );
		
                $heading = isset($section['heading']) ? $section['heading'] : $section['title'];

                add_settings_section( $this->args['opt_name'] . $k . '_section', $heading, array( &$this, '_section_desc' ), $this->args['opt_name'] . $k . '_section_group' );

                if( isset( $section['fields'] ) ) {
                    foreach( $section['fields'] as $fieldk => $field ) {
                        if ( !isset( $field['type'] ) ) {
                            continue; // You need a type!
                        }
                    	
                    	$th = "";
                        if( isset( $field['title'] ) && isset( $field['type'] ) && $field['type'] !== "info" && $field['type'] !== "group" ) {
			    			$default_mark = ( !empty($field['default']) && isset($this->options[$field['id']]) && $this->options[$field['id']] == $field['default'] && !empty( $this->args['default_mark'] ) && isset( $field['default'] ) ) ? $this->args['default_mark'] : '';
                            if (!empty($field['title'])) {
                                $th = $field['title'] . $default_mark."";
                            }
          
						    if( isset( $field['subtitle'] ) ) {
								$th .= '<span class="description">' . $field['subtitle'] . '</span>';
						    }
  
                        } 
						if (!isset($field['id'])) {
                            echo '<br /><h3>No field ID is set. Here\'s the field:</h3><pre>';
							print_r($field);
                            echo "</pre><br />";
						}
						// Set the default if it's a new field
						if (!isset($this->options[$field['id']])) {
			                if ( !empty( $this->options_defaults ) ) {
			                	$this->options[$field['id']] = array_key_exists( $field['id'], $this->options_defaults ) ? $this->options_defaults[$field['id']] : '';	
			                }
							$runUpdate = true;
						}						

						if ( $this->args['default_show'] === true && isset( $field['default'] ) && isset($this->options[$field['id']]) && $this->options[$field['id']] != $field['default'] && $field['type'] !== "info" && $field['type'] !== "group" && $field['type'] !== "editor" && $field['type'] !== "ace_editor" ) {
							$default_output = "";
						    if (!is_array($field['default'])) {
								if ( !empty( $field['options'][$field['default']] ) ) {
									if (!empty($field['options'][$field['default']]['alt'])) {
										$default_output .= $field['options'][$field['default']]['alt'] . ', ';
									} else {
										// TODO: This serialize fix may not be the best solution. Look into it. PHP 5.4 error without serialize
										$default_output .= serialize($field['options'][$field['default']]).", ";	
									}
								} else if ( !empty( $field['options'][$field['default']] ) ) {
									$default_output .= $field['options'][$field['default']].", ";
								} else if ( !empty( $field['default'] ) ) {
									$default_output .= $field['default'] . ', ';
								}
						    } else {
								foreach( $field['default'] as $defaultk => $defaultv ) {
									if (!empty($field['options'][$defaultv]['alt'])) {
										$default_output .= $field['options'][$defaultv]['alt'] . ', ';
									} else if ( !empty( $field['options'][$defaultv] ) ) {
										$default_output .= $field['options'][$defaultv].", ";
									} else if ( !empty( $field['options'][$defaultk] ) ) {
										$default_output .= $field['options'][$defaultk].", ";
									} else if ( !empty( $defaultv ) ) {
										$default_output .= $defaultv.', ';
									}
								}
						   	}
							if ( !empty( $default_output ) ) {
							    $default_output = __( 'Default', $this->args['domain'] ) . ": " . substr($default_output, 0, -2);
							}				   	
						    $th .= '<span class="showDefaults">'.$default_output.'</span>';
			            }
			            if (!isset($field['class'])) { // No errors please
			            	$field['class'] = "";
			            }
			            $field = apply_filters( 'redux-field-' . $field['id'] . 'modifier-' . $this->args['opt_name'], $field ); // REMOVE LATER
                        $field = apply_filters( 'redux/options/' . $this->args['opt_name'].'/field/' . $field['id'], $field );
						if ( !empty( $this->folds[$field['id']]['parent'] ) ) { // This has some fold items, hide it by default
						    $field['class'] .= " fold";
						}

						if ( !empty( $this->folds[$field['id']]['children'] ) ) { // Sets the values you shoe fold children on
						    $field['class'] .= " foldParent";
						}

						if ( !empty( $field['compiler'] ) ) {
							$field['class'] .= " compiler";
						}
						$this->sections[$k]['fields'][$fieldk] = $field;

                        if( isset( $this->args['display_source'] ) ) {
                            $th .= '<div id="'.$field['id'].'-settings" style="display:none;"><pre>'.var_export($this->sections[$k]['fields'][$fieldk], true).'</pre></div>';
                            $th .= '<br /><a href="#TB_inline?width=600&height=800&inlineId='.$field['id'].'-settings" class="thickbox"><small>View Source</small></a>';
                        }
                        do_action( 'redux/options/'.$this->args['opt_name'].'/field/'.$field['type'].'/register', $field);
                        extract($this->check_dependencies($field));
                        add_settings_field( $fieldk . '_field', $th, array( &$this, '_field_input' ), $this->args['opt_name'] . $k . '_section_group', $this->args['opt_name'] . $k . '_section', $field ); // checkbox

                    }
                }
            }

            do_action( 'redux-register-settings-' . $this->args['opt_name'] ); // REMOVE
            do_action( 'redux/options/'.$this->args['opt_name'].'/register', $this->sections);

			if ($runUpdate) { // Always update the DB with new fields
				$this->set_options( $this->options );
			}

			if ( get_transient( 'redux-compiler-' . $this->args['opt_name'] ) ) {
                $this->args['output_tag'] = false;
                $this->_enqueue_output();
				do_action( 'redux-compiler-' . $this->args['opt_name'], $this->options, $this->compilerCSS ); // REMOVE
                do_action( 'redux/options/' . $this->args['opt_name'] . '/compiler', $this->options, $this->compilerCSS );
                delete_transient( 'redux-compiler-' . $this->args['opt_name'] );
			}				

        }

        /**
         * Register Extensions for use
         *
         * @since       3.0.0
         * @access      public
         * @return      void
         */
        public function _register_extensions() {        	
        	
        	$path = dirname( __FILE__ ) . '/extensions/';
			$folders = scandir( $path, 1 );		 

            do_action( 'redux/extensions/'.$this->args['opt_name'].'/before', $this );  

		    foreach($folders as $folder){

		    	if ($folder === '.' or $folder === '..' or !is_dir($path . $folder) ) {
		    		continue;	
		    	} 
				$extension_class = 'ReduxFramework_Extension_' . $folder;

                if( !class_exists( $extension_class ) ) {
                    $class_file = apply_filters( 'redux-extensionclass-load', $path . $folder . '/extension_' . $folder . '.php', $extension_class ); // REMOVE LATER
                    $class_file = apply_filters( 'redux/extension/'.$this->args['opt_name'].'/'.$folder, $path . $folder . '/extension_' . $folder . '.php', $class_file );

                    if( $class_file ) {
                        /** @noinspection PhpIncludeInspection */
                        require_once( $class_file );
						/** @noinspection PhpUnusedLocalVariableInspection */
						$extension = new $extension_class( $this );
                 	}
                }
                		   		
		    }    

		    do_action( 'redux-register-extensions-' . $this->args['opt_name'], $this ); // REMOVE
            do_action( 'redux/extensions/'.$this->args['opt_name'], $this );

        }

		/**
		 * Validate the Options options before insertion
		 * @since       3.0.0
		 * @access      public
		 * @param       array $plugin_options The options array
		 * @return array|mixed|string|void
		 */
        public function _validate_options( $plugin_options ) {

            set_transient( 'redux-saved-' . $this->args['opt_name'], '1', 1000 );

            if( !empty( $plugin_options['import'] ) ) {
                if( $plugin_options['import_code'] != '' ) {
                    $import = $plugin_options['import_code'];
                } elseif( $plugin_options['import_link'] != '' ) {
                    $import = wp_remote_retrieve_body( wp_remote_get( $plugin_options['import_link'] ) );
                }

                if ( !empty( $import ) ) {
                    $imported_options = json_decode( htmlspecialchars_decode( $import ), true );
                }

                if( !empty( $imported_options ) && is_array( $imported_options ) && isset( $imported_options['redux-backup'] ) && $imported_options['redux-backup'] == '1' ) {
                    $plugin_options['REDUX_imported'] = 1;
                	foreach($imported_options as $key => $value) {
                		$plugin_options[$key] = $value;
                	}                    
                    
                    // Remove the import/export tab cookie.
                    if( $_COOKIE['redux_current_tab'] == 'import_export_default' ) {
                        setcookie( 'redux_current_tab', '', 1, '/' );
                    }

                    set_transient( 'redux-compiler-' . $this->args['opt_name'], '1', 1000 );
                    $plugin_options['REDUX_COMPILER'] = time();
                    unset( $plugin_options['defaults'], $plugin_options['compiler'], $plugin_options['import'], $plugin_options['import_code'] );
				    if ( $this->args['database'] == 'transient' || $this->args['database'] == 'theme_mods' || $this->args['database'] == 'theme_mods_expanded' ) {
						$this->set_options( $plugin_options );
						return $this->options;
				    }
                    return $plugin_options;
                }
            } else {
            	$plugin_options['REDUX_imported'] = false;
            }

            if( !empty( $plugin_options['defaults'] ) ) {
                set_transient( 'redux-compiler-' . $this->args['opt_name'], '1', 1000 );
                $plugin_options = $this->_default_values();
                $plugin_options['REDUX_COMPILER'] = time();
                if ( $this->args['database'] == 'transient' || $this->args['database'] == 'theme_mods' || $this->args['database'] == 'theme_mods_expanded' ) {
				    $this->set_options( $plugin_options );
					return $this->options;
				}
                return $plugin_options;
            }
            if( isset( $plugin_options['defaults-section'] ) ) {
            	$compiler = false;
            	foreach ($this->sections[$plugin_options['redux-section']]['fields'] as $field) {
                    if ( isset( $this->options_defaults[$field['id']] ) ) {
                        $plugin_options[$field['id']] = $this->options_defaults[$field['id']];
                    } else {
                        $plugin_options[$field['id']] = "";
                    }
            		if (isset($field['compiler'])) {
            			$compiler = true;
            		}
            	}
            	if ($compiler) {
					set_transient( 'redux-compiler-' . $this->args['opt_name'], '1', 1000 );
                	$plugin_options['REDUX_COMPILER'] = time();
            	}
            	$plugin_options['defaults'] = true;
                unset( $plugin_options['compiler'], $plugin_options['import'], $plugin_options['import_code'], $plugin_options['redux-section'] );
				$this->set_options( $plugin_options );
				return $plugin_options;
            }            

            // Validate fields (if needed)
            $plugin_options = $this->_validate_values( $plugin_options, $this->options );

            if( !empty( $this->errors ) || !empty( $this->warnings ) ) {
            	set_transient( 'redux-notices-' . $this->args['opt_name'], array( 'errors' => $this->errors, 'warnings' => $this->warnings ), 1000 );
            }    

            do_action_ref_array('redux-validate-' . $this->args['opt_name'], array(&$plugin_options, $this->options)); // REMOVE
            do_action_ref_array('redux/options/' . $this->args['opt_name'].'/validate', array(&$plugin_options, $this->options));

            if( !empty( $plugin_options['compiler'] ) ) {
            	$plugin_options['REDUX_COMPILER'] = time();
            	set_transient( 'redux-compiler-' . $this->args['opt_name'], '1', 2000 );
            }

            unset( $plugin_options['defaults'], $plugin_options['import'], $plugin_options['import_code'], $plugin_options['import_link'], $plugin_options['compiler'], $plugin_options['redux-section'] );
		    if ( $this->args['database'] == 'transient' || $this->args['database'] == 'theme_mods' || $this->args['database'] == 'theme_mods_expanded' ) {
				$this->set_options( $plugin_options );
				return $this->options;
		    }
            return $plugin_options;
        }

        /**
         * Validate values from options form (used in settings api validate function)
         * calls the custom validation class for the field so authors can override with custom classes
         *
         * @since       1.0.0
         * @access      public
         * @param       array $plugin_options
         * @param       array $options
         * @return      array $plugin_options
         */
        public function _validate_values( $plugin_options, $options ) {
            foreach( $this->sections as $k => $section ) {
                if( isset( $section['fields'] ) ) {
                    foreach( $section['fields'] as $field ) {
                        $field['section_id'] = $k;

                        if( isset( $field['type'] ) && ( $field['type'] == 'checkbox' || $field['type'] == 'checkbox_hide_below' || $field['type'] == 'checkbox_hide_all' ) ) {
                            if( !isset( $plugin_options[$field['id']] ) )
                                $plugin_options[$field['id']] = 0;
                        }

                        if( !isset( $plugin_options[$field['id']] ) || $plugin_options[$field['id']] == '' ) continue;

                        // Force validate of custom field types
                        if( isset( $field['type'] ) && !isset( $field['validate'] ) ) {
                            if( $field['type'] == 'color' || $field['type'] == 'color_gradient' ) {
                                $field['validate'] = 'color';
                            } elseif( $field['type'] == 'date' ) {
                                $field['validate'] = 'date';
                            }
                        }

                        if( isset( $field['validate'] ) ) {
                            $validate = 'Redux_Validation_' . $field['validate'];

                            if( !class_exists( $validate ) ) {
                                $class_file = apply_filters( 'redux-validateclass-load', self::$_dir . 'inc/validation/' . $field['validate'] . '/validation_' . $field['validate'] . '.php', $validate ); // REMOVE LATER
                                $class_file = apply_filters( 'redux/validate/'.$this->args['opt_name'].'/class/'.$field['validate'], self::$_dir . 'inc/validation/' . $field['validate'] . '/validation_' . $field['validate'] . '.php', $class_file );

                                if( $class_file ) {
                                    /** @noinspection PhpIncludeInspection */
                                    require_once( $class_file );
                                }

                            }

                            if( class_exists( $validate ) ) {
                            	//!DOVY - DB saving stuff. Is this right?
                            	if ( empty ( $options[$field['id']] ) ) {
                            		$options[$field['id']] = '';
                            	}

                                if ( isset( $plugin_options[$field['id']] ) && is_array( $plugin_options[$field['id']] ) && !empty( $plugin_options[$field['id']] ) ) {
                                    foreach ( $plugin_options[$field['id']] as $key => $value ) {
                                        $before = $after = null;
                                        if ( isset( $plugin_options[$field['id']][$key] ) && !empty( $plugin_options[$field['id']][$key] ) ) {
                                            $before = $plugin_options[$field['id']][$key];
                                        }
                                        if ( isset( $options[$field['id']][$key] ) && !empty( $options[$field['id']][$key] ) ) {
                                            $after = $options[$field['id']][$key];
                                        }                                        
                                        $validation = new $validate( $field, $before, $after );
                                        $plugin_options[$field['id']][$key] = $validation->value;
                                        if( isset( $validation->error ) ) {
                                            $this->errors[] = $validation->error;
                                        }
                                        if( isset( $validation->warning) ) {
                                            $this->warnings[] = $validation->warning;                                        
                                        }
                                    }
                                } else {
                                    $validation = new $validate( $field, $plugin_options[$field['id']], $options[$field['id']] );    
                                    $plugin_options[$field['id']] = $validation->value;
                                    if( isset( $validation->error ) ) {
                                        $this->errors[] = $validation->error;
                                    }
                                    if( isset( $validation->warning) ) {
                                        $this->warnings[] = $validation->warning;                                        
                                    }                                    
                                }
                                continue;
                            }
                        }

                        if( isset( $field['validate_callback'] ) && function_exists( $field['validate_callback'] ) ) {
                            $callbackvalues = call_user_func( $field['validate_callback'], $field, $plugin_options[$field['id']], $options[$field['id']] );
                            $plugin_options[$field['id']] = $callbackvalues['value'];

                            if( isset( $callbackvalues['error'] ) )
                                $this->errors[] = $callbackvalues['error'];

                            if( isset( $callbackvalues['warning'] ) )
                                $this->warnings[] = $callbackvalues['warning'];
                        }
                    }
                }
            }

            return $plugin_options;
        }

        /**
         * HTML OUTPUT.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function _options_page_html() {

            $saved = get_transient( 'redux-saved-' . $this->args['opt_name'] );
            if ( $saved ) {
            	delete_transient( 'redux-saved-' . $this->args['opt_name'] );	
            }
            echo '<div class="wrap"><h2></h2></div>'; // Stupid hack for Wordpress alerts and warnings

            echo '<div class="clear"></div>';
            echo '<div class="wrap">';

            // Do we support JS?
            echo '<noscript><div class="no-js">' . __( 'Warning- This options panel will not work properly without javascript!', $this->args['domain'] ) . '</div></noscript>';

            // Security is vital!
            echo '<input type="hidden" id="ajaxsecurity" name="security" value="' . wp_create_nonce( 'of_ajax_nonce' ) . '" />';

            do_action( 'redux-page-before-form-' . $this->args['opt_name'] ); // Remove
            do_action( 'redux/page/'.$this->args['opt_name'].'/form/before', $this );

            // Main container
            echo '<div class="redux-container">';
            echo '<form method="post" action="' . './options.php" enctype="multipart/form-data" id="redux-form-wrapper">';

            echo '<input type="hidden" id="redux-compiler-hook" name="' . $this->args['opt_name'] . '[compiler]" value="" />';
			echo '<input type="hidden" id="currentSection" name="' . $this->args['opt_name'] . '[redux-section]" value="" />';
            settings_fields( $this->args['opt_name'] . '_group' );

            // Last tab?
            if( empty( $this->options['last_tab'] ) )
                $this->options['last_tab'] = '';

            $this->options['last_tab'] = ( isset( $_GET['tab'] ) && !$saved ) ? $_GET['tab'] : $this->options['last_tab'];

            echo '<input type="hidden" id="last_tab" name="' . $this->args['opt_name'] . '[last_tab]" value="' . $this->options['last_tab'] . '" />';

            // Header area
            echo '<div id="redux-header">';
                
            if( !empty( $this->args['display_name'] ) ) {
                echo '<div class="display_header">';
                echo '<h2>' . $this->args['display_name'] . '</h2>';

                if( !empty( $this->args['display_version'] ) )
                    echo '<span>' . $this->args['display_version'] . '</span>';

                echo '</div>';
            }

            // Page icon
            // DOVY!
            echo '<div id="' . $this->args['page_icon'] . '" class="icon32"></div>';

            echo '<div class="clear"></div>';
            echo '</div>';

            // Intro text
            if( isset( $this->args['intro_text'] ) ) {
                echo '<div id="redux-intro-text">';
                echo $this->args['intro_text'];
                echo '</div>';
            }

            // Stickybar
            echo '<div id="redux-sticky">';
            echo '<div id="info_bar">';
            echo '<a href="javascript:void(0);" id="expand_options">' . __( 'Expand', $this->args['domain'] ) . '</a>';
            echo '<div class="redux-action_bar">';
            submit_button( __( 'Save Changes', $this->args['domain']), 'primary', 'redux_save', false );
            echo '&nbsp;';
            submit_button( __( 'Reset Section', $this->args['domain']), 'secondary', $this->args['opt_name'] . '[defaults-section]', false );
            echo '&nbsp;';            
            submit_button( __( 'Reset All', $this->args['domain'] ), 'secondary', $this->args['opt_name'] . '[defaults]', false );
            echo '</div>';

            echo '<div class="redux-ajax-loading" alt="' . __( 'Working...', $this->args['domain'] ) . '">&nbsp;</div>';
            echo '<div class="clear"></div>';
            echo '</div>';

            // Warning bar
            if( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' && $saved == '1' ) {
                if( isset( $this->options['REDUX_imported'] ) && $this->options['REDUX_imported'] === 1 ) {
                    echo '<div id="redux-imported">' . apply_filters( 'redux-imported-text-' . $this->args['opt_name'], '<strong>' . __( 'Settings Imported!', $this->args['domain'] ) ) . '</strong></div>';
                } else {
                    echo '<div id="redux-save">' . apply_filters( 'redux-saved-text-' . $this->args['opt_name'], '<strong>'.__( 'Settings Saved!', $this->args['domain'] ) ) . '</strong></div>';
                }
            }

            echo '<div id="redux-save-warn">' . apply_filters( 'redux-changed-text-' . $this->args['opt_name'], '<strong>'.__( 'Settings have changed, you should save them!', $this->args['domain'] ) ) . '</strong></div>';
            echo '<div id="redux-field-errors"><strong><span></span> ' . __( 'error(s) were found!', $this->args['domain'] ) . '</strong></div>';
            echo '<div id="redux-field-warnings"><strong><span></span> ' . __( 'warning(s) were found!', $this->args['domain'] ) . '</strong></div>';

            echo '</div>';

            echo '<div class="clear"></div>';

            // Sidebar
            echo '<div id="redux-sidebar">';
            echo '<ul id="redux-group-menu">';
            foreach( $this->sections as $k => $section ) {
            	if( (isset($this->args['icon_type']) && $this->args['icon_type'] == 'image') || (isset($section['icon_type']) && $section['icon_type'] == 'image')) {
                //if( !empty( $this->args['icon_type'] ) && $this->args['icon_type'] == 'image' ) {
                    $icon = ( !isset( $section['icon'] ) ) ? '' : '<img class="image_icon_type" src="' . $section['icon'] . '" /> ';
                } else {

					if ( ! empty( $section['icon_class'] ) ) {
						$icon_class = ' ' . $section['icon_class'];
					}
					elseif ( ! empty( $this->args['default_icon_class'] ) ) {
						$icon_class = ' ' . $this->args['default_icon_class'];
					}
					else {
						$icon_class = '';
					}

					$icon = ( !isset( $section['icon'] ) ) ? '<i class="icon-cog' . $icon_class . '"></i> ' : '<i class="' . $section['icon'] . $icon_class . '"></i> ';
                }

				if (isset($section['type']) && $section['type'] == "divide") {
					echo '<li class="divide">&nbsp;</li>';
				} else {
					// DOVY! REPLACE $k with $section['ID'] when used properly.
	                echo '<li id="' . $k . '_section_group_li" class="redux-group-tab-link-li">';
	                echo '<a href="javascript:void(0);" id="' . $k . '_section_group_li_a" class="redux-group-tab-link-a" data-rel="' . $k . '">' . $icon . '<span class="group_title">' . $section['title'] . '</span></a>';
	                if ( !empty( $section['sections'] ) ) {
	                	echo '<ul id="' . $k . '_section_group_li_subsections" class="sub">';
	                	foreach ($section['sections'] as $k2 => $subsection) {
	                		echo '<li id="' . $k . '_section_group_li" class="redux-group-tab-link-li">';
	                		echo '<a href="javascript:void(0);" id="' . $k . '_section_group_subsection_li_a" class="redux-group-tab-link-a" data-rel="' . $k .'sub-'.$k2.'"><span class="group_title">' . $subsection['title'] . '</span></a>';
	                		echo '</li>';
	                	}
	                	echo '</ul>';
	                }
	                echo '</li>';							
				}                
            }

            echo '<li class="divide">&nbsp;</li>';

            do_action( 'redux-page-after-sections-menu-' . $this->args['opt_name'], $this );
            do_action( 'redux/page/'.$this->args['opt_name'].'/menu/after', $this );

            if( $this->args['show_import_export'] === true ) {
                echo '<li id="import_export_default_section_group_li" class="redux-group-tab-link-li">';

                if( !empty( $this->args['icon_type'] ) && $this->args['icon_type'] == 'image' ) {
                    $icon = ( !isset( $this->args['import_icon'] ) ) ? '' : '<img src="' . $this->args['import_icon'] . '" /> ';
                } else {
                    $icon_class = ( !isset( $this->args['import_icon_class'] ) ) ? '' : ' ' . $this->args['import_icon_class'];
                    $icon = ( !isset( $this->args['import_icon'] ) ) ? '<i class="el-icon-refresh' . $icon_class . '"></i>' : '<i class="icon-' . $this->args['import_icon'] . $icon_class . '"></i> ';
                }

                echo '<a href="javascript:void(0);" id="import_export_default_section_group_li_a" class="redux-group-tab-link-a" data-rel="import_export_default">' . $icon . ' <span class="group_title">' . __( 'Import / Export', $this->args['domain'] ) . '</span></a>';
                echo '</li>';
     
                echo '<li class="divide">&nbsp;</li>';
            }

            if( $this->args['dev_mode'] === true ) {
                echo '<li id="dev_mode_default_section_group_li" class="redux-group-tab-link-li">';

                if( !empty( $this->args['icon_type'] ) && $this->args['icon_type'] == 'image' ) {
                    $icon = ( !isset( $this->args['dev_mode_icon'] ) ) ? '' : '<img src="' . $this->args['dev_mode_icon'] . '" /> ';
                } else {
                    $icon_class = ( !isset( $this->args['dev_mode_icon_class'] ) ) ? '' : ' ' . $this->args['dev_mode_icon_class'];
                    $icon = ( !isset( $this->args['dev_mode_icon'] ) ) ? '<i class="el-icon-info-sign' . $icon_class . '"></i>' : '<i class="icon-' . $this->args['dev_mode_icon'] . $icon_class . '"></i> ';
                }

                echo '<a href="javascript:void(0);" id="dev_mode_default_section_group_li_a" class="redux-group-tab-link-a custom-tab" data-rel="dev_mode_default">' . $icon . ' <span class="group_title">' . __( 'Options Object', $this->args['domain'] ) . '</span></a>';
                echo '</li>';
            }

            if( $this->args['system_info'] === true ) {
                echo '<li id="system_info_default_section_group_li" class="redux-group-tab-link-li">';

                if( !empty( $this->args['icon_type'] ) && $this->args['icon_type'] == 'image' ) {
                    $icon = ( !isset( $this->args['system_info_icon'] ) ) ? '' : '<img src="' . $this->args['system_info_icon'] . '" /> ';
                } else {
                    $icon_class = ( !isset( $this->args['system_info_icon_class'] ) ) ? '' : ' ' . $this->args['system_info_icon_class'];
                    $icon = ( !isset( $this->args['system_info_icon'] ) ) ? '<i class="el-icon-info-sign' . $icon_class . '"></i>' : '<i class="icon-' . $this->args['system_info_icon'] . $icon_class . '"></i> ';
                }

                echo '<a href="javascript:void(0);" id="system_info_default_section_group_li_a" class="redux-group-tab-link-a custom-tab" data-rel="system_info_default">' . $icon . ' <span class="group_title">' . __( 'System Info', $this->args['domain'] ) . '</span></a>';
                echo '</li>';
            }

            echo '</ul>';
            echo '</div>';

            echo '<div class="redux-main">';

            foreach( $this->sections as $k => $section ) {
                echo '<div id="' . $k . '_section_group' . '" class="redux-group-tab">';
                if ( !empty( $section['sections'] ) ) {
                	//$tabs = "";
		            echo '<div id="' . $k . '_section_tabs' . '" class="redux-section-tabs">';
		            echo '<ul>';                	
                	foreach ($section['sections'] as $subkey => $subsection) {
                		echo '<li><a href="#'.$k.'_section-tab-'.$subkey.'">'.$subsection['title'].'</a></li>';
                	}
		            echo '</ul>';
               		foreach ($section['sections'] as $subkey => $subsection) {
               			echo '<div id="' . $k .'sub-'.$subkey. '_section_group' . '" class="redux-group-tab">';
                		echo '<div id="'.$k.'_section-tab-'.$subkey.'">';
                		echo "hello".$subkey;
                		do_settings_sections( $this->args['opt_name'] . $k . '_tab_'.$subkey.'_section_group' );	
                		echo "</div>";
                	}
                	echo "</div>";
                } else {
                	do_settings_sections( $this->args['opt_name'] . $k . '_section_group' );	
                }

                echo '</div>';
            }

            if( $this->args['show_import_export'] === true ) {
                echo '<div id="import_export_default_section_group' . '" class="redux-group-tab">';

                echo '<h3>' . __( 'Import / Export Options', $this->args['domain'] ) . '</h3>';
                echo '<h4>' . __( 'Import Options', $this->args['domain'] ) . '</h4>';
                echo '<p><a href="javascript:void(0);" id="redux-import-code-button" class="button-secondary">' . __( 'Import from file', $this->args['domain'] ) . '</a> <a href="javascript:void(0);" id="redux-import-link-button" class="button-secondary">' . __( 'Import from URL', $this->args['domain'] ) . '</a></p>';

                echo '<div id="redux-import-code-wrapper">';

                echo '<div class="redux-section-desc">';
                echo '<p class="description" id="import-code-description">' . apply_filters( 'redux-import-file-description', __( 'Input your backup file below and hit Import to restore your sites options from a backup.', $this->args['domain'] ) ) . '</p>';
                echo '</div>';

                echo '<textarea id="import-code-value" name="' . $this->args['opt_name'] . '[import_code]" class="large-text noUpdate" rows="8"></textarea>';

                echo '</div>';

                echo '<div id="redux-import-link-wrapper">';

                echo '<div class="redux-section-desc">';
                echo '<p class="description" id="import-link-description">' . apply_filters( 'redux-import-link-description', __( 'Input the URL to another sites options set and hit Import to load the options from that site.', $this->args['domain'] ) ) . '</p>';
                echo '</div>';

                echo '<input type="text" id="import-link-value" name="' . $this->args['opt_name'] . '[import_link]" class="large-text noUpdate" value="" />';

                echo '</div>';

                echo '<p id="redux-import-action"><input type="submit" id="redux-import" name="' . $this->args['opt_name'] . '[import]" class="button-primary" value="' . __( 'Import', $this->args['domain'] ) . '">&nbsp;&nbsp;<span>' . apply_filters( 'redux-import-warning', __( 'WARNING! This will overwrite all existing option values, please proceed with caution!', $this->args['domain'] ) ) . '</span></p>';
                echo '<div class="hr"/><div class="inner"><span>&nbsp;</span></div></div>';

                echo '<h4>' . __( 'Export Options', $this->args['domain'] ) . '</h4>';
                echo '<div class="redux-section-desc">';
                echo '<p class="description">' . apply_filters( 'redux-backup-description', __( 'Here you can copy/download your current option settings. Keep this safe as you can use it as a backup should anything go wrong, or you can use it to restore your settings on this site (or any other site).', $this->args['domain'] ) ) . '</p>';
                echo '</div>';

                /** @noinspection PhpUndefinedConstantInspection */
                echo '<p><a href="javascript:void(0);" id="redux-export-code-copy" class="button-secondary">' . __( 'Copy', $this->args['domain'] ) . '</a> <a href="' . add_query_arg( array( 'feed' => 'reduxopts-' . $this->args['opt_name'], 'action' => 'download_options', 'secret' => md5( AUTH_KEY . SECURE_AUTH_KEY ) ), site_url() ) . '" id="redux-export-code-dl" class="button-primary">' . __( 'Download', $this->args['domain'] ) . '</a> <a href="javascript:void(0);" id="redux-export-link" class="button-secondary">' . __( 'Copy Link', $this->args['domain'] ) . '</a></p>';
                $backup_options = $this->options;
                $backup_options['redux-backup'] = '1';
                echo '<textarea class="large-text noUpdate" id="redux-export-code" rows="8">';
                print_r( json_encode( $backup_options ) );
                echo '</textarea>';
                /** @noinspection PhpUndefinedConstantInspection */
                echo '<input type="text" class="large-text noUpdate" id="redux-export-link-value" value="' . add_query_arg( array( 'feed' => 'reduxopts-' . $this->args['opt_name'], 'secret' => md5( AUTH_KEY.SECURE_AUTH_KEY ) ), site_url() ) . '" />';

                echo '</div>';
            }

            if( $this->args['dev_mode'] === true ) {
                echo '<div id="dev_mode_default_section_group' . '" class="redux-group-tab">';
                echo '<h3>' . __( 'Options Object', $this->args['domain'] ) . '</h3>';
                echo '<div class="redux-section-desc">';

                echo '<div id="redux-object-browser"></div>';

                echo '</div>';

                echo '<div id="redux-object-json" class="hide">'.json_encode($this->options).'</div>';

                echo '<a href="#" id="consolePrintObject" class="button">' . __( 'Show Object in Javascript Console Object', $this->args['domain'] ) . '</a>';
                // END Javascript object debug

                echo '</div>';
            }

            if( $this->args['system_info'] === true ) {
                require_once 'inc/sysinfo.php';
                $system_info = new Simple_System_Info();

                echo '<div id="system_info_default_section_group' . '" class="redux-group-tab">';
                echo '<h3>' . __( 'System Info', $this->args['domain'] ) . '</h3>';

                echo '<div id="redux-system-info">';
                echo $system_info->get( true );
                echo '</div>';

                echo '</div>';
            }

            do_action( 'redux/page-after-sections-' . $this->args['opt_name'], $this ); // REMOVE LATER
            do_action( 'redux/page/'.$this->args['opt_name'].'/sections/after', $this );

            echo '<div class="clear"></div>';
            echo '</div>';
            echo '<div class="clear"></div>';

            echo '<div id="redux-sticky-padder" style="display: none;">&nbsp;</div>';
            echo '<div id="redux-footer-sticky"><div id="redux-footer">';

            if( isset( $this->args['share_icons'] ) ) {
                echo '<div id="redux-share">';

                foreach( $this->args['share_icons'] as $link ) {
                    // SHIM, use URL now
                    if (isset($link['link']) && !empty($link['link'])) {
                        $link['url'] = $link['link'];
                        unset($link['link']);
                    }
                    echo '<a href="' . $link['url'] . '" title="' . $link['title'] . '" target="_blank">';
                    if ( isset( $link['icon'] ) && !empty( $link['icon'] ) ) {
                        echo '<i class="'.$link['icon'].'"></i>';
                    } else {
                        echo '<img src="' . $link['img'] . '"/>';    
                    }
                    echo '</a>';
                }

                echo '</div>';
            }

            echo '<div class="redux-action_bar">';
            submit_button( __( 'Save Changes', $this->args['domain']), 'primary', 'redux_save', false );
            echo '&nbsp;';
            submit_button( __( 'Reset Section', $this->args['domain']), 'secondary', $this->args['opt_name'] . '[defaults-section]', false );
            echo '&nbsp;';
            submit_button( __( 'Reset All', $this->args['domain']), 'secondary', $this->args['opt_name'] . '[defaults]', false );
            echo '</div>';

            echo '<div class="redux-ajax-loading" alt="' . __( 'Working...', $this->args['domain']) . '">&nbsp;</div>';
            echo '<div class="clear"></div>';

            echo '</div>';
            echo '</form>';
            echo '</div></div>';

            echo ( isset( $this->args['footer_text'] ) ) ? '<div id="redux-sub-footer">' . $this->args['footer_text'] . '</div>' : '';

            do_action( 'redux-page-after-form-' . $this->args['opt_name'] ); // REMOVE
            do_action( 'redux/page/'.$this->args['opt_name'].'/form/after', $this );

            echo '<div class="clear"></div>';

            echo '</div><!--wrap-->';

            if ( $this->args['dev_mode'] === true ) {

            	

                if (current_user_can('administrator')){
                    global $wpdb;
                    echo "<br /><pre>";
                    print_r($wpdb->queries);
                    echo "</pre>";
                }

                echo '<br /><div class="redux-timer">' . get_num_queries() . ' queries in ' . timer_stop(0) . ' seconds</div>';

            }

                
        }

        /**
         * Section HTML OUTPUT.
         *
         * @since       1.0.0
         * @access      public
         * @param       array $section
         * @return      void
         */
        public function _section_desc( $section ) {
            $id = trim( rtrim( $section['id'], '_section' ), $this->args['opt_name'] );

            if( isset( $this->sections[$id]['desc'] ) && !empty( $this->sections[$id]['desc'] ) ) {
            	echo '<div class="redux-section-desc">' . $this->sections[$id]['desc'] . '</div>';
            }
        }

		/**
		 * Field HTML OUTPUT.
		 * Gets option from options array, then calls the specific field type class - allows extending by other devs
		 * @since       1.0.0
		 * @param array $field
		 * @param string $v
		 * @return      void
		 */
        public function _field_input( $field, $v = "" ) {

            if( isset( $field['callback'] ) && function_exists( $field['callback'] ) ) {
                $value = ( isset( $this->options[$field['id']] ) ) ? $this->options[$field['id']] : '';
                do_action( 'redux-before-field-' . $this->args['opt_name'], $field, $value ); // REMOVE
                do_action( 'redux/field/'.$this->args['opt_name'].'/'.$field['type'].'/callback/before', $field, $value );
                do_action( 'redux/field/'.$this->args['opt_name'].'/callback/before', $field, $value );
                call_user_func( $field['callback'], $field, $value );
                do_action( 'redux-after-field-' . $this->args['opt_name'], $field, $value ); // REMOVE
                do_action( 'redux/field/'.$this->args['opt_name'].'/'.$field['type'].'/callback/after', $field, $value );
                do_action( 'redux/field/'.$this->args['opt_name'].'/callback/after', $field, $value );
                return;
            }

            if( isset( $field['type'] ) ) {
                $field_class = 'ReduxFramework_' . $field['type'];

                if( !class_exists( $field_class ) ) {
//                    $class_file = apply_filters( 'redux/field/class/'.$field['type'], self::$_dir . 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.php', $field ); // REMOVE
                    $class_file = apply_filters( 'redux/'.$this->args['opt_name'].'/field/class/'.$field['type'], self::$_dir . 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.php', $field );
                    if( $class_file ) {
                        /** @noinspection PhpIncludeInspection */
                        require_once($class_file);
                    }

                }

                if( class_exists( $field_class ) ) {
                    $value = isset($this->options[$field['id']])?$this->options[$field['id']]:'';
                    if ($v != "") {
                    	$value = $v;
                    }
                    do_action( 'redux-before-field-' . $this->args['opt_name'], $field, $value ); // REMOVE
                    do_action( 'redux/field/'.$this->args['opt_name'].'/'.$field['type'].'/render/before', $field, $value );
                    do_action( 'redux/field/'.$this->args['opt_name'].'/render/before', $field, $value );

                    $render = new $field_class( $field, $value, $this );
                    ob_start();
					/** @noinspection PhpUndefinedMethodInspection */
					$render->render();
                    $_render = apply_filters( 'redux-field-'.$this->args['opt_name'], ob_get_contents(), $field ); // REMOVE
                    $_render = apply_filters( 'redux/field/'.$this->args['opt_name'].'/'.$field['type'].'/render/after', $_render, $field );
                    $_render = apply_filters( 'redux/field/'.$this->args['opt_name'].'/render/after', $_render, $field );
                    ob_end_clean();

                    //save the values into a unique array in case we need it for dependencies
                    $this->fieldsValues[$field['id']] = (isset($value['url']) && is_array($value) )?$value['url']:$value;
                    //create default data und class string and checks the dependencies of an object
					$class_string = '';
					$data_string = '';
                    extract($this->check_dependencies($field));

                    do_action( 'redux/field/'.$this->args['opt_name'].'/'.$field['type'].'/fieldset/before/' . $this->args['opt_name'], $field, $value );
                    do_action( 'redux/field/'.$this->args['opt_name'].'/fieldset/before/' . $this->args['opt_name'], $field, $value );
					echo '<fieldset id="'.$this->args['opt_name'].'-'.$field['id'].'" class="redux-field redux-container-'.$field['type'].' '.$class_string.'" data-id="'.$field['id'].'" '.$data_string.'>';
	                    echo $_render;

	                    if (!empty($field['desc'])) {
	                    	$field['description'] = $field['desc'];
	                    }
                    
                    echo ( isset( $field['description'] ) && $field['type'] != "info" && $field['type'] != "group" && !empty( $field['description'] ) ) ? '<div class="description field-desc">' . $field['description'] . '</div>' : '';

                    echo '</fieldset>';

                    do_action( 'redux-after-field-' . $this->args['opt_name'], $field, $value ); // REMOVE
                    do_action( 'redux/field/'.$this->args['opt_name'].'/'.$field['type'].'/fieldset/after/' . $this->args['opt_name'], $field, $value );
                    do_action( 'redux/field/'.$this->args['opt_name'].'/fieldset/after/' . $this->args['opt_name'], $field, $value );
                }
            }
        } // function

        /**
         * Checks dependencies between objects based on the $field['required'] array
         *
         * If the array is set it needs to have exactly 3 entries.
         * The first entry describes which field should be monitored by the current field. eg: "content"
         * The second entry describes the comparison parameter. eg: "equals, not, is_larger, is_smaller ,contains"
         * The third entry describes the value that we are comparing against.
         *
         * Example: if the required array is set to array('content','equals','Hello World'); then the current
         * field will only be displayed if the field with id "content" has exactly the value "Hello World"
         * 
         * @param array $field
         * @return array $params
         */
        public function check_dependencies($field) {

            $params = array('data_string' => "", 'class_string' => "");

            if (!empty($field['required'])) {
                $data['check-field'] = $field['required'][0];
                $data['check-comparison'] = $field['required'][1];
                $data['check-value'] = $field['required'][2];
                $params['data_string'] = $this->create_data_string($data);
                $return = false;
                //required field must not be hidden. otherwise hide this one by default
                
                if ( !in_array( $data['check-field'], $this->fieldsHidden ) ) {
                    if (isset($this->fieldsValues[$data['check-field']])) {
                        //$value1 = isset($this->fieldsValues[$data['check-field']]['url'])?isset($this->fieldsValues[$data['check-field']]['url']):$this->fieldsValues[$data['check-field']];
                        $value1 = $this->fieldsValues[$data['check-field']];
                        $value2 = $data['check-value'];
                        switch ($data['check-comparison']) {
                            case '=': 
                            case 'equals': 
                                if(is_array($value2)){
                                    if(in_array($value1, $value2))
                                       $return = true;  
                                }else{
                                    if ($value1 == $value2)
                                        $return = true; 
                                }
                                break;
                            case '!=':    
                            case 'not':
                                if(is_array($value2)){
                                    if(!in_array($value1, $value2))
                                       $return = true;  
                                }else{ 
                                    if ($value1 != $value2)
                                        $return = true; 
                                }
                                break;
                            case '>':    
                            case 'greater':    
                            case 'is_larger': 
                                if ($value1 > $value2)
                                    $return = true; 
                                break;
                            case '>=':    
                            case 'greater_equal':    
                            case 'is_larger_equal': 
                                if ($value1 >= $value2)
                                    $return = true; 
                                break;                                
                            case '<':
                            case 'less':    
                            case 'is_smaller': 
                                if ($value1 < $value2)
                                    $return = true; 
                                break;
                            case '<=':
                            case 'less_equal':    
                            case 'is_smaller_equal': 
                                if ($value1 <= $value2)
                                    $return = true; 
                                break;                                
                            case 'contains': 
                                if (strpos($value1, $value2) !== false)
                                    $return = true; 
                                break;
                            case 'doesnt_contain': 
                            case 'not_contain': 
                                if (strpos($value1, $value2) === false)
                                    $return = true; 
                                break;
                            case 'is_empty_or': 
                                if (empty($value1) || $value1 == $value2)
                                    $return = true; 
                                break;
                            case 'not_empty_and': 
                                if (!empty($value1) && $value1 != $value2)
                                    $return = true; 
                                break;
                        }
                    }
                }

                if (!$return) {
                    $params['class_string'] = ' hiddenFold ';
                    $this->fieldsHidden[] = $field['id'];
                }else{
                    $params['class_string'] = ' showFold ';
                }
            }
            return $params;
        }

        /**
         * converts an array into a html data string
         *
         * @param array $data example input: array('id'=>'true')
         * @return string $data_string example output: data-id='true'
         */
        public function create_data_string($data = array()){
            $data_string = "";
            
            foreach($data as $key=>$value){
                if(is_array($value)) $value = implode("|",$value);
                $data_string .= " data-$key='$value' ";
            }
        
            return $data_string;
        } 
    } // class

    do_action( 'redux/init', ReduxFramework::init() );

} // if
