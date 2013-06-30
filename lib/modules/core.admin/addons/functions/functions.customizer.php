<?php


/*
 *    Function to save the current SMOF values for comparison. 
 *    Needed to hijack the customizer's save options
 */
function shoestrap_customize_save() {
  set_theme_mod( 'shoestrap_customizer_preSave', get_theme_mods() );
}
add_action('customize_save', 'shoestrap_customize_save');


/*
 *    Function to compare LESS values to see if we need to rebuild the CSS
 *    Code added to Wordpress 3.6. Allows us to compare the previously saved
 *    settings so we're not rebuilding the css every save.
 */
function shoestrap_generateCSS() {
  global $smof_details;
  $old = get_theme_mod( 'shoestrap_customizer_preSave' );
  remove_theme_mod( 'shoestrap_customizer_preSave' ); // Cleanup
  $new = get_theme_mods();

  foreach ( $smof_details as $key=>$option ) {
    if ( $option['less'] == true ) {
      if ( $old[$option['id']] != $new[$option['id']] ) {
        shoestrap_makecss();
        break;
      }
    }
  }
}
add_action('customize_save_after', 'shoestrap_generateCSS');

/*
 *    Adds everthing needed to the customizer pane in the customizer
 *    IE, this is how we interact with the customizer.
 */
function smof_customize_init( $wp_customize ) {
  // Get Javascript
  of_load_only();
  // Have to change the javascript for the customizer
  wp_dequeue_script( 'smof', ADMIN_DIR .'assets/js/smof.js' );

  of_style_only();

  wp_enqueue_style( 'wp-pointer' );
  wp_enqueue_script( 'wp-pointer' );
  // Remove when code is in place!
  wp_enqueue_script('smofcustomizerjs', get_template_directory_uri() . SMOF_DIR . 'addons/assets/js/customizer.js');
  // Get styles

  wp_enqueue_style('smofcustomizer', get_template_directory_uri() . SMOF_DIR .'addons/assets/css/customizer.css');
}
add_action( 'customize_controls_init', 'smof_customize_init' );

/*
 *    Adds everthing needed to the preview pane in the customizer
 */
function smof_preview_init( $wp_customize ) {
  global $smof_data, $smof_details;
  wp_dequeue_style( 'shoestrap_css' );
  wp_deregister_style( 'shoestrap_css' );

  $less = shoestrap_compile_css('less');
  print '<link rel="stylesheet/less" type="text/less" href="'.str_replace( ".css", ".less", shoestrap_css( 'url' ) ).'">';
  print '<script type="text/javascript">
    less = {
      env: "development", // or "production"
      async: false,       // load imports async
      fileAsync: false,   // load imports async when in a page under a file protocol
      poll: 1000,         // when in watch mode, time in ms between polls
      functions: {},      // user functions, keyed by name
      dumpLineNumbers: "comments", // or "mediaQuery" or "all"
      relativeUrls: false,// whether to adjust urls to be relative if false, urls are already relative to the entry less file
      rootpath: "'.get_template_directory_uri().'/assets/less/"
    };
  </script>';
  wp_enqueue_script( 'less-js', get_template_directory_uri() . SMOF_DIR . 'addons/assets/js/less-1.3.3.min.js' );
  wp_enqueue_script( 'preview-js', get_template_directory_uri() . SMOF_DIR . 'addons/assets/js/preview.js' );
  wp_localize_script( 'preview-js', 'smofPost', array(
    'data'      => $smof_data,
    'variables' => $smof_details
  ));
}
add_action( 'customize_preview_init', 'smof_preview_init' );

/*
 *    Adds our Customizer LESS styles
 */
function enqueue_less_styles( $tag, $handle ) {
  global $wp_styles;
  $match_pattern = '/\.less$/U';
  if ( preg_match( $match_pattern, $wp_styles->registered[$handle]->src ) ) {
    $handle = $wp_styles->registered[$handle]->handle;
    $media = $wp_styles->registered[$handle]->args;
    $href = $wp_styles->registered[$handle]->src . '?ver=' . $wp_styles->registered[$handle]->ver;
    $rel = isset($wp_styles->registered[$handle]->extra['alt']) && $wp_styles->registered[$handle]->extra['alt'] ? 'alternate stylesheet' : 'stylesheet';
    $title = isset($wp_styles->registered[$handle]->extra['title']) ? "title='" . esc_attr( $wp_styles->registered[$handle]->extra['title'] ) . "'" : '';
    $tag = "<link rel='stylesheet/less' id='$handle' $title href='$href' type='text/less' media='$media' />";
  }
  return $tag;
}

/*
 *    Option to change the way posts are made. May utilize in the future
 *    to allow "live" updating.
 */
function postMessageHandlersJS() {
  global $smof_data, $smof_details;
  $script = "";
  foreach ( $smof_details as $option ) {
    if ( $option['less'] == true ) {
      $script .="
      wp.customize( option , function( value ) {
        value.bind( function( to ) {
          console.log('Setting customize bind: '+option);
          var variable = '@'+option;
          console.log(option);
        });
      });";
    }
  }
}