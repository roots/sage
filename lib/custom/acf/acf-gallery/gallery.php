<?php

class acf_field_gallery extends acf_field
{

	var $settings;
	
	
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function __construct()
	{
		// vars
		$this->name = 'gallery';
		$this->label = __("Gallery",'acf');
		$this->category = __("Content",'acf');
		
		
		// do not delete!
    	parent::__construct();
    	
    	
    	// extra
		add_action('acf_head-update_attachment-' . $this->name, array($this, 'acf_head_update_attachment'));
		add_action('wp_ajax_acf/fields/gallery/get_image', array($this, 'get_image'));
		add_action('admin_head-media-upload-popup', array($this, 'popup_head'));
		
		
    	// settings
    	// settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.0.0'
		);
	}
	
	
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add css + javascript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{
		// register acf scripts
		wp_register_script( 'acf-input-gallery', $this->settings['dir'] . 'js/input.js', array('acf-input'), $this->settings['version'] );
		wp_register_style( 'acf-input-gallery', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version'] ); 
		
		
		// scripts
		wp_enqueue_script(array(
			'acf-input-gallery',	
		));

		// styles
		wp_enqueue_style(array(
			'acf-input-gallery',	
		));
		
	}
	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add css and javascript to assist your create_field() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_head()
	{
?>
<script type="text/javascript">
acf.fields.gallery.title_add = "<?php _e("Add Image to Gallery",'acf'); ?>";
acf.fields.gallery.title_edit = "<?php _e("Edit Image",'acf'); ?>";
</script>
<?php
	}
	
	
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function create_field( $field )
	{
		// vars
		$defaults = array(
			'value'	=>	false,
			'preview_size' => 'thumbnail',
		);
		
		$field = array_merge($defaults, $field);
		
		?>
<div class="acf-gallery" data-preview_size="<?php echo $field['preview_size']; ?>">
	
	<input type="hidden" name="<?php echo $field['name']; ?>" value="" />
	
	<div class="thumbnails">
		<div class="inner clearfix">
		<?php if( $field['value'] ): foreach( $field['value'] as $attachment ): 
			
			$src = wp_get_attachment_image_src( $attachment['id'], $field['preview_size'] );
			$src = $src[0];
			
			?>
			<div class="thumbnail" data-id="<?php echo $attachment['id']; ?>">
				<input class="acf-image-value" type="hidden" name="<?php echo $field['name']; ?>[]" value="<?php echo $attachment['id']; ?>" />
				<div class="inner clearfix">
					<img src="<?php echo $src; ?>" alt="" />
					<div class="list-data">
						<table>
							<tbody>
							<tr>
								<th><label><?php _e("Title",'acf'); ?>:</label></th>
								<td class="td-title"><?php echo $attachment['title']; ?></td>
							</tr>
							<tr>
								<th><label><?php _e("Alternate Text",'acf'); ?>:</label></th>
								<td class="td-alt"><?php echo $attachment['alt']; ?></td>
							</tr>
							<tr>
								<th><label><?php _e("Caption",'acf'); ?>:</label></th>
								<td class="td-caption"><?php echo $attachment['caption']; ?></td>
							</tr>
							<tr>
								<th><label><?php _e("Description",'acf'); ?>:</label></th>
								<td class="td-description"><?php echo $attachment['description']; ?></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="hover">
					<ul class="bl">
						<li><a href="#" class="acf-button-delete ir"><?php _e("Remove",'acf'); ?></a></li>
						<li><a href="#" class="acf-button-edit ir"><?php _e("Edit",'acf'); ?></a></li>
					</ul>
				</div>
				
			</div>
		<?php endforeach; endif; ?>
		</div>
	</div>

	<div class="toolbar">
		<ul class="hl clearfix">
			<li class="add-image-li"><a class="acf-button add-image" href="#"><?php _e("Add Image",'acf'); ?></a></li>
			<li class="gallery-li view-grid-li active"><div class="divider divider-left"></div><a class="ir view-grid" href="#"><?php _e("Grid",'acf'); ?></a><div class="divider"></div></li>
			<li class="gallery-li view-list-li"><a class="ir view-list" href="#"><?php _e("List",'acf'); ?></a><div class="divider"></div></li>
			<li class="gallery-li count-li right">
				<span class="count" data-0="<?php _e("No images selected",'acf'); ?>" data-1="<?php _e("1 image selected",'acf'); ?>" data-2="<?php _e("{count} images selected",'acf'); ?>"></span>
			</li>
		</ul>
	</div>
	
	<script type="text/html" class="tmpl-thumbnail">
	<div class="thumbnail" data-id="{id}">
		<input type="hidden" class="acf-image-value" name="<?php echo $field['name']; ?>[]" value="{id}" />
		<div class="inner clearfix">
			<img src="{src}" alt="{alt}" />
			<div class="list-data">
				<table>
				<tbody>
					<tr>
						<th><label><?php _e("Title",'acf'); ?>:</label></th>
						<td class="td-title">{title}</td>
					</tr>
					<tr>
						<th><label><?php _e("Alternate Text",'acf'); ?>:</label></th>
						<td class="td-alt">{alt}</td>
					</tr>
					<tr>
						<th><label><?php _e("Caption",'acf'); ?>:</label></th>
						<td class="td-caption">{caption}</td>
					</tr>
					<tr>
						<th><label><?php _e("Description",'acf'); ?>:</label></th>
						<td class="td-description">{description}</td>
					</tr>
				</tbody>
			</table>
			</div>
		</div>
		<div class="hover">
			<ul class="bl">
				<li><a href="#" class="acf-button-delete ir"><?php _e("Remove",'acf'); ?></a></li>
				<li><a href="#" class="acf-button-edit ir"><?php _e("Edit",'acf'); ?></a></li>
			</ul>
		</div>
		
	</div>
	</script>
	
</div>
		<?php
	}
	
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function create_options( $field )
	{
		// vars
		$defaults = array(
			'preview_size'	=>	'thumbnail',
		);
		
		$field = array_merge($defaults, $field);
		$key = $field['name'];
		
?>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Preview Size",'acf'); ?></label>
		<p class="description"><?php _e("Thumbnail is advised",'acf'); ?></p>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][preview_size]',
			'value'		=>	$field['preview_size'],
			'layout'	=>	'horizontal',
			'choices'	=>	apply_filters('acf/get_image_sizes', array())
		));
		
		?>
	</td>
