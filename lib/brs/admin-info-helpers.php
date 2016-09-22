<?php

// buildAddress() - Creates a full address from the meta information given
function buildAddress($meta){
  if( empty( $meta ) ){ return 'Please update the administrators billing information in the User area in the Admin'; }
  return $meta['billing_address_1'][0] . '</br>' . $meta['billing_address_2'][0] . '</br>' . $meta['billing_city'][0] . ' ' . $meta['billing_state'][0] . ', ' . $meta['billing_postcode'][0];
}

// getAdminInfo() - A helper to access the admin associated users information
function getAdminInfo(){
  // What's the email address for the wp administrator?
  // https://developer.wordpress.org/reference/functions/get_option
  $admin_email = get_option( 'admin_email' );

  if( !empty( $admin_email ) ){
    // Let's get the user object, so that we can reference the ID elsewhere
    // The user should be accessed very little, as I don't like this...hashed pw at least
    // https://developer.wordpress.org/reference/functions/get_user_by/
    $user = get_user_by( 'email', $admin_email );

    // Let's get the user meta data, which we need to acccess the billing information
    // https://developer.wordpress.org/reference/functions/get_user_meta/
    $meta = get_user_meta( $user->ID );

    // Let's get the admin users associated avatar / photo, which is pulled from gravatar
    // https://codex.wordpress.org/Function_Reference/get_avatar
    $photo = get_avatar( $user->ID, 32 );
  }

  if ( !empty( $meta ) ) {
    // Create the final user array to pass to the template, for scrubbing and simplification
    return array(
      'email'         => $admin_email,
      'name'          => $meta['first_name'][0] . ' ' . $meta['last_name'][0],
      'first_name'    => $meta['first_name'][0],
      'last_name'     => $meta['last_name'][0],
      'phone'         => $meta['billing_phone'][0],
      'address_1'     => $meta['billing_address_1'][0],
      'address_2'     => $meta['billing_address_2'][0],
      'city'          => $meta['billing_city'][0],
      'state'         => $meta['billing_state'][0],
      'zip'           => $meta['billing_postcode'][0],
      'full_address'  => buildAddress($meta),
      'photo'         => $photo
    );
  }
}
