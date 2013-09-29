<?php 
if(function_exists('register_field'))
{
register_field('Users_field', dirname(__File__) . '/fields/users_field.php');
register_field('Gravity_Forms_field', dirname(__File__) . '/fields/gravity_forms.php');
register_field('acf_time_picker', dirname(__File__) . '/fields/acf_time_picker/acf_time_picker.php');

}