</tr>
		<?php
		
	}
	
	
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed to the create_field action
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value( $value, $post_id, $field )
	{
		$new_value = array();
		
		
		// empty?
		if( empty($value) )
		{
			return $value;
		}
		
		
		// find attachments (DISTINCT POSTS)
		$attachments = get_posts(array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post__in' => $value,
		));
		
		$ordered_attachments = array();
		foreach( $attachments as $attachment)
		{
			// create array to hold value data
			$ordered_attachments[ $attachment->ID ] = array(
				'id' => $attachment->ID,
				'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
				'title' => $attachment->post_title,
				'caption' => $attachment->post_excerpt,
				'description' => $attachment->post_content,
			);
		}
		
		
		// override value array with attachments
		foreach( $value as $v)
		{
			if( isset($ordered_attachments[ $v ]) )
			{
				$new_value[] = $ordered_attachments[ $v ];
			}
		}
		
		
		// return value
		return $new_value;	
	}
	
	
	/*
	*  format_value_for_api()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value_for_api( $value, $post_id, $field )
	{
		$value = $this->format_value( $value, $post_id, $field );
		
		// find all image sizes
		$image_sizes = get_intermediate_image_sizes();


		if( $value )
		{
			foreach( $value as $k => $v )
			{
				// full url
				$src = wp_get_attachment_image_src( $v['id'], 'full' );
				
				$value[ $k ]['url'] = $src[0];
				$value[ $k ]['width'] = $src[1];
				$value[ $k ]['height'] = $src[2];
				
				// sizes
				if( $image_sizes )
				{
					$value[$k]['sizes'] = array();
					
					foreach( $image_sizes as $image_size )
					{
						// find src
						$src = wp_get_attachment_image_src( $v['id'], $image_size );
						
						// add src
						$value[ $k ]['sizes'][ $image_size ] = $src[0];
						$value[ $k ]['sizes'][ $image_size . '-width' ] = $src[1];
						$value[ $k ]['sizes'][ $image_size . '-height' ] = $src[2];
					}
					// foreach( $image_sizes as $image_size )
				}
				// if( $image_sizes )	
			}
			// foreach( $value as $k => $v )
		}
		// if( $value )
		
		
		// return value
		return $value;
	}
	
	
	/*
   	*  acf_head_update_attachment
   	*
   	*  @description: 
   	*  @since: 3.2.7
   	*  @created: 4/07/12
   	*/
   	
   	function acf_head_update_attachment()
	{
		?>
<script type="text/javascript">
(function($){

	// vars
	var div = self.parent.acf.media.div;
	
	
	self.parent.acf.fields.gallery.update_image();
	
	
	// add message
	self.parent.acf.helpers.add_message('<?php _e("Image Updated",'acf'); ?>.', div);
	
		
})(jQuery);
</script>
		<?php
	}
   	
   	
   	/*
	*  get_image
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 18/01/13
	*/
	
   	function get_image()
   	{
   		
   		// vars
		$options = array(
			'nonce' => '',
			'id' => '',
			'preview_size' => 'full'
		);
		$return = array();
		
		
		// load post options
		$options = array_merge($options, $_POST);

		
		// verify nonce
		if( ! wp_verify_nonce($options['nonce'], 'acf_nonce') )
		{
			die(0);
		}
		
		
		// get attachment object
		$attachment = get_post( $options['id'] );
		
		$src = wp_get_attachment_image_src( $attachment->ID, $options['preview_size'] );
		
		$return = array(
			'id' => $attachment->ID,
			'src' => $src[0],
			'title'=> $attachment->post_title,
			'caption'=> $attachment->post_excerpt,
			'alt'=> get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
			'description'=> $attachment->post_content,
		);
		
		
		echo json_encode($return);
		die;
   	}
   	
   	
   	/*
	*  popup_head
	*
	*  @description: css + js for thickbox
	*  @since: 1.1.4
	*  @created: 7/12/12
	*/
	
	function popup_head()
	{
		// options
		$defaults = array(
			'acf_type' => '',
			'acf_gallery_id' => '',
			'acf_preview_size' => 'thumbnail',
			'tab'	=>	'type',	
		);
		
		$options = array_merge($defaults, $_GET);
		
		
		// validate
		if( $options['acf_type'] != 'gallery' )
		{
			return;
		}

			
?><style type="text/css">
	#media-upload-header #sidemenu li#tab-type_url,
	#media-items .media-item a.toggle,
	#media-items .media-item tr.image-size,
	#media-items .media-item tr.align,
	#media-items .media-item tr.url,
	#media-items .media-item .slidetoggle {
		display: none !important;
	}
	
	#media-items .media-item {
		position: relative;
		overflow: hidden;
	}
	
	#media-items .media-item .acf-checkbox {
		float: left;
		margin: 28px 10px 0;
	}
	
	#media-items .media-item .pinkynail {
		max-width: 64px;
		max-height: 64px;
		display: block !important;
		margin: 2px;
	}
	
	#media-items .media-item .filename.new {
		min-height: 0;
		padding: 20px 10px 10px 10px;
		line-height: 15px;
	}
	
	#media-items .media-item .title {
		line-height: 14px;
	}
	
	#media-items .media-item .acf-select {
		float: right;
		margin: 22px 12px 0 10px;
	}
	
	#media-upload .ml-submit {
		display: none !important;
	}

	#media-upload .acf-submit {
		margin: 1em 0;
		padding: 1em 0;
		position: relative;
		overflow: hidden;
		display: none; /* default is hidden */
		clear: both;
	}
	
	#media-upload .acf-submit a {
		float: left;
		margin: 0 10px 0 0;
	}
	
	.acf-message-wrapper .message {
		margin: 2px 2px 0;
	}
	
	#media-items .media-item.media-item-added {
		background: #F9F9F9;
	}
	
	#wpcontent {
   		margin-left: 0 !important;
    }
    
