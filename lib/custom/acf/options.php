<?php if( function_exists('acf_add_options_sub_page') )
{
    acf_add_options_sub_page(array(
        'title' => 'Branding',
        'parent' => 'options-general.php',
        'capability' => 'manage_options'
    ));
    acf_add_options_sub_page(array(
        'title' => 'Layout',
        'parent' => 'options-general.php',
        'capability' => 'manage_options'
    ));
    acf_add_options_sub_page(array(
        'title' => 'Forms',
        'parent' => 'options-general.php',
        'capability' => 'manage_options'
    ));
}
 
function my_acf_settings( $options )
{
    // activate add-ons
    $options['activation_codes']['repeater']            = 'QJF7-L4IX-UCNP-RF2W';
    $options['activation_codes']['options_page']        = 'OPN8-FA4J-Y2LW-81LS';
    $options['activation_codes']['gallery']             = 'GF72-8ME6-JS15-3PZC';
    $options['activation_codes']['flexible-content']    = 'FC9O-H6VN-E4CL-LT33';
 
    return $options;
 
}
add_filter('acf_settings', 'my_acf_settings');