<?php 

/*
*  Input
*
*  @description: controller for adding field HTML to edit screens
*  @since: 3.6
*  @created: 25/01/13
*/

class acf_input
{

	/*
	*  __construct
	*
	*  @description: 
	*  @since 3.1.8
	*  @created: 23/06/12
	*/
	
	function __construct()
	{
		
		// actions
		add_action('admin_enqueue_scripts', array($this,'admin_enqueue_scripts'));
		
		
		// save
		add_action('save_post', array($this, 'save_post'), 10, 1);
		
		
		// actions
		add_action('acf/input/admin_head', array($this, 'input_admin_head'));
		add_action('acf/input/admin_enqueue_scripts', array($this, 'input_admin_enqueue_scripts'));
		
		
		// ajax acf/update_field_groups
		add_action('wp_ajax_acf/input/render_fields', array($this, 'ajax_render_fields'));
		add_action('wp_ajax_acf/input/get_style', array($this, 'ajax_get_style'));
	}
	
	
	/*
	*  validate_page
	*
	*  @description: returns true | false. Used to stop a function from continuing
	*  @since 3.2.6
	*  @created: 23/06/12
	*/
	
	function validate_page()
	{
		// global
		global $pagenow, $typenow;
		
		
		// vars
		$return = false;
		
		
		// validate page
		if( in_array( $pagenow, array('post.php', 'post-new.php') ) )
		{
		
			// validate post type
			global $typenow;
			
			if( $typenow != "acf" )
			{
				$return = true;
			}
			
		}
		
		
		// validate page (Shopp)
		if( $pagenow == "admin.php" && isset( $_GET['page'] ) && $_GET['page'] == "shopp-products" && isset( $_GET['id'] ) )
		{
			$return = true;
		}
		
		
		// return
		return $return;
	}
	
	
	/*
	*  admin_enqueue_scripts
	*
	*  @description: run after post query but before any admin script / head actions. A good place to register all actions.
	*  @since: 3.6
	*  @created: 26/01/13
	*/
	
	function admin_enqueue_scripts()
	{
		// validate page
		if( ! $this->validate_page() ){ return; }

		
		// scripts
		do_action('acf/input/admin_enqueue_scripts');
		
		
		// head
		add_action('admin_head', array($this,'admin_head'));
	}
	
	
	/*
	*  admin_head
	*
	*  @description: 
	*  @since 3.1.8
	*  @created: 23/06/12
	*/
	
	function admin_head()
	{
		// globals
		global $post, $pagenow, $typenow;
		
		
		// shopp
		if( $pagenow == "admin.php" && isset( $_GET['page'] ) && $_GET['page'] == "shopp-products" && isset( $_GET['id'] ) )
		{
			$typenow = "shopp_product";
		}
		
		
		// vars
		$post_id = $post ? $post->ID : 0;
		
			
		// get field groups
		$filter = array( 
			'post_id' => $post_id, 
			'post_type' => $typenow 
		);
		$metabox_ids = array();
		$metabox_ids = apply_filters( 'acf/location/match_field_groups', $metabox_ids, $filter );
		
		
		// get style of first field group
		$style = '';
		if( isset($metabox_ids[0]) )
		{
			$style = $this->get_style( $metabox_ids[0] );
		}
		
		
		// Style
		echo '<style type="text/css" id="acf_style" >' . $style . '</style>';
		
		
		// add user js + css
		do_action('acf/input/admin_head');
		
		
		// get field groups
		$acfs = apply_filters('acf/get_field_groups', array());
		
		
		if( $acfs )
		{
			foreach( $acfs as $acf )
			{
				// load options
				$acf['options'] = apply_filters('acf/field_group/get_options', array(), $acf['id']);
				
				
				// vars
				$show = in_array( $acf['id'], $metabox_ids ) ? 1 : 0;
				$priority = 'high';
				if( $acf['options']['position'] == 'side' )
				{
					$priority = 'core';
				}
				
				
				// add meta box
				add_meta_box(
					'acf_' . $acf['id'], 
					$acf['title'], 
					array($this, 'meta_box_input'), 
					$typenow, 
					$acf['options']['position'], 
					$priority, 
					array( 'field_group' => $acf, 'show' => $show, 'post_id' => $post_id )
				);
				
			}
			// foreach($acfs as $acf)
		}
		// if($acfs)
		
		
		// Allow 'acf_after_title' metabox position
		add_action('edit_form_after_title', array($this, 'edit_form_after_title'));
	}
	
	
	/*
	*  edit_form_after_title
	*
	*  This action will allow ACF to render metaboxes after the title
	*
	*  @type	action
	*  @date	17/08/13
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	function edit_form_after_title()
	{
		// globals
		global $post, $wp_meta_boxes;
		
		
		// render
		do_meta_boxes( get_current_screen(), 'acf_after_title', $post);
		
		
		// clean up
		unset( $wp_meta_boxes['post']['acf_after_title'] );
	}
	
	
	/*
	*  meta_box_input
	*
	*  @description: 
	*  @since 1.0.0
	*  @created: 23/06/12
	*/
	
	function meta_box_input( $post, $args )
	{
		// extract $args
		extract( $args );
		
		
		// classes
		$class = 'acf_postbox ' . $args['field_group']['options']['layout'];
		$toggle_class = 'acf_postbox-toggle';
		
		
		if( ! $args['show'] )
		{
			$class .= ' acf-hidden';
			$toggle_class .= ' acf-hidden';
		}

		?>
<script type="text/javascript">
(function($) {
	
	$('#<?php echo $id; ?>').addClass('<?php echo $class; ?>').removeClass('hide-if-js');
	$('#adv-settings label[for="<?php echo $id; ?>-hide"]').addClass('<?php echo $toggle_class; ?>');
	
})(jQuery);	
</script>
		<?php
		
		
		// nonce
		echo '<input type="hidden" name="acf_nonce" value="' . wp_create_nonce( 'input' ) . '" />';
		
		
		// HTML
		if( $args['show'] )
		{
			$fields = apply_filters('acf/field_group/get_fields', array(), $args['field_group']['id']);
	
			do_action('acf/create_fields', $fields, $args['post_id']);
		}
		else
		{
			echo '<div class="acf-replace-with-fields"><div class="acf-loading"></div></div>';
		}
	}
	
	
	/*
	*  get_style
	*
	*  @description: called by admin_head to generate acf css style (hide other metaboxes)
	*  @since 2.0.5
	*  @created: 23/06/12
	*/

	function get_style( $acf_id )
	{
		// vars
		$options = apply_filters('acf/field_group/get_options', array(), $acf_id);
		$html = '';
		
		
		// add style to html 
		if( in_array('the_content',$options['hide_on_screen']) )
		{
			$html .= '#postdivrich {display: none;} ';
		}
		if( in_array('excerpt',$options['hide_on_screen']) )
		{
			$html .= '#postexcerpt, #screen-meta label[for=postexcerpt-hide] {display: none;} ';
		}
		if( in_array('custom_fields',$options['hide_on_screen']) )
		{
			$html .= '#postcustom, #screen-meta label[for=postcustom-hide] { display: none; } ';
		}
		if( in_array('discussion',$options['hide_on_screen']) )
		{
			$html .= '#commentstatusdiv, #screen-meta label[for=commentstatusdiv-hide] {display: none;} ';
		}
		if( in_array('comments',$options['hide_on_screen']) )
		{
			$html .= '#commentsdiv, #screen-meta label[for=commentsdiv-hide] {display: none;} ';
		}
		if( in_array('slug',$options['hide_on_screen']) )
		{
			$html .= '#slugdiv, #screen-meta label[for=slugdiv-hide] {display: none;} ';
		}
		if( in_array('author',$options['hide_on_screen']) )
		{
			$html .= '#authordiv, #screen-meta label[for=authordiv-hide] {display: none;} ';
		}
		if( in_array('format',$options['hide_on_screen']) )
		{
			$html .= '#formatdiv, #screen-meta label[for=formatdiv-hide] {display: none;} ';
		}
		if( in_array('featured_image',$options['hide_on_screen']) )
		{
			$html .= '#postimagediv, #screen-meta label[for=postimagediv-hide] {display: none;} ';
		}
		if( in_array('revisions',$options['hide_on_screen']) )
		{
			$html .= '#revisionsdiv, #screen-meta label[for=revisionsdiv-hide] {display: none;} ';
		}
		if( in_array('categories',$options['hide_on_screen']) )
		{
			$html .= '#categorydiv, #screen-meta label[for=categorydiv-hide] {display: none;} ';
		}
		if( in_array('tags',$options['hide_on_screen']) )
		{
			$html .= '#tagsdiv-post_tag, #screen-meta label[for=tagsdiv-post_tag-hide] {display: none;} ';
		}
		if( in_array('send-trackbacks',$options['hide_on_screen']) )
		{
			$html .= '#trackbacksdiv, #screen-meta label[for=trackbacksdiv-hide] {display: none;} ';
		}
		
				
		return $html;
	}
	
	
	/*
	*  ajax_get_input_style
	*
	*  @description: called by input-actions.js to hide / show other metaboxes
	*  @since 2.0.5
	*  @created: 23/06/12
	*/
	
	function ajax_get_style()
	{
		// vars
		$options = array(
			'acf_id' => 0,
			'nonce' => ''
		);
		
		// load post options
		$options = array_merge($options, $_POST);
		
		
		// verify nonce
		if( ! wp_verify_nonce($options['nonce'], 'acf_nonce') )
		{
			die(0);
		}
		
		
		// return style
		echo $this->get_style( $options['acf_id'] );
		
		
		// die
		die;
	}
	
	
	/*
	*  ajax_render_fields
	*
	*  @description: 
	*  @since 3.1.6
	*  @created: 23/06/12
	*/

