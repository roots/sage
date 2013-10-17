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
