<?php

function shoestrap_add_typography_class_case($array) {
  global $smof_output, $smof_details, $wp_filesystem;
	// Initialize the Wordpress filesystem, no more using file_put_contents function
	if (empty($wp_filesystem)) {
		require_once(ABSPATH .'/wp-admin/includes/file.php');
		WP_Filesystem();
	}  

  if ($array['value']['type'] =="select_google_font_hybrid") {
    $output = '';
    unset($array['output'],$array['smof_output']);
    //print_r($array);
    extract($array);
    $typography_stored = isset($smof_data[$value['id']]) ? $smof_data[$value['id']] : $value['std'];
    $gfonts = json_decode($wp_filesystem->get_contents(dirname(__FILE__).'/webfonts.json'), true);
    /* Font Size */

    if(isset($typography_stored['size'])) {
      $output .= '<div class="select_wrapper typography-size temphide" original-title="Font size">';
      $output .= '<select class="of-typography of-typography-size select google_font_hybrid_value" name="'.$value['id'].'[size]" id="'. $value['id'].'_size">';
        for ($i = 9; $i < 80; $i++){
          $test = $i.'px';
          $output .= '<option value="'. $i .'px" ' . selected($typography_stored['size'], $test, false) . '>'. $i .'px</option>';
          }

      $output .= '</select></div>';
    }

      $output .= '<div class="select_wrapper typography-style" original-title="Font style">';
      $output .= '<select class="of-typography of-typography-style select google_font_hybrid_value" name="'.$value['id'].'[style]" id="'. $value['id'].'_style">';
      $styles = array('100'=>'Ultra-Light 100',
                '200'=>'Light 200',
                '300'=>'Book 300',
                '400'=>'Normal 400',
                '500'=>'Medium 500',
                '600'=>'Semi-Bold 600',
                '700'=>'Bold 700',
                '800'=>'Extra-Bold 800',
                '900'=>'Ultra-Bold 900',
                '100-italic'=>'Ultra-Light 100 Italic',
                '200-italic'=>'Light 200 Italic',
                '300-italic'=>'Book 300 Italic',
                '400-italic'=>'Normal 400 Italic',
                '500-italic'=>'Medium 500 Italic',
                '600-italic'=>'Semi-Bold 600 Italic',
                '700-italic'=>'Bold 700 Italic',
                '800-italic'=>'Extra-Bold 800 Italic',
                '900-italic'=>'Ultra-Bold 900 Italic',
              );
      if (isset($gfonts[$typography_stored['face']])) {
        $styles = array();
        foreach ($gfonts[$typography_stored['face']]['variants'] as $k=>$v) {
          $output .= '<option value="'. $v['id'] .'" ' . selected($typography_stored['style'], $v['id'], false) . '>'. $v['name'] .'</option>';
        }
      } else {
        foreach ($styles as $i=>$style){
          if (!isset($typography_stored['style']))
            $typography_stored['style'] = false;
          $output .= '<option value="'. $i .'" ' . selected($typography_stored['style'], $i, false) . '>'. $style .'</option>';
        }
      }

      $output .= '</select></div>';

      /* Font Script */
      $output .= '<div class="select_wrapper typography-script tooltip" original-title="Font Script">';
      $output .= '<select class="of-typography of-typography-script select google_font_hybrid_value" name="'.$value['id'].'[script]" id="'. $value['id'].'_style">';
      if (isset($gfonts[$typography_stored['face']])) {
        $styles = array();
        foreach ($gfonts[$typography_stored['face']]['subsets'] as $k=>$v) {
          $output .= '<option value="'. $v['id'] .'" ' . selected($typography_stored['style'], $v['id'], false) . '>'. $v['name'] .'</option>';
        }
      }

      $output .= '</select></div>';


    //}


      $output .= '<div class="select_wrapper typography-face" original-title="Font family" style="width: 220px; margin-right: 5px;">';
      $output .= '<select class="of-typography of-typography-new-face select google_font_hybrid_value" name="'.$value['id'].'[face]" id="'. $value['id'].'_face">';

      $faces = array(
        "Arial, Helvetica, sans-serif" => "Arial, Helvetica, sans-serif",
        "'Arial Black', Gadget, sans-serif" => "'Arial Black', Gadget, sans-serif",
        "'Bookman Old Style', serif" => "'Bookman Old Style', serif",
        "'Comic Sans MS', cursive" => "'Comic Sans MS', cursive",
        "Courier, monospace" => "Courier, monospace",
        "Garamond, serif" => "Garamond, serif",
        "Georgia, serif" => "Georgia, serif",
        "Impact, Charcoal, sans-serif" => "Impact, Charcoal, sans-serif",
        "'Lucida Console', Monaco, monospace" => "'Lucida Console', Monaco, monospace",
        "'Lucida Sans Unicode', 'Lucida Grande', sans-serif" => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
        "'MS Sans Serif', Geneva, sans-serif" => "'MS Sans Serif', Geneva, sans-serif",
        "'MS Serif', 'New York', sans-serif" =>"'MS Serif', 'New York', sans-serif",
        "'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "'Palatino Linotype', 'Book Antiqua', Palatino, serif",
        "Tahoma, Geneva, sans-serif" =>"Tahoma, Geneva, sans-serif",
        "'Times New Roman', Times, serif" => "'Times New Roman', Times, serif",
        "'Trebuchet MS', Helvetica, sans-serif" => "'Trebuchet MS', Helvetica, sans-serif",
        "Verdana, Geneva, sans-serif" => "Verdana, Geneva, sans-serif",
      );

      foreach ($faces as $i=>$face) {
        $output .= '<option data-google="false" data-details="'.urlencode(json_encode(
          array('100'=>'Ultra-Light 100',
                '200'=>'Light 200',
                '300'=>'Book 300',
                '400'=>'Normal 400',
                '500'=>'Medium 500',
                '600'=>'Semi-Bold 600',
                '700'=>'Bold 700',
                '800'=>'Extra-Bold 800',
                '900'=>'Ultra-Bold 900',
                '100-italic'=>'Ultra-Light 100 Italic',
                '200-italic'=>'Light 200 Italic',
                '300-italic'=>'Book 300 Italic',
                '400-italic'=>'Normal 400 Italic',
                '500-italic'=>'Medium 500 Italic',
                '600-italic'=>'Semi-Bold 600 Italic',
                '700-italic'=>'Bold 700 Italic',
                '800-italic'=>'Extra-Bold 800 Italic',
                '900-italic'=>'Ultra-Bold 900 Italic',
              )
          )).'" value="'. $i .'" ' . selected($typography_stored['face'], $i, false) . '>'. $face .'</option>';
      }
      $output .= '<option value="" style="text-align: center;" />-------- GOOGLE WEB FONTS --------</option>';

      $google = "false";
      if ( isset( $gfonts ) ) {
        foreach ($gfonts as $i => $face) {
          if ( $i == $typography_stored['face'] )
            $google = "true";
          
          $output .= '<option data-details="'.urlencode(json_encode($face)).'" data-google="true" value="'.$i.'" ' . selected($typography_stored['face'], $i, false) . '>'. $i .'</option>';
        }
      }

      $output .= '</select></div>';

      $output .= '<input type="hidden" class="typography-google" name="'.$value['id'].'[google]" value="'.$google.'" />';

    /* Font Color */
    if(isset($typography_stored['color'])) {
      $output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector typography-color" style="float: right;"><div style="background-color: '.$typography_stored['color'].'"></div></div>';
      $output .= '<input data-default-color="'.$value['std']['color'].'" class="of-color of-typography of-typography-color google_font_hybrid_value" original-title="Font color" name="'.$value['id'].'[color]" id="'. $value['id'] .'_color" type="text" value="'. $typography_stored['color'] .'" />';
    }


    if(isset($value['preview']['text'])){
      $g_text = $value['preview']['text'];
    } else {
      $g_text = '0123456789 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz';
    }
    if(isset($value['preview']['size'])) {
      $g_size = 'style="font-size: '. $value['preview']['size'] .';"';
    } else {
      $g_size = '';
    }

    $output .= '<p class="'.$value['id'].'_ggf_previewer google_font_preview" '. $g_size .'>'. $g_text .'</p>';
    $smof_output = $output;
  }

}
add_action( 'optionsframework_machine_loop', 'shoestrap_add_typography_class_case' );




function shoestrap_typography_css() {
  echo '<style type="text/css" id="core.typography">';
  echo '#of_container .section-select_google_font_hybrid .typography-script{width:130px !important;margin-right: 0;}';
  echo '#of_container .section-select_google_font_hybrid .typography-style {width:125px !important;}';
  echo '#of_container p.google_font_preview {width: 570px !important;}';
  echo '</style>';
}
add_action( 'of_style_only_after', 'shoestrap_typography_css' );


function shoestrap_module_typography_js()
{
  wp_register_script('core_typography', get_template_directory_uri() . '/lib/modules/core.typography/font.js', false, null, true);
  wp_enqueue_script('core_typography');
}
add_action( 'of_load_only_after', 'shoestrap_module_typography_js' );


function shoestrap_module_typography_googlefont_links() {
  $font_base            = shoestrap_getVariable( 'font_base' );
  $font_navbar          = shoestrap_getVariable( 'font_navbar' );
  $font_brand           = shoestrap_getVariable( 'font_brand' );
  $font_jumbotron       = shoestrap_getVariable( 'font_jumbotron' );
  $font_heading         = shoestrap_getVariable( 'font_heading' );
  if (shoestrap_getVariable( 'font_heading_custom' )) {
    $font_h1 = shoestrap_getVariable( 'font_h1' );
    $font_h2 = shoestrap_getVariable( 'font_h2' );
    $font_h3 = shoestrap_getVariable( 'font_h3' );
    $font_h4 = shoestrap_getVariable( 'font_h4' );
    $font_h5 = shoestrap_getVariable( 'font_h5' );
    $font_h6 = shoestrap_getVariable( 'font_h6' );
  }
  if (shoestrap_getVariable( 'font_jumbotron_heading_custom' ) == 1) {
    $font_jumbotron_headers = shoestrap_getVariable( 'font_jumbotron_headers' );
  }
  if ($font_base['google'] === 'true' ) {
    echo getGoogleScript($font_base);
  }
  if ($font_navbar['google'] === 'true' ) {
    echo getGoogleScript($font_navbar);
  }
  if ($font_brand['google'] === 'true' ) {
    echo getGoogleScript($font_brand);
  }
  if ($font_jumbotron['google'] === 'true' ) {
    echo getGoogleScript($font_jumbotron);
  }
  if (shoestrap_getVariable( 'font_heading_custom' )) {
    if ($font_h1['google'] === 'true' ) {
      echo getGoogleScript($font_h1);
    }
    if ($font_h2['google'] === 'true' ) {
      echo getGoogleScript($font_h2);
    }
    if ($font_h3['google'] === 'true' ) {
      echo getGoogleScript($font_h3);
    }
    if ($font_h4['google'] === 'true' ) {
      echo getGoogleScript($font_h4);
    }
    if ($font_h5['google'] === 'true' ) {
      echo getGoogleScript($font_h5);
    }
    if ($font_h6['google'] === 'true' ) {
      echo getGoogleScript($font_h6);
    }
  } else if (isset($font_heading['google']) && $font_heading['google'] === 'true' ) {
    echo getGoogleScript($font_heading);
  }
  if (shoestrap_getVariable( 'font_jumbotron_heading_custom' ) == 1) {
    if ($font_jumbotron_headers['google'] === 'true' ) {
      echo getGoogleScript($font_jumbotron_headers);
    }
  }
}
add_action( 'wp_head', 'shoestrap_module_typography_googlefont_links' );


function getGoogleScript($font) {
  $link = 'http://fonts.googleapis.com/css?family='.str_replace(" ","+",$font['family']);
  if (!empty($font['style']))
    $link .= ':'.str_replace('-','',$font['style']);
  if (!empty($font['script']))
    $link .= '&subset='.$font['script'];

  return '<link href="'.$link.'" rel="stylesheet" type="text/css" class="base_font">';
}
