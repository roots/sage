<?php
/**
 * Post Meta Configurator Module for Shoestrap
 * @category   Module
 * @package    ShoeStrap
 * @author     Mikkel Breum <mail@mikkelbreum.com>
 * @link       https://github.com/shoestrap/shoestrap
 */

if ( !function_exists( 'shoestrap_module_meta_config' ) ) :
function shoestrap_module_meta_config( $sections ) {

  // Branding Options
  $section = array(
    'title' => __( 'Post Meta', 'shoestrap' ),
    'icon'  => 'el-icon-time icon-large'
  );

  $fields[] = array(
    'id'          => 'shoestrap_entry_meta_config',
    'title'       => __( 'Activate and order elements', 'shoestrap' ),
    'options'     => array(
      "disabled"  => array (
        "tags"    => "Tags",
        "sticky"  => "Sticky"
      ),
      "enabled"   => array (
        "date"    => "Date",
        "category"=> "Category",
        "author"  => "Author",
      )
    ),
    'type'        => 'sorter'
  );
  $section['fields'] = $fields;
  $section = apply_filters( 'shoestrap_module_meta_config_modifier', $section );
  $sections[] = $section;

  return $sections;
}
endif;
add_filter( 'redux-sections-'.REDUX_OPT_NAME, 'shoestrap_module_meta_config', 58 );   

include_once( dirname( __FILE__ ) . '/functions.metaconfig.php' );