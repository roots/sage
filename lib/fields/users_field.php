<?php

/*
 * Advanced Custom Fields - Users Multi-Select Field add-on
 *	
 * 
 * Contributors: by @lewismcarey (@forepoint) ,
 *               update nicename to displayname - jeradin (ACF support)
 *               translation issue resolved - eonlova (issue 1)
 *               now includes users.php if using ACF front-end-forms - owsleye (issue 2)
 *               additional translation issue resolved - Synaestesia (issue 3)
 *
 * Tags: acf, acf add-on, users, custom field, user field
 * Requires at least: 3.0
 * Tested up to: 3.3.1
 * Stable tag: 0.0
 * 
 * Documentation can be found at https://github.com/lewismcarey/User-Field-ACF-Add-on/blob/master/readme.txt
 * Report any issues or feature requests to https://github.com/lewismcarey/User-Field-ACF-Add-on/issues
 *
 */
 
if ( class_exists( 'acf_Field' ) && ! class_exists( 'Users_field' ) ) {
 
	class Users_field extends acf_Field {
	
		/*--------------------------------------------------------------------------------------
		*
		*	Constructor
		*	- This function is called when the field class is initalized on each page.
		*	- Here you can add filters / actions and setup any other functionality for your field
		*
		*	@author Elliot Condon
		*	@since 2.2.0
		* 
		*-------------------------------------------------------------------------------------*/
		
		function __construct( $parent ) {
		
			// do not delete!
	    	parent::__construct( $parent );
	    	
	    	// set name / title
	    	$this->name = 'users_field'; // variable name (no spaces / special characters / etc)
			$this->title = __( "Users", 'acf' ); // field label (Displayed in edit screens)
			
	   	}
	
		
		/*--------------------------------------------------------------------------------------
		*
		*	create_options
		*	- this function is called from core/field_meta_box.php to create extra options
		*	for your field
		*
		*	@params
		*	- $key (int) - the $_POST obejct key required to save the options to the field
		*	- $field (array) - the field object
		*
		*	@author Elliot Condon
		*	@since 2.2.0
		* 
		*-------------------------------------------------------------------------------------*/
		
		function create_options( $key, $field ) {
		
			//role_capability
			// defaults
			$field['role'] = isset( $field['role'] ) ? $field['role'] : '';
			$field['multiple'] = isset( $field['multiple'] ) ? $field['multiple'] : '0';
			$field['allow_null'] = isset( $field['allow_null'] ) ? $field['allow_null'] : '0';
			
	?>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( "Filter by role", 'acf' ); ?></label>
				</td>
				<td>
	
	<?php 
					$choices = array( '' => '-All-' );
							
					$editable_roles = get_editable_roles();
	
					foreach ( $editable_roles as $role => $details ) {
											
						// only translate the output not the value
						$choices[$role] = translate_user_role( $details['name'] );
						
					}
					
					$this->parent->create_field( array(
						'type' => 'select',
						'name' => 'fields[' . $key . '][role]',
						'value'	=> $field['role'],
						'choices' => array( $choices ),
						'optgroup' => true,
						'multiple' => '1',
					) );
	?>
	
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( "Allow Null?", 'acf' ); ?></label>
				</td>
				<td>
	
	<?php 
					$this->parent->create_field( array(
						'type' => 'radio',
						'name' => 'fields['.  $key . '][allow_null]',
						'value'	=> $field['allow_null'],
						'choices' => array(
							'1'	=> 'Yes',
							'0'	=> 'No',
						),
						'layout' => 'horizontal',
					)) ;
	?>
	
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( "Select multiple values?", 'acf' ); ?></label>
				</td>
				<td>
	
	<?php 
					$this->parent->create_field( array(
						'type' => 'radio',
						'name' => 'fields[' . $key . '][multiple]',
						'value'	=> $field['multiple'],
						'choices' => array (
							'1'	=> 'Yes',
							'0'	=> 'No',
						),
						'layout' => 'horizontal',
					) );
	?>
	
				</td>
			</tr>
	<?php
	
		}
		
		
		/*--------------------------------------------------------------------------------------
		*
		*	pre_save_field
		*	- this function is called when saving your acf object. Here you can manipulate the
		*	field object and it's options before it gets saved to the database.
		*
		*	@author Elliot Condon
		*	@since 2.2.0
		* 
		*-------------------------------------------------------------------------------------*/
		
		function pre_save_field( $field ) {
		
			// do stuff with field (mostly format options data)
			
			return parent::pre_save_field( $field );
			
		}
		
		
		/*--------------------------------------------------------------------------------------
		*
		*	create_field
		*	- this function is called on edit screens to produce the html for this field
		*
		*	@author Elliot Condon
		*	@since 2.2.0
		* 
		*-------------------------------------------------------------------------------------*/
		
		function create_field( $field ) {
		
			$editable_roles = get_editable_roles();
			
			$field['role'] = isset( $field['role'] ) ? $field['role'] : false;
			$field['multiple'] = isset( $field['multiple'] ) ? $field['multiple'] : false;
			
			
			if ( ! $field['role'] || ! is_array( $field['role'] ) || $field['role'][0] == "" ) {
				
				$field['role'] = array();
				
				foreach ( $editable_roles as $role => $details ) {
					
					if ( $details['name'] != "" ) {
						
						$field['role'][] = $role ;
						
					}
					
				}
				
			}
		
			// multiple select
			$multiple = '';
			
			if ( $field['multiple'] == '1' ) {
			
				$multiple = ' multiple="multiple" size="5" ';
				$field['name'] .= '[]';
			} 
			
			// html
			echo '<select id="' . $field['name'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" ' . $multiple . ' >';
			
			// null
			if ( $field['allow_null'] == '1' ) {
			
				echo '<option value="null"> - Select - </option>';
				
			}
			
			
			foreach ( $field['role'] as $role ) {
			
				$label = $editable_roles[ $role]['name'];
				
				// get users
				$args = array(
					'role' => $role	
				);
				
				$users = get_users( $args );			
							
				if ( $users ) {
					
					echo '<optgroup label="' . translate_user_role( $label ) . '">';
	
					foreach ( $users as $k => $user ) {
					
						$key = $user->ID;
						$value = ucfirst( $user->display_name ); 
						$selected = '';
						
						if ( is_array( $field['value'] ) ) {
							
							// 2. If the value is an array (multiple select), 
							// loop through values and check if it is selected
							
							if ( in_array( $key, $field['value'] ) ) {
							
								$selected = 'selected="selected"';
								
							}
							
						} else {
							
							// 3. this is not a multiple select, just check normaly
							if ( $key == $field['value'] ) {
								$selected = 'selected="selected"';
							}
						}
						
						echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
	
					} 
					
					echo '</optgroup>';

				}					
								
			}// endforeach
		
			echo '</select>';
			
		}
		
		
		/*--------------------------------------------------------------------------------------
		*
		*	admin_head
		*	- this function is called in the admin_head of the edit screen where your field
		*	is created. Use this function to create css and javascript to assist your 
		*	create_field() function.
		*
		*	@author Elliot Condon
		*	@since 2.2.0
		* 
		*-------------------------------------------------------------------------------------*/
		
		function admin_head() {
		
			if ( ! function_exists( 'get_editable_roles' ) ) { 
			
				// if using front-end forms then we need to add this core file
				require_once( ABSPATH . '/wp-admin/includes/user.php' ); 
				
			}
			
		}
		
		
		/*--------------------------------------------------------------------------------------
		*
		*	admin_print_scripts / admin_print_styles
		*	- this function is called in the admin_print_scripts / admin_print_styles where 
		*	your field is created. Use this function to register css and javascript to assist 
		*	your create_field() function.
		*
		*	@author Elliot Condon
		*	@since 3.0.0
		* 
		*-------------------------------------------------------------------------------------*/
		
		function admin_print_scripts() {
		
		}
		
		function admin_print_styles() {
			
		}
	
		
		/*--------------------------------------------------------------------------------------
		*
		*	update_value
		*	- this function is called when saving a post object that your field is assigned to.
		*	the function will pass through the 3 parameters for you to use.
		*
		*	@params
		*	- $post_id (int) - usefull if you need to save extra data or manipulate the current
		*	post object
		*	- $field (array) - usefull if you need to manipulate the $value based on a field option
		*	- $value (mixed) - the new value of your field.
		*
		*	@author Elliot Condon
		*	@since 2.2.0
		* 
		*-------------------------------------------------------------------------------------*/
		
		function update_value( $post_id, $field, $value ) {

			// do stuff with value
			
			// save value
			parent::update_value( $post_id, $field, $value );
			
		}
		
		
		/*--------------------------------------------------------------------------------------
		*
		*	get_value
		*	- called from the edit page to get the value of your field. This function is useful
		*	if your field needs to collect extra data for your create_field() function.
		*
		*	@params
		*	- $post_id (int) - the post ID which your value is attached to
		*	- $field (array) - the field object.
		*
		*	@author Elliot Condon
		*	@since 2.2.0
		* 
		*-------------------------------------------------------------------------------------*/
		
		function get_value( $post_id, $field ) {
		
			// get value
			$value = parent::get_value( $post_id, $field );
			
			// format value
			
			// return value
			return $value;
					
		}
		
		
		/*--------------------------------------------------------------------------------------
		*
		*	get_value_for_api
		*	- called from your template file when using the API functions (get_field, etc). 
		*	This function is useful if your field needs to format the returned value
		*
		*	@params
		*	- $post_id (int) - the post ID which your value is attached to
		*	- $field (array) - the field object.
		*
		*	@author Elliot Condon
		*	@since 3.0.0
		* 
		*-------------------------------------------------------------------------------------*/
		
		function get_value_for_api( $post_id, $field ) {
		
			// get value
			$value = $this->get_value( $post_id, $field );
			
			// format value
			if ( ( ! $value ) || ( $value == 'null' ) ) {
				
				return false;
				
			}
			
			// make sure that whats returned is an array
			$value = (array) $value;
			
			foreach ( $value as $k => $v ) {
			
				$user_data = get_userdata( $v );
				
				//cope with deleted users by @adampope
				if ( ! is_object( $user_data ) ) {
				
					unset( $value[$k] );
					
					continue;
			
				}
	
				$value[ $k ] = array();
				$value[ $k ]['ID'] = $v;
				$value[ $k ]['user_firstname'] = $user_data->user_firstname;
				$value[ $k ]['user_lastname'] = $user_data->user_lastname;
				$value[ $k ]['nickname'] = $user_data->nickname;
				$value[ $k ]['user_nicename'] = $user_data->user_nicename;
				$value[ $k ]['display_name'] = $user_data->display_name;
				$value[ $k ]['user_email'] = $user_data->user_email;
				$value[ $k ]['user_url'] = $user_data->user_url;
				$value[ $k ]['user_registered'] = $user_data->user_registered;
				$value[ $k ]['user_description'] = $user_data->user_description;
				$value[ $k ]['user_avatar'] = get_avatar( $v );
				
			}

			// return value
			return $value;
	
		}
		
	}

}

?>