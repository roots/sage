jQuery(document).ready(function(){

	// setup chosen javascript dropdowns when the document is ready
	setup_chz();

	jQuery('div.widgets-sortables').bind('sortstop',function(event,ui){
		
		// setup chosen javascript when widget is dragged to widget area
		
		// I can get an alert to work here after widget is dropped
		// but the setup_chz() doesn't seem to work?
		setup_chz();
	});
	
	// handle cleaning youtube id for video entry in projects
	
	jQuery('#projectVideo, #postVideo').keyup(function(){
		var project_video_value;
		project_video_value = jQuery(this).val();
		console.log( project_video_value );
		jQuery(this).val( clean_youtube_id( project_video_value ));
	});
	
});

jQuery(window).load(function(){
	mdg_alerts();
});

function setup_chz(){
	//jQuery.each(".chzn-select", function(){

	jQuery(".chzn-select").chosen({
		allow_single_deselect:true
	});
	
	jQuery(".chzn-select").chosen().change(function(e){
		
		change_chz(e);

	});
}

function change_chz(e){
	// this updates the hidden text box that holds selections
	var chz, select_id, text_field;
	chz   = jQuery('#' + e.currentTarget.id).val();
	select_id  = e.currentTarget.id.replace('chz_', '');
	text_field  = jQuery("#" + select_id);

	text_field.val(chz);
	console.log(select_id);

}

jQuery(document).ajaxSuccess(function(e, xhr, settings) {
	
	// setup chosen javascript after a widget is saved
	setup_chz();
});

function mdg_alerts(){
	// this will show a little alert above the title on the awards page (in admin)
	if( pagenow == 'award' ) {

		jQuery('#titlediv').before( '<div class="updated"><p>Please give the award a title and a featured image.</p></div>' );
	
	} else if( pagenow == 'certification' ) {

		jQuery('#titlediv').before( '<div class="updated"><p>Please give the Certification a title and a featured image.</p></div>' );
	} else if( pagenow == 'upload' ) {
		jQuery('h2').after( '<div class="updated"><p><a href="#" id="image-size-reference-trigger">View image size reference.</a></p></div><div class="image-size-reference"></div>' );
		
		jQuery('#image-size-reference-trigger').click(function(){
			mdg_get_image_reference_grid();
		});
		
	}
}

function mdg_get_image_reference_grid(){
	var html;
	jQuery.get(

		// ajaxurl is defined in templates/head.php
		'/wp-admin/admin-ajax.php',
		{
			action 		: 'mdg-image-reference-grid'
		},
		function( return_html ) {
			jQuery('#image-size-reference-trigger').after(
				'<p><a href="#" id="hide-image-grid-reference">hide image sizes</a></p>'+
				'<p>please note that image sizes may be smaller to fit into your screen</p>'
			);		
			jQuery('.image-size-reference').empty().append( return_html + '<div style="clear:both"></div>' );
			
			jQuery('#hide-image-grid-reference').click(function(){
				jQuery(this).parent().next().remove();
				jQuery(this).remove();
				jQuery('.image-size-reference').empty();
			});
			
		} // end success function
	);
	
	return html;
}

/*****************************

	Uploader
	
*****************************/
/* Code Only Blog Post
 *
 * To add a custom field media uploader in WordPress
 * using the built-in thick box you need to override
 * the window.send_to_editor method. This is a JavaScript
 * code block that will help you do just that.
 *
 * The best way to get the JavaScript onto the page you
 * are working with is to use the wp_enqueue_script and
 * wp_register_script functions. Then need it to run against
 * the page when it loads.
 *
 * You can do this using the add_action method or something else.
 *
 * http://codex.wordpress.org/Function_Reference/wp_enqueue_script
 * http://codex.wordpress.org/Function_Reference/wp_register_script
 *
 * If you are working with a page that doesn't include the WordPress
 * editor you will be missing the files: thickbox and media-upload.
 * You must include these files as well for the uploader to work.
 * Including your script last.
 *
 * Here is an example of the php function you might create:
 *
 * function scripts() {
 * wp_enqueue_script('media-upload');
 * wp_enqueue_script('thickbox');
 * wp_register_script('upload', '/wp-content/plugins/js/upload.js', array('jquery','media-upload','thickbox'));
 *  wp_enqueue_script('upload');
 * }
 *
 * Note: you should never assume the plugin directory as show above.
 * It is best to use a function to get this path.
 *
 * The below works with WordPress 3.2 and has been tested
 */
jQuery(document).ready(function() {

	/**
	 * Add Uploader.
	 *
	 * Attach on click event to button enabling thickbox uploader
	 * built into wordpress. Uses jQuery like selector for
	 * strings parameters. Targets must be one element. This
	 * function allows for multiple uploaders per page when
	 * editor is present.
	 *
	 * (required)
	 * @para1 string -> form button's id to open uploader box
	 * @para2 string -> form input's id where image url will go
	 *
	 * Example: set_uploader('#button', '#field')
	 *
	 * Usage: Added function call to end of this file inside the
	 * ready function. See example for calling the function.
	 */
	function set_uploader(button, field) {
		// make sure both button and field are in the DOM
		if(jQuery(button) && jQuery(field)) {
			// when button is clicked show thick box
			jQuery(button).click(function() {
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');

				// when the thick box is opened set send to editor button
				set_send(field);
				return false;
			});
		}
	}

	/* Setup Send Button
	 *
	 * Add image url to the set_uploader() field parameters element
	 * when send or "Insert into Post" is clicked; setting the value
	 * to the image's path.
	 *
	 * (required)
	 * @para1 string -> form field id
	 *
	 * Example: set_url('#filed')
	 *
	 * Usage: needed by the set_uploader, no calls outside needed
	 */
	function set_send(field) {
		// store send_to_event so at end of function normal editor works
		window.original_send_to_editor = window.send_to_editor;

		// override function so you can have multiple uploaders pre page
		window.send_to_editor = function(html) {
			imgurl = jQuery(html).attr('src');
			if(!imgurl){
				imgurl = jQuery('img', html).attr('src');
			}
			
			if(!imgurl){
				// might be a file (pdf)
				// let's try this
				imgurl = jQuery(html).attr('href');
			}
			jQuery(field).val(imgurl);
			tb_remove();
			// Set normal uploader for editor
			window.send_to_editor = window.original_send_to_editor;
		};
	}

	// place set_uploader functions below, button then field
	set_uploader('.upload-link-projectHomePageFeaturedImage', '#projectHomePageFeaturedImage');

});