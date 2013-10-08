<?php function my_connection_types() {
  p2p_register_connection_type( array(
  	'name' => 'product_to_resource',
  	'from' => 'product',
  	'to' => 'resource',
  	'reciprocal' => true,
  	'sortable' => 'any',
  	'admin_dropdown' => 'any'
  ) );

  p2p_register_connection_type( array(
  	'name' => 'downloads_to_product',
  	'from' => 'attachment',
  	'to' => 'product',
  	'reciprocal' => true,
  	'sortable' => 'any',
  	'admin_dropdown' => 'any'
  ) );

  p2p_register_connection_type( array(
  	'name' => 'Product Data Tables',
  	'from' => 'tablepress_table',
  	'to' => 'product',
  	'reciprocal' => true,
  ) );

  p2p_register_connection_type( array(
  	'name' => 'Related attachments',
  	'from' => 'attachment',
  	'to' => 'attachment',
  	'reciprocal' => true,
  	'sortable' => 'any',
  	'admin_dropdown' => 'any'
  ) );
  
  p2p_register_connection_type( array(
  	'name' => 'Related resources',
  	'from' => 'resource',
  	'to' => 'resource',
  	'reciprocal' => true,
  	'admin_dropdown' => 'any'
  ) );
  
  p2p_register_connection_type( array(
  	'name' => 'resource_to_pa_finish',
  	'from' => 'resource',
  	'to' => 'pa_finish',
  	'reciprocal' => true,
  	'admin_dropdown' => 'any'
  ) );
  
  p2p_register_connection_type( array( 
  	'name' => 'distributor_to_location',
  	'from' => 'distributor',
  	'to' => 'location',
  	'reciprocal' => true,
  	'title' => 'Office location',
  	'admin_dropdown' => 'any'
  ) );
  
  p2p_register_connection_type( array( 
  	'name' => 'atkore_manager',
  	'from' => 'distributor',
  	'to' => 'distributor',
  	'cardinality' => 'one-to-many',
  	'title' => array( 'from' => 'Managed by', 'to' => 'Manages' )
  ) );

}
add_action( 'p2p_init', 'my_connection_types' );