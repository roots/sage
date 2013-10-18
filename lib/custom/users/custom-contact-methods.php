<?php
// Register User Contact Methods
function custom_user_contact_methods( $user_contact_method ) {

	$user_contact_method['facebook'] = __( 'Facebook Username', 'text_domain' );
	$user_contact_method['twitter'] = __( 'Twitter Username', 'text_domain' );
	$user_contact_method['gplus'] = __( 'Google Plus', 'text_domain' );
	$user_contact_method['skype'] = __( 'Skype Username', 'text_domain' );

	return $user_contact_method; ;

}

// Hook into the 'user_contactmethods' filter
add_filter( 'user_contactmethods', 'custom_user_contact_methods' );
