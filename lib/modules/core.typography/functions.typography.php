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
    $output = '';
  if ($array['value']['type'] =="select_google_font_hybrid") {
    
    extract($array);

              //  $value['id'] = ;
  $standards = array(
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
         
          $output = '<div class="select_wrapper">';
          $output .= '<select class="select of-input google_font_select_hybrid" name="'.$value['id'].'" id="'. $value['id'] .'">';
          $output .= '<option value="" />Select a Font</option>';
          foreach ($standards as $select_key => $option) {
            $output .= '<option value="'.$select_key.'" ' . selected((isset($smof_data[$value['id']]))? $smof_data[$value['id']] : "", $option, false) . ' />'.$option.'</option>';
          } 
          $output .= '<option value="" style="text-align: center;" />---- Google Web Fonts ----</option>';
          $gfonts = json_decode(file_get_contents(dirname(__FILE__).'/webfonts.json'));
          //print_r($gfonts);
          foreach ($gfonts as $select_key => $option) {
            $output .= '<option data-google="true" value="'.$select_key.'" ' . selected((isset($smof_data[$value['id']]))? $smof_data[$value['id']] : "", $select_key, false) . ' />'.$select_key.'</option>';
          } 
          $output .= '</select></div>';
          
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

  }
  $smof_output = $output;
}




function shoestrap_module_typography_controls()
{
  wp_register_script('core_typography', get_template_directory_uri() . '/lib/modules/core.typography/font.js', false, null, true);
  wp_enqueue_script('core_typography');
}
add_action( 'of_init', 'shoestrap_module_typography_controls' );