<?php if( $options['tab'] == 'gallery' ): ?>
	#sort-buttons,
	#gallery-form > .widefat,
	#media-items .menu_order,
	#gallery-settings {
		display: none !important;
	}
<?php endif; ?>

</style>
<script type="text/javascript">
(function($){
	
	
	/*
	*  Exists
	*
	*  @description: returns true / false		
	*  @created: 1/03/2011
	*/
	
	$.fn.exists = function()
	{
		return $(this).length>0;
	};
	
	
	/*
	*  global vars
	*
	*  @description: 
	*  @created: 16/08/12
	*/
	var gallery,
		tmpl_thumbnail;
	
	
	/*
	*  Disable / Enable image
	*
	*  @description: 
	*  @created: 16/08/12
	*/
	
	function disable_image( div )
	{
		// add class
		div.addClass('media-item-added');
		
		
		// change button text
		div.find('.acf-select').addClass('disabled').text("<?php _e("Added",'acf'); ?>");
		
	}
	
	
	/*
	*  Select Image
	*
	*  @created : 28/03/2012
	*/
	
	$('#media-items .media-item a.acf-select').live('click', function(){
		
		var attachment_id = $(this).attr('href'),
			div = $(this).closest('.media-item');
		
		
		// does this image already exist in the gallery?
		if( gallery.find('.thumbnail[data-id="' + attachment_id + '"]').length > 0 )
		{
			alert("<?php _e("Image already exists in gallery",'acf'); ?>");
			return false;
		}
		
		
		// show added message
		disable_image( div );
		
		
		// add image
		add_image( attachment_id );
		
		
		return false;
		
	});
	
	
	// edit toggle
	$('#media-items .media-item a.acf-toggle-edit').live('click', function(){
		
		if( $(this).hasClass('active') )
		{
			$(this).removeClass('active');
			$(this).closest('.media-item').find('.slidetoggle').attr('style', 'display: none !important');
			return false;
		}
		else
		{
			$(this).addClass('active');
			$(this).closest('.media-item').find('.slidetoggle').attr('style', 'display: table !important');
			return false;
		}
		
	});
	
	
	// set a interval function to add buttons to media items
	function acf_add_buttons()
	{

		// add buttons to media items
		$('#media-items .media-item:not(.acf-active)').each(function(){
			
			// needs attachment ID
			if($(this).children('input[id*="type-of-"]').length == 0){ return false; }
			
			// only once!
			$(this).addClass('acf-active');
			
			// find id
			var id = $(this).children('input[id*="type-of-"]').attr('id').replace('type-of-', '');
			
			// Add edit button
			$(this).find('.filename.new').append('<br /><a href="#" class="acf-toggle-edit">Edit</a>');
			
			// Add select button
			$(this).find('.filename.new').before('<a href="' + id + '" class="button acf-select"><?php _e("Add Image",'acf'); ?></a>');
			
			// add save changes button
			$(this).find('tr.submit input.button').hide().before('<input type="submit" value="<?php _e("Update Image",'acf'); ?>" class="button savebutton" />');

			
		});
		
		
		// disable images
		gallery.find('.thumbnails .thumbnail input[type="hidden"]').each(function(){
			
			var div = $('#media-item-' + $(this).val());
			
			if( div.exists() )
			{
				disable_image( div );
			}
			
		});
	}
	<?php
	
	// run the acf_add_buttons ever 500ms when on the image upload tab
	if($options['tab'] == 'type'): ?>
	var acf_t = setInterval(function(){
	
		acf_add_buttons();
		
		// auto add images
		$('#media-items .media-item:not(.media-item-added)').each(function(){
			$(this).find('a.acf-select').trigger('click');
		});
		
		
	}, 500);
	<?php endif; ?>
	
	
	// add acf input filters to allow for tab navigation
	$(document).ready(function(){
		
		// vars
		gallery = self.parent.acf.media.div;
		tmpl_thumbnail = gallery.find('.tmpl-thumbnail').html();
		
		
		// add buttins
		setTimeout(function(){
		
			acf_add_buttons();
			
		}, 1);
		
		
		$('form#filter').each(function(){
			
			$(this).append('<input type="hidden" name="acf_preview_size" value="<?php echo $options['acf_preview_size']; ?>" />');
			$(this).append('<input type="hidden" name="acf_type" value="gallery" />');
						
		});
		
		$('form#image-form, form#library-form').each(function(){
			
			var action = $(this).attr('action');
			action += "&acf_type=gallery&acf_preview_size=<?php $options['acf_preview_size']; ?>";
			$(this).attr('action', action);
			
		});
		
		
	});
	
	
	/*
	*  add_image
	*
	*  @description: 
	*  @created: 2/07/12
	*/
	
	function add_image( id )
	{
		var ajax_data = {
			action : 'acf/fields/gallery/get_image',
			nonce : self.parent.acf.nonce,
			id : id,
			preview_size : "<?php echo $options['acf_preview_size']; ?>"
		};
	
		
		// ajax
		$.ajax({
			url: ajaxurl,
			type: 'post',
			data : ajax_data,
			cache: false,
			dataType: "json",
			success: function( json ) {	    	

				// validate
				if( !json )
				{
					return false;
				}
				
				
				// add file
				self.parent.acf.fields.gallery.add( json );
				
			}
		});
		
	}

				
})(jQuery);
</script><?php

	}
}

new acf_field_gallery();

?>
