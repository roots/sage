<?php
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @subpackage  Field_slides
 * @author      Luciano "WebCaos" Ubertini
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Don't duplicate me!
if (!class_exists('ReduxFramework_slides')) {

    /**
     * Main ReduxFramework_slides class
     *
     * @since       1.0.0
     */
    class ReduxFramework_slides extends ReduxFramework
    {

        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function __construct($field = array(), $value = '', $parent)
        {

            parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);

            $this->field = $field;
            $this->value = $value;

        }

        /**
         * Field Render Function.
         *
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render()
        {

            echo '<div class="redux-slides-accordion">';

            $x = 0;

            if (isset($this->value) && is_array($this->value)) {

                $slides = $this->value;

                foreach ($slides as $slide) {

                    if (empty($slide['slide_image_url']) && !empty($slide['slide_image_id'])) {
                        $img = wp_get_attachment_image_src($slide['slide_image_id'], 'full');
                        $slide['slide_image_url'] = $img[0];
                        $slide['slide_image_width'] = $img[1];
                        $slide['slide_image_height'] = $img[2];
                    }

                    if (!isset($slide['slide_title'])) $slide['slide_title'] = '';
                    if (!isset($slide['slide_description'])) $slide['slide_description'] = '';
                    if (!isset($slide['slide_url'])) $slide['slide_url'] = '';
                    if (!isset($slide['slide_sort'])) $slide['slide_sort'] = '';
                    if (!isset($slide['slide_image_id'])) $slide['slide_image_id'] = '';
                    if (!isset($slide['slide_image_url'])) $slide['slide_image_url'] = '';
                    if (!isset($slide['slide_image_height'][$x])) $slide['slide_image_height'] = '';
                    if (!isset($slide['slide_image_width'])) $slide['slide_image_width'] = '';

                    if ($slide['slide_title'] != '' && isset($slide['slide_title'])) {
                        echo '<div class="redux-slides-accordion-group"><fieldset><h3><span class="redux-slides-header">' . $slide['slide_title'] . '</span></h3><div>';

                        $hide = '';
                        if (empty($slide['slide_image_url']))
                            $hide = ' hide';

                        echo '<div class="screenshot' . $hide . '">';
                        echo '<a class="of-uploaded-image" href="' . $slide['slide_image_url'] . '">';
                        echo '<img class="redux-slides-image" id="image_slide_image_id_' . $x . '" src="' . $slide['slide_image_url'] . '" alt="" />';
                        echo '</a>';
                        echo '</div>';

                        echo '<div class="redux_slides_add_remove">';

                        echo '<span class="button media_upload_button" id="add_slide_' . $x . '">' . __('Upload', 'redux-framework') . '</span>';

                        $hide = '';
                        if (empty($slide['slide_image_url']) || $slide['slide_image_url'] == '')
                            $hide = ' hide';

                        echo '<span class="button remove-image' . $hide . '" id="reset_slide_' . $x . '" rel="' . $slide['slide_image_id'] . '">' . __('Remove', 'redux-framework') . '</span>';

                        echo '</div>' . "\n";

                        echo '<ul id="' . $this->field['id'] . '-ul" class="redux-multi-text">';
                        echo '<li><input type="text" id="' . $this->field['id'] . '-slide_title_' . $x . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_title]" value="' . esc_attr($slide['slide_title']) . '" class="full-text slide-title" /></li>';
                        echo '<li><textarea name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_description]" id="' . $this->field['id'] . '-slide_description_' . $x . '" placeholder="'.__('Description', 'redux-framework').'" class="large-text" rows="6">' . esc_attr($slide['slide_description']) . '</textarea></li>';
                        echo '<li><input type="text" id="' . $this->field['id'] . '-slide_url_' . $x . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_url]" value="' . esc_attr($slide['slide_url']) . '" class="full-text" /></li>';
                        echo '<li><input type="hidden" class="slide-sort" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_sort]" id="' . $this->field['id'] . '-slide_sort_' . $x . '" value="' . $slide['slide_sort'] . '" />';
                        echo '<li><input type="hidden" class="upload-id" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_image_id]" id="' . $this->field['id'] . '-slide_image_id_' . $x . '" value="' . $slide['slide_image_id'] . '" />';
                        echo '<input type="hidden" class="upload" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_image_url]" id="' . $this->field['id'] . '-slide_image_url_' . $x . '" value="' . $slide['slide_image_url'] . '" readonly="readonly" />';
                        echo '<input type="hidden" class="upload-height" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_image_height]" id="' . $this->field['id'] . '-slide_image_height_' . $x . '" value="' . $slide['slide_image_height'] . '" />';
                        echo '<input type="hidden" class="upload-width" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_image_width]" id="' . $this->field['id'] . '-slide_image_width_' . $x . '" value="' . $slide['slide_image_width'] . '" /></li>';
                        echo '<li><a href="javascript:void(0);" class="button deletion redux-slides-remove">' . __('Delete Slide', 'redux-framework') . '</a></li>';
                        echo '</ul></div></fieldset></div>';
                        $x++;
                    }
                }
            }

            if ($x == 0) {
                echo '<div class="redux-slides-accordion-group"><fieldset><h3><span class="redux-slides-header">New Slide</span></h3><div>';

                $hide = ' hide';

                echo '<div class="screenshot' . $hide . '">';
                echo '<a class="of-uploaded-image" href="">';
                echo '<img class="redux-slides-image" id="image_slide_image_id_' . $x . '" src="" alt="" />';
                echo '</a>';
                echo '</div>';

                //Upload controls DIV
                echo '<div class="upload_button_div">';

                //If the user has WP3.5+ show upload/remove button
                echo '<span class="button media_upload_button" id="add_slide_' . $x . '">' . __('Upload', 'redux-framework') . '</span>';

                echo '<span class="button remove-image' . $hide . '" id="reset_slide_' . $x . '" rel="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][slide_image_id]">' . __('Remove', 'redux-framework') . '</span>';

                echo '</div>' . "\n";

                echo '<ul id="' . $this->field['id'] . '-ul" class="redux-multi-text">';
                echo '<li><input type="text" id="' . $this->field['id'] . '-slide_title_' . $x . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_title]" value="" placeholder="'.__('Title', 'redux-framework').'" class="full-text slide-title" /></li>';
                echo '<li><textarea name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_description]" id="' . $this->field['id'] . '-slide_description_' . $x . '" placeholder="'.__('Description', 'redux-framework').'" class="large-text" rows="6"></textarea></li>';
                echo '<li><input type="text" id="' . $this->field['id'] . '-slide_url_' . $x . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_url]" value="" class="full-text" placeholder="'.__('URL', 'redux-framework').'" /></li>';
                echo '<li><input type="hidden" class="slide-sort" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_sort]" id="' . $this->field['id'] . '-slide_sort_' . $x . '" value="' . $x . '" />';
                echo '<li><input type="hidden" class="upload-id" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_image_id]" id="' . $this->field['id'] . '-slide_image_id_' . $x . '" value="" />';
                echo '<input type="hidden" class="upload" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_image_url]" id="' . $this->field['id'] . '-slide_image_url_' . $x . '" value="" readonly="readonly" />';
                echo '<input type="hidden" class="upload-height" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_image_height]" id="' . $this->field['id'] . '-slide_image_height_' . $x . '" value="" />';
                echo '<input type="hidden" class="upload-width" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_image_width]" id="' . $this->field['id'] . '-slide_image_width_' . $x . '" value="" /></li>';
                echo '<li><a href="javascript:void(0);" class="button deletion redux-slides-remove">' . __('Delete Slide', 'redux-framework') . '</a></li>';
                echo '</ul></div></fieldset></div>';
            }
            echo '</div><a href="javascript:void(0);" class="button redux-slides-add button-primary" rel-id="' . $this->field['id'] . '-ul" rel-name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][slide_title][]">' . __('Add Slide', 'redux-framework') . '</a><br/>';
            
        }

        /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */

        public function enqueue() {


            wp_enqueue_script(
                'redux-field-media-js',
                REDUX_URL . 'inc/fields/media/field_media.js',
                array( 'jquery', 'wp-color-picker' ),
                time(),
                true
            );

            wp_enqueue_style(
                'redux-field-media-css',
                REDUX_URL . 'inc/fields/media/field_media.css',
                time(),
                true
            );            

            wp_enqueue_script(
                'redux-field-slides-js',
                REDUX_URL . 'inc/fields/slides/field_slides.min.js',
                array('jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'wp-color-picker'),
                time(),
                true
            );

            if (function_exists('wp_enqueue_media')) {
                wp_enqueue_media();
            }
            else {
                wp_enqueue_script('media-upload');
                wp_enqueue_script('thickbox');
                wp_enqueue_style('thickbox');
            }

            wp_enqueue_style(
                'redux-field-slides-css',
                REDUX_URL . 'inc/fields/slides/field_slides.css',
                time(),
                true
            );


        }

    }
}
