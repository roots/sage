<?php

if ( !function_exists( 'shoestrap_module_advanced_options' ) ) :
/*
 * The advanced core options for the Shoestrap theme
 */
function shoestrap_module_advanced_options( $sections ) {
  // Advanced Settings
  $section = array( 
    'title'   => __( 'Advanced', 'shoestrap' ),
    'icon'    => 'el-icon-cogs icon-large'
  );  

  // $fields[] = array( 
  //   'title'     => __( 'Enable Advanced mode', 'shoestrap' ),
  //   'desc'      => __( 'By enabling you have a more in-depth control of Shoestrap\'s modules. Default: Off', 'shoestrap' ),
  //   'id'        => 'advanced_toggle',
  //   'default'   => 0,
  //   'type'      => 'switch',
  //   'customizer'=> array(),
  // );

  $fields[] = array( 
    'title'     => __( 'Disable Comments on Blog', 'shoestrap' ),
    'desc'      => __( 'Do not allow site visitors to write comments on blog posts. Default: Off.', 'shoestrap' ),
    'id'        => 'blog_comments_toggle',
    'default'   => 0,
    'type'      => 'switch',
    'customizer'=> array(),
  );

  $fields[] = array( 
    'title'     => __( 'Post excerpt length', 'shoestrap' ),
    'desc'      => __( 'Choose how many words should be used for post excerpt. Default: 40', 'shoestrap' ),
    'id'        => 'post_excerpt_length',
    'default'   => 40,
    'min'       => 10,
    'step'      => 1,
    'max'       => 1000,
    'edit'      => 1,
    'type'      => 'slider'
  );

  $fields[] = array( 
    'title'     => __( 'Enable Retina mode', 'shoestrap' ),
    'desc'      => __( 'By enabling your site will be retina ready. Requires a all images to be uploaded at 2x the typical size desired, including logos. Default: On', 'shoestrap' ),
    'id'        => 'retina_toggle',
    'default'   => 1,
    'type'      => 'switch',
    'customizer'=> array(),
    // 'required' => array('advanced_toggle','=',array('1')),  
  );

  $fields[] = array( 
    'title'     => __( 'Dev mode', 'shoestrap' ),
    'desc'      => __( 'By enabling your admin panel will have a Dev Mode Info with an output of the options object for addition debugging. Default: Off', 'shoestrap' ),
    'id'        => 'dev_mode',
    'default'   => 0,
    'type'      => 'switch',
    'customizer'=> array(),
    // 'required'  => array('advanced_toggle','=',array('1')),
  );    

  $fields[] = array( 
    'title'     => __( 'Allow shortcodes in widgets', 'shoestrap' ),
    'desc'      => __( 'This option allows shortcodes within widgets. Default: On.', 'shoestrap' ),
    'id'        => 'enable_widget_shortcodes',
    'compiler'      => true,
    'default'   => 1,
    'type'      => 'switch',
  );

  $fields[] = array( 
    'title'     => __( 'Google Analytics ID', 'shoestrap' ),
    'desc'      => __( 'Paste your Google Analytics ID here to enable analytics tracking. Only Universal Analytics properties. Your user ID should be in the form of UA-XXXXX-Y.', 'shoestrap' ),
    'id'        => 'analytics_id',
    'default'   => '',
    'type'      => 'text',
  );

  $fields[] = array( 
    'title'     => 'Border-Radius and Padding Base',
    'id'        => 'help2',
    'desc'      => __( 'The following settings affect various areas of your site, most notably buttons.', 'shoestrap' ),
    'type'      => 'info',
    // 'required'  => array('advanced_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'     => __( 'Border-Radius', 'shoestrap' ),
    'desc'      => __( 'You can adjust the corner-radius of all elements in your site here. This will affect buttons, navbars, widgets and many more. Default: 4', 'shoestrap' ),
    'id'        => 'general_border_radius',
    'default'   => 4,
    'min'       => 0,
    'step'      => 1,
    'max'       => 50,
    'advanced'  => true,
    'compiler'  => true,
    'type'      => 'slider',
    // 'required'  => array('advanced_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'     => __( 'Padding Base', 'shoestrap' ),
    'desc'      => __( 'You can adjust the padding base. This affects buttons size and lots of other cool stuff too! Default: 8', 'shoestrap' ),
    'id'        => 'padding_base',
    'default'   => 8,
    'min'       => 0,
    'step'      => 1,
    'max'       => 20,
    'advanced'  => true,
    'compiler'  => true,
    'type'      => 'slider',
    // 'required'  => array('advanced_toggle','=',array('1')),
  );

  $url = admin_url( 'widgets.php' );
  $fields[] = array( 
    'title'     => __( 'CAUTION', 'shoestrap' ),
    'id'        => 'help10',
    'style'     => 'warning',
    'desc'      => __('The settings bellow can pottentially harm your site if you do not properly comprehend them and what they do.
                    If unsure, simply let them be.', 'shoestrap' ),
    'icon'      => 'warning-sign',
    'type'      => 'info'
  );

  $url = admin_url( 'options-permalink.php' );
  $fields[] = array( 
    'title'     => __( 'URL Rewrites', 'shoestrap' ),
    'desc'      => __( 'Rewrites URLs, masking partially the fact that you\'re using WordPress. Please note that after you enable or disable this option, you should visit the <a href=' . $url . '>permalinks menu</a> and press <strong>save</strong>. This option requires that your .htaccess file is writable by your webserver. Default: OFF', 'shoestrap' ),
    'id'        => 'rewrites',
    'default'   => 0,
    'type'      => 'switch',
    // 'required'  => array('advanced_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'     => __( 'Change uploads folder', 'shoestrap' ),
    'desc'      => __( 'Move your uploads folder in <strong> /media </strong>. NOTICE: By toggling this option, any files stored in default folder won\'t be accessible, and vice versa. Default: OFF', 'shoestrap' ),
    'id'        => 'upload_folder',
    'default'   => 0,
    'type'      => 'switch',
    // 'required'  => array('advanced_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'     => __( 'PJAX', 'shoestrap' ),
    'desc'      => __( 'Use <a href="https://github.com/defunkt/jquery-pjax" target="_blank">PJAX</a> in link tags inside NavBars, Sibebars & Breadcrumb. This cause a fast linear fadeToggle effect in main page. Default: OFF', 'shoestrap' ),
    'id'        => 'pjax',
    'default'   => 0,
    'type'      => 'switch',
    // 'required'  => array('advanced_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'     => __( 'Root Relative URLs', 'shoestrap' ),
    'desc'      => __( 'Return URLs such as <em>/assets/css/style.css</em> instead of <em>http://example.com/assets/css/style.css</em>. Default: ON', 'shoestrap' ),
    'id'        => 'root_relative_urls',
    'default'   => 0,
    'type'      => 'switch'
  );

  $fields[] = array( 
    'title'     => __( 'Enable Nice Search', 'shoestrap' ),
    'desc'      => __( 'Redirects /?s=query to /search/query/, convert %20 to +. Default: ON', 'shoestrap' ),
    'id'        => 'nice_search',
    'default'   => 1,
    'type'      => 'switch'
  );

  $fields[] = array( 
    'title'     => __( 'Custom CSS', 'shoestrap' ),
    'desc'      => __( 'You can write your custom CSS here. This code will appear in a script tag appended in the header section of the page.', 'shoestrap' ),
    'id'        => 'user_css',
    'default'   => '',
    'type'      => 'textarea',
    // 'required'  => array('advanced_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'     => __( 'Custom LESS', 'shoestrap' ),
    'desc'      => __( 'You can write your custom LESS here. This code will be compiled with the other LESS files of the theme and be appended to the header.', 'shoestrap' ),
    'id'        => 'user_less',
    'default'   => '',
    'type'      => 'textarea',
    'compiler'  => true,
    // 'required'  => array('advanced_toggle','=',array('1')),
  );  

  $fields[] = array( 
    'title'     => __( 'Custom JS', 'shoestrap' ),
    'desc'      => __( 'You can write your custom JavaScript/jQuery here. The code will be included in a script tag appended to the bottom of the page.', 'shoestrap' ),
    'id'        => 'user_js',
    'default'   => '',
    'type'      => 'textarea',
    // 'required'  => array('advanced_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'     => __( 'Toggle adminbar On/Off', 'shoestrap' ),
    'desc'      => __( 'Turn the admin bar On or Off on the frontend. Default: On.', 'shoestrap' ),
    'id'        => 'advanced_wordpress_disable_admin_bar_toggle',
    'default'   => 1,
    'customizer'=> array(),
    'type'      => 'switch',
    // 'required'  => array('advanced_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'     => __( 'Minimize CSS', 'shoestrap' ),
    'desc'      => __( 'Minimize the genearated CSS. This should be ON for production sites. Default: OFF.', 'shoestrap' ),
    'id'        => 'minimize_css',
    'default'   => 0,
    'compiler'  => true,
    'customizer'=> array(),
    'type'      => 'switch',
    // 'required'  => array('advanced_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'     => __( 'Debug Hooks', 'shoestrap' ),
    'desc'      => __( 'Turn on very useful debug hooks. These will only be visible to admins. Default: Off.', 'shoestrap' ),
    'id'        => 'debug_hooks',
    'default'   => 0,
    'customizer'=> array(),
    'type'      => 'switch',
    // 'required'  => array('advanced_toggle','=',array('1')),
  );

  $section['fields'] = $fields;

  $section = apply_filters( 'shoestrap_module_advanced_options_modifier', $section );
  
  $sections[] = $section;
  
  return $sections;

}
endif;
add_filter( 'redux-sections-'.REDUX_OPT_NAME, 'shoestrap_module_advanced_options', 95 );

include_once( dirname( __FILE__ ).'/functions.advanced.php' );
include_once( dirname( __FILE__ ).'/debug-hooks.php' );

if ( !function_exists( 'shoestrap_debug_hooks' ) ) :
function shoestrap_debug_hooks() {
  global $redux;
  if ( current_user_can( 'administrator' ) && shoestrap_getVariable( 'debug_hooks' ) == 1 ) : ?>
    <div class='panel widget-inner clearfix'>
      <div class='panel-heading'>Debug Information</div>
      <ul class='nav nav-tabs' id='debugTabs'>
        <li class='active'><a href='#SMOFData'>SMOF Data</a></li>
        <li><a href='#hooksdebug'>Wordpress Hooks</a></li>
      </ul>
      <div class='tab-content'>
        <div class='tab-pane active' id='SMOFData'>
          <?php
            $redux_r = print_r( $redux, true );
            $redux_r_sans = htmlspecialchars( $redux_r, ENT_QUOTES );
            echo '<pre>'. $redux_r_sans .'<pre>';
          ?>
        </div>
        <div class='tab-pane' id='hooksdebug'><?php echo list_hooks(); ?></div>
      </div>
    </div>
    <script>
      /** Fire up jQuery - let's dance! */
      jQuery( document ).ready( function( $ ){
        $( '#debugTabs a' ).click( function ( e ) {
          e.preventDefault();
          $( this ).tab( 'show' );
        })
      })
    </script>
    <?php
  endif;
}
endif;
add_action( 'shoestrap_after_content', 'shoestrap_debug_hooks' );