	function ajax_render_fields()
	{
		
		// defaults
		$options = array(
			'acf_id' => 0,
			'post_id' => 0,
			'nonce' => ''
		);
		
		
		// load post options
		$options = array_merge($options, $_POST);
		
		
		// verify nonce
		if( ! wp_verify_nonce($options['nonce'], 'acf_nonce') )
		{
			die(0);
		}
		
		
		// get acfs
		$acfs = apply_filters('acf/get_field_groups', array());
		if( $acfs )
		{
			foreach( $acfs as $acf )
			{
				if( $acf['id'] == $options['acf_id'] )
				{
					$fields = apply_filters('acf/field_group/get_fields', array(), $acf['id']);
					
					do_action('acf/create_fields', $fields, $options['post_id']);
					
					break;
				}
			}
		}

		die();
		
	}
	
	
	/*
	*  save_post
	*
	*  @description: Saves the field / location / option data for a field group
	*  @since 1.0.0
	*  @created: 23/06/12
	*/
	
	function save_post( $post_id )
	{	
		
		// do not save if this is an auto save routine
		if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		{
			return $post_id;
		}
		
		
		// verify nonce
		if( !isset($_POST['acf_nonce'], $_POST['fields']) || !wp_verify_nonce($_POST['acf_nonce'], 'input') )
		{
			return $post_id;
		}
		
		
		// if save lock contains a value, the save_post action is already running for another post.
		// this would imply that the user is hooking into an ACF update_value or save_post action and inserting a new post
		// if this is the case, we do not want to save all the $POST data to this post.
		if( isset($GLOBALS['acf_save_lock']) && $GLOBALS['acf_save_lock'] )
		{
			return $post_id;
		}
		
		
		// update the post (may even be a revision / autosave preview)
		do_action('acf/save_post', $post_id);
        
	}
	
		
	/*
	*  input_admin_head
	*
	*  action called when rendering the head of an admin screen. Used primarily for passing PHP to JS
	*
	*  @type	action
	*  @date	27/05/13
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	function input_admin_head()
	{
		// global
		global $wp_version, $post;
		
				
		// vars
		$toolbars = apply_filters( 'acf/fields/wysiwyg/toolbars', array() );
		$post_id = 0;
		if( $post )
		{
			$post_id = intval( $post->ID );
		}
		
		
		// l10n
		$l10n = apply_filters( 'acf/input/admin_l10n', array(
			'core' => array(
				'expand_details' => __("Expand Details",'acf'),
				'collapse_details' => __("Collapse Details",'acf')
			),
			'validation' => array(
				'error' => __("Validation Failed. One or more fields below are required.",'acf')
			)
		));
		
		
		// options
		$o = array(
			'post_id'		=>	$post_id,
			'nonce'			=>	wp_create_nonce( 'acf_nonce' ),
			'admin_url'		=>	admin_url(),
			'ajaxurl'		=>	admin_url( 'admin-ajax.php' ),
			'wp_version'	=>	$wp_version
		);
		
		
		// toolbars
		$t = array();
		
		if( is_array($toolbars) ){ foreach( $toolbars as $label => $rows ){
			
			$label = sanitize_title( $label );
			$label = str_replace('-', '_', $label);
			
			$t[ $label ] = array();
			
			if( is_array($rows) ){ foreach( $rows as $k => $v ){
				
				$t[ $label ][ 'theme_advanced_buttons' . $k ] = implode(',', $v);
				
			}}
		}}
		
			
		?>
<script type="text/javascript">
(function($) {

	// vars
	acf.post_id = <?php echo is_numeric($post_id) ? $post_id : '"' . $post_id . '"'; ?>;
	acf.nonce = "<?php echo wp_create_nonce( 'acf_nonce' ); ?>";
	acf.admin_url = "<?php echo admin_url(); ?>";
	acf.ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
	acf.wp_version = "<?php echo $wp_version; ?>";
	
	
	// new vars
	acf.o = <?php echo json_encode( $o ); ?>;
	acf.l10n = <?php echo json_encode( $l10n ); ?>;
	acf.fields.wysiwyg.toolbars = <?php echo json_encode( $t ); ?>;

})(jQuery);	
</script>
		<?php
	}
	
	
	
	/*
	*  input_admin_enqueue_scripts
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 30/01/13
	*/
	
	function input_admin_enqueue_scripts()
	{

		// scripts
		wp_enqueue_script(array(
			'jquery',
			'jquery-ui-core',
			'jquery-ui-tabs',
			'jquery-ui-sortable',
			'wp-color-picker',
			'thickbox',
			'media-upload',
			'acf-input',
			'acf-datepicker',	
		));

		
		// 3.5 media gallery
		if( function_exists('wp_enqueue_media') && !did_action( 'wp_enqueue_media' ))
		{
			wp_enqueue_media();
		}
		
		
		// styles
		wp_enqueue_style(array(
			'thickbox',
			'wp-color-picker',
			'acf-global',
			'acf-input',
			'acf-datepicker',	
		));
	}
			
}

new acf_input();

?>