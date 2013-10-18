<?php

add_action('acf/register_fields', 'shortcode_acf_register_fields');
function shortcode_acf_register_fields() {

class shortcode_field extends acf_field
{

	function __construct()
	{

		// set name / title
		$this->name = 'ShortCode'; // variable name (no spaces / special characters / etc)
		$this->label = __("ShortCode",'advanced-custom-fields-shortcode'); // field label (Displayed in edit screens)
		// do not delete!
		parent::__construct();

	}

	function create_field($field)
	{
		echo '<input type="text" value="' . esc_attr($field['value']) . '" id="' . esc_attr($field['name']) . '" class="' . esc_attr($field['class']) . '" name="' . esc_attr($field['name']) . '" />';
		global $shortcode_tags;
		print '<p>';
		print '<span id="sc_acf_detail_'. esc_attr($field['label']) . '" class="button">'.__('View the list of shortcodes','advanced-custom-fields-shortcode').'</span>';
		print '<span id="sc_acf_hide_'. esc_attr($field['label']) . '" class="button" style="display:none">' . __('Hide the list of shortcodes','advanced-custom-fields-shortcode').'</span>';
		print '</p>';
		print '<pre id="sc_acf_'. esc_attr($field['label']) . '" style="display:none">';
		foreach( $shortcode_tags as $key => $val ) {
			print "[" . esc_html($key) . "][/" . esc_html($key) . "]\n"; 
		};
		print '</pre>';
?>
		<script type="text/javascript">
		jQuery(function() {
			jQuery('#sc_acf_detail_<?php echo esc_js($field['label']);?>').click(function(){
				jQuery('#sc_acf_<?php echo esc_js($field['label']);?>').css('display','block');
				jQuery('#sc_acf_hide_<?php echo esc_js($field['label']);?>').css('display','inline');
				jQuery('#sc_acf_detail_<?php echo esc_js($field['label']);?>').css('display','none');
			});
			jQuery('#sc_acf_hide_<?php echo esc_js($field['label']);?>').click(function(){
				jQuery('#sc_acf_<?php echo esc_js($field['label']);?>').css('display','none');
				jQuery('#sc_acf_hide_<?php echo esc_js($field['label']);?>').css('display','none');
				jQuery('#sc_acf_detail_<?php echo esc_js($field['label']);?>').css('display','inline');
			});
		});
		</script>	
<?php
	}

	function format_value_for_api($value, $field)
	{
        return do_shortcode($value);
	}
}
new shortcode_field();
}
