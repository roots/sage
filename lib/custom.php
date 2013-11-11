<?php
//define( 'ACF_LITE' , true );
require_once locate_template('/lib/custom/post-types.php');
require_once locate_template('/lib/custom/image-sizes.php');
//require_once locate_template('/lib/custom/post-status/post-status.php');
require_once locate_template('/lib/custom/users/custom-contact-methods.php');
require_once locate_template('/lib/custom/acf/advanced-custom-fields/acf.php' );
require_once locate_template('/lib/custom/acf/acf-gallery/acf-gallery.php');
require_once locate_template('/lib/custom/acf/acf-repeater/acf-repeater.php');
require_once locate_template('/lib/custom/acf/acf-flexible-content/acf-flexible-content.php');
require_once locate_template('/lib/custom/acf/acf-options-page/acf-options-page.php' );
require_once locate_template('/lib/custom/acf/acf-field-date-time-picker/acf-date_time_picker.php' );
require_once locate_template('/lib/custom/acf/acf-gravity-forms/acf-gravity_forms.php' );
require_once locate_template('/lib/custom/acf/acf-shortcode-field/shortcode_field.php' );
require_once locate_template('/lib/custom/acf/acf-leaflet-field/acf-leaflet_field.php' );
require_once locate_template('/lib/custom/acf/acf-wordpress-wysiwyg-field/acf-wp_wysiwyg.php' );
require_once locate_template('/lib/custom/acf/options.php');
require_once locate_template('/lib/custom/acf/options-branding.php');
require_once locate_template('/lib/custom/acf/layout.php');
require_once locate_template('/lib/custom/acf/template-tabs.php');
require_once locate_template('/lib/custom/acf/template-accordian.php');
require_once locate_template('/lib/custom/acf/product-file-downloads.php' );
require_once locate_template('/lib/custom/acf/brand-details.php' );
require_once locate_template('/lib/custom/admin.php');
require_once locate_template('/lib/custom/login.php');
require_once locate_template('/lib/custom/categories.php');
require_once locate_template('/lib/custom/classes.php');
require_once locate_template('/lib/custom/content-filters.php');
require_once locate_template('/lib/custom/convert-to-slug.php');
require_once locate_template('/lib/custom/dashboard.php');
require_once locate_template('/lib/custom/dropdown.php');
require_once locate_template('/lib/custom/filter-ptags-on-images.php');
require_once locate_template('/lib/custom/wp-advanced-search/wpas.php');
require_once locate_template('/lib/custom/woocommerce/theme-wrapper.php');
require_once locate_template('/lib/custom/woocommerce/remove-actions.php');
//require_once locate_template('/lib/custom/woocommerce/new-measurement.php');
//require_once locate_template('/lib/custom/pdfjs/wp_pdfjs.php');

require_once locate_template('/lib/custom/widgets/featured-carousel-widget.php');
//Add class to edit button
function custom_edit_post_link($output) {
 $output = str_replace('class="post-edit-link"', 'class="post-edit-link btn btn-block btn-default"', $output);
 return $output;
}
add_filter('edit_post_link', 'custom_edit_post_link');