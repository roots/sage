<?php

// /*
//  * Creates the section, settings and the controls for the customizer
//  */
// function shoestrap_typography_customizer( $wp_customize ) {

//   // Dropdown (Select) Controls
//   $select_controls = array();

//   // Text Controls
//   $text_controls = array();

//   foreach ( $select_controls as $control ) {
//     $wp_customize->add_control( $control['setting'], array(
//       'label'       => $control['label'],
//       'section'     => $control['section'],
//       'settings'    => $control['setting'],
//       'type'        => 'select',
//       'priority'    => $control['priority'],
//       'choices'     => $control['choises']
//     ));
//   }

//   foreach ( $text_controls as $control) {
//     $wp_customize->add_control( $control['setting'], array(
//       'label'       => $control['label'],
//       'section'     => $control['section'],
//       'settings'    => $control['setting'],
//       'type'        => 'text',
//       'priority'    => $control['priority']
//     ));
//   }

//   // Content of the Google Font
//   // $wp_customize->add_control( new Shoestrap_Google_WebFont_Control( $wp_customize, 'typography_google_webfont', array(
//   //   'label'       => 'Google Webfont',
//   //   'section'     => 'shoestrap_typography',
//   //   'settings'    => 'typography_google_webfont',
//   //   'priority'    => 3,
//   // )));

//   //if ( $wp_customize->is_preview() && ! is_admin() )
//     //add_action( 'wp_footer', 'shoestrap_customizer_typography_preview', 21 );
// }
// add_action( 'customize_register', 'shoestrap_typography_customizer' );



// /**
//  * Used by shoestrap_typography_customizer
//  *
//  * Adds extra javascript actions to the theme customizer editor
//  */
// function shoestrap_customizer_typography_controls()
// {
//   wp_register_script('theme_customizer', get_template_directory_uri() . '/lib/modules/core.typography/scripts-customizer.js', false, null, true);
//   wp_enqueue_script('theme_customizer');
// }
// //add_action( 'customize_controls_init', 'shoestrap_customizer_typography_controls' );


// /**
//  * Used by shoestrap_typography_customizer
//  *
//  * Bind JS handlers to make Theme Customizer preview reload changes asynchronously.
//  */
// function shoestrap_customizer_typography_preview()
// {
//   wp_register_script('theme_customizer', get_template_directory_uri() . '/lib/modules/core.typography/scripts-preview.js', false, null, true);
//   wp_enqueue_script('theme_customizer');
// }
// //add_action( 'customize_preview_init', 'shoestrap_customizer_typography_preview' );














function shoestrap_add_typography_class_case($array) {
  global $smof_output, $smof_details;
    
  if ($array['value']['type'] =="select_google_font_hybrid") {
    $output = '';
    unset($array['output'],$array['smof_output']);
    //print_r($array);
    extract($array);
    $typography_stored = isset($smof_data[$value['id']]) ? $smof_data[$value['id']] : $value['std'];
    $gfonts = json_decode(file_get_contents(dirname(__FILE__).'/webfonts.json'), true);
    /* Font Size */
    
    //if(isset($typography_stored['size'])) {
      $output .= '<div class="select_wrapper typography-size" original-title="Font size">';
      $output .= '<select class="of-typography of-typography-size select google_font_hybrid_value" name="'.$value['id'].'[size]" id="'. $value['id'].'_size">';
        for ($i = 9; $i < 80; $i++){ 
          $test = $i.'px';
          $output .= '<option value="'. $i .'px" ' . selected($typography_stored['size'], $test, false) . '>'. $i .'px</option>'; 
          }
  
      $output .= '</select></div>';
    
    //}
    
    // /* Line Height */
    // //if(isset($typography_stored['height'])) {
    //   $output .= '<div class="select_wrapper typography-size" original-title="Line height">';
    //   $output .= '<select class="of-typography of-typography-height select google_font_hybrid_value" name="'.$value['id'].'[height]" id="'. $value['id'].'_height">';
    //     for ($i = 20; $i < 88; $i++) {
    //       $test = $i.'px';
    //       $output .= '<option value="'. $i .'px" ' . selected($typography_stored['height'], $test, false) . '>'. $i .'px</option>'; 
    //     }
    //   $output .= '</select></div>';
    // //}
    
    /* Font Weight */
    //if(isset($typography_stored['style'])) {
    
      $output .= '<div class="select_wrapper typography-style" original-title="Font style">';
      $output .= '<select class="of-typography of-typography-style select google_font_hybrid_value" name="'.$value['id'].'[style]" id="'. $value['id'].'_style">';
      $styles = array('normal'=>'Normal',
              'italic'=>'Italic',
              'bold'=>'Bold',
              'bold italic'=>'Bold Italic');
      if (isset($gfonts[$typography_stored['face']])) {
        $styles = array();
        foreach ($gfonts[$typography_stored['face']]['variants'] as $k=>$v) {
          $output .= '<option value="'. $v['id'] .'" ' . selected($typography_stored['style'], $v['id'], false) . '>'. $v['name'] .'</option>';   
        }
      } else {
        foreach ($styles as $i=>$style){
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
        $output .= '<option data-details="'.urlencode(json_encode(
          array('variants'=>array(
            array('id'=>'normal', 'name'=>'Normal'),
            array('id'=>'italic', 'name'=>'Italic'),
            array('id'=>'bold', 'name'=>'Bold'),
            array('id'=>'bold italic', 'name'=>'Bold Italic'),
              ))
          )).'" value="'. $i .'" ' . selected($typography_stored['face'], $i, false) . '>'. $face .'</option>';
      }     
      $output .= '<option value="" style="text-align: center;" />-------- GOOGLE WEB FONTS --------</option>';

    
      foreach ($gfonts as $i => $face) {
        $output .= '<option data-details="'.urlencode(json_encode($face)).'" data-google="true" value="'.$i.'" ' . selected($typography_stored['face'], $i, false) . '>'. $i .'</option>';
      } 

      $output .= '</select></div>';

    
    /* Font Color */
    //if(isset($typography_stored['color'])) {
      $output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector typography-color" style="float: right;"><div style="background-color: '.$typography_stored['color'].'"></div></div>';
      $output .= '<input class="of-color of-typography of-typography-color" original-title="Font color" name="'.$value['id'].'[color]" id="'. $value['id'] .'_color" type="text" value="'. $typography_stored['color'] .'" />';
    //}

    
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





function shoestrap_typography_css() {
  echo '<style type="text/css" id="core.typography">';
  echo '#of_container .section-select_google_font_hybrid .typography-script{width:130px !important;margin-right: 0;}';
  echo '#of_container .section-select_google_font_hybrid .typography-style {width:125px !important;}';
  echo '</style>';
}
add_action( 'of_style_only_after', 'shoestrap_typography_css' );


function shoestrap_module_typography_js()
{
  wp_register_script('core_typography', get_template_directory_uri() . '/lib/modules/core.typography/font.js', false, null, true);
  wp_enqueue_script('core_typography');
}
add_action( 'of_load_only_after', 'shoestrap_module_typography_js' );





