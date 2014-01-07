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
    class ReduxFramework_slides extends ReduxFramework{

        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field = array(), $value ='', $parent ) {
        
            //parent::__construct( $parent->sections, $parent->args );
            $this->parent = $parent;
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
        public function render() {

            echo '<div class="redux-slides-accordion">';

            $x = 0;

            $multi = (isset($this->field['multi']) && $this->field['multi']) ? ' multiple="multiple"' : "";

            if (isset($this->value) && is_array($this->value)) {

                $slides = $this->value;

                foreach ($slides as $slide) {
                    
                    if ( empty( $slide ) ) {
                        continue;
                    }

                    $defaults = array(
                        'title' => '',
                        'description' => '',
                        'sort' => '',
                        'url' => '',
                        'thumb' => '',
                        'attachment_id' => '',
                        'height' => '',
                        'width' => '',
                        'select' => array(),
                    );
                    $slide = wp_parse_args( $slide, $defaults );

                    if (empty($slide['url']) && !empty($slide['attachment_id'])) {
                        $img = wp_get_attachment_image_src($slide['attachment_id'], 'full');
                        $slide['url'] = $img[0];
                        $slide['width'] = $img[1];
                        $slide['height'] = $img[2];
                    }

                    echo '<div class="redux-slides-accordion-group"><fieldset class="redux-field" data-id="'.$this->field['id'].'"><h3><span class="redux-slides-header">' . $slide['title'] . '</span></h3><div>';

                    $hide = '';
                    if ( empty( $slide['url'] ) ) {
                        $hide = ' hide';
                    }

                    echo '<div class="screenshot' . $hide . '">';
                    echo '<a class="of-uploaded-image" href="' . $slide['url'] . '">';
                    echo '<img class="redux-slides-image" id="image_image_id_' . $x . '" src="' . $slide['thumb'] . '" alt="" target="_blank" rel="external" />';
                    echo '</a>';
                    echo '</div>';

                    echo '<div class="redux_slides_add_remove">';

                    echo '<span class="button media_upload_button" id="add_' . $x . '">' . __('Upload', 'redux-framework') . '</span>';

                    $hide = '';
                    if (empty($slide['url']) || $slide['url'] == '')
                        $hide = ' hide';

                    echo '<span class="button remove-image' . $hide . '" id="reset_' . $x . '" rel="' . $slide['attachment_id'] . '">' . __('Remove', 'redux-framework') . '</span>';

                    echo '</div>' . "\n";

                    echo '<ul id="' . $this->field['id'] . '-ul" class="redux-slides-list">';
                    echo '<li><input type="text" id="' . $this->field['id'] . '-title_' . $x . '" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][title]" value="' . esc_attr($slide['title']) . '" placeholder="'.__('Title', 'redux-framework').'" class="full-text slide-title" /></li>';
                    echo '<li><textarea name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][description]" id="' . $this->field['id'] . '-description_' . $x . '" placeholder="'.__('Description', 'redux-framework').'" class="large-text" rows="6">' . esc_attr($slide['description']) . '</textarea></li>';
                    echo '<li><input type="text" id="' . $this->field['id'] . '-url_' . $x . '" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][url]" value="' . esc_attr($slide['url']) . '" class="full-text" placeholder="'.__('URL', 'redux-framework').'" /></li>';
                    echo '<li><input type="hidden" class="slide-sort" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][sort]" id="' . $this->field['id'] . '-sort_' . $x . '" value="' . $slide['sort'] . '" />';
                    echo '<li><input type="hidden" class="upload-id" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][attachment_id]" id="' . $this->field['id'] . '-image_id_' . $x . '" value="' . $slide['attachment_id'] . '" />';
                    echo '<input type="hidden" class="upload-thumbnail" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][thumb]" id="' . $this->field['id'] . '-thumb_url_' . $x . '" value="' . $slide['thumb'] . '" readonly="readonly" />';
                    echo '<input type="hidden" class="upload" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][image]" id="' . $this->field['id'] . '-image_url_' . $x . '" value="' . $slide['url'] . '" readonly="readonly" />';
                    echo '<input type="hidden" class="upload-height" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][height]" id="' . $this->field['id'] . '-image_height_' . $x . '" value="' . $slide['height'] . '" />';
                    echo '<input type="hidden" class="upload-width" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][width]" id="' . $this->field['id'] . '-image_width_' . $x . '" value="' . $slide['width'] . '" /></li>';
                    if ( isset( $this->field['options'] ) && !empty( $this->field['options'] ) ) {
                        $placeholder = (isset($this->field['placeholder']['options'])) ? esc_attr($this->field['placeholder']['options']) : __( 'Select an Option', 'redux-framework' );

                        if ( isset( $this->field['select2'] ) ) { // if there are any let's pass them to js
                            $select2_params = json_encode( esc_attr( $this->field['select2'] ) );
                            $select2_params = htmlspecialchars( $select2_params , ENT_QUOTES);
                            echo '<input type="hidden" class="select2_params" value="'. $select2_params .'">';
                        }

                        echo '<select '.$multi.' id="'.$this->field['id'].'-select" data-placeholder="'.$placeholder.'" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][select]" class="redux-select-item '.$this->field['class'].'" rows="6">';
                            echo '<option></option>';
                            foreach($this->field['options'] as $k => $v){
                                if (is_array($this->value)) {
                                    $selected = (is_array($this->value) && in_array($k, $this->value))?' selected="selected"':'';                   
                                } else {
                                    $selected = selected($this->value, $k, false);
                                }
                                echo '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
                            }//foreach
                        echo '</select>';                           
                    }
                    echo '<li><a href="javascript:void(0);" class="button deletion redux-slides-remove">' . __('Delete Slide', 'redux-framework') . '</a></li>';
                    echo '</ul></div></fieldset></div>';
                    $x++;
                
                }
            }

            if ($x == 0) {
                echo '<div class="redux-slides-accordion-group"><fieldset class="redux-field" data-id="'.$this->field['id'].'"><h3><span class="redux-slides-header">New Slide</span></h3><div>';

                $hide = ' hide';

                echo '<div class="screenshot' . $hide . '">';
                echo '<a class="of-uploaded-image" href="">';
                echo '<img class="redux-slides-image" id="image_image_id_' . $x . '" src="" alt="" target="_blank" rel="external" />';
                echo '</a>';
                echo '</div>';

                //Upload controls DIV
                echo '<div class="upload_button_div">';

                //If the user has WP3.5+ show upload/remove button
                echo '<span class="button media_upload_button" id="add_' . $x . '">' . __('Upload', 'redux-framework') . '</span>';

                echo '<span class="button remove-image' . $hide . '" id="reset_' . $x . '" rel="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][attachment_id]">' . __('Remove', 'redux-framework') . '</span>';

                echo '</div>' . "\n";

                echo '<ul id="' . $this->field['id'] . '-ul" class="redux-slides-list">';
                $placeholder = (isset($this->field['placeholder']['title'])) ? esc_attr($this->field['placeholder']['title']) : __( 'Title', 'redux-framework' );
                echo '<li><input type="text" id="' . $this->field['id'] . '-title_' . $x . '" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][title]" value="" placeholder="'.$placeholder.'" class="full-text slide-title" /></li>';
                $placeholder = (isset($this->field['placeholder']['description'])) ? esc_attr($this->field['placeholder']['description']) : __( 'Description', 'redux-framework' );
                echo '<li><textarea name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][description]" id="' . $this->field['id'] . '-description_' . $x . '" placeholder="'.$placeholder.'" class="large-text" rows="6"></textarea></li>';
                $placeholder = (isset($this->field['placeholder']['url'])) ? esc_attr($this->field['placeholder']['url']) : __( 'URL', 'redux-framework' );
                echo '<li><input type="text" id="' . $this->field['id'] . '-url_' . $x . '" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][url]" value="" class="full-text" placeholder="'.$placeholder.'" /></li>';
                echo '<li><input type="hidden" class="slide-sort" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][sort]" id="' . $this->field['id'] . '-sort_' . $x . '" value="' . $x . '" />';
                echo '<li><input type="hidden" class="upload-id" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][attachment_id]" id="' . $this->field['id'] . '-image_id_' . $x . '" value="" />';
                echo '<input type="hidden" class="upload" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][url]" id="' . $this->field['id'] . '-image_url_' . $x . '" value="" readonly="readonly" />';
                echo '<input type="hidden" class="upload-height" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][height]" id="' . $this->field['id'] . '-image_height_' . $x . '" value="" />';
                echo '<input type="hidden" class="upload-width" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][width]" id="' . $this->field['id'] . '-image_width_' . $x . '" value="" /></li>';

                    if ( isset( $this->field['options'] ) && !empty( $this->field['options'] ) ) {
                        $placeholder = (isset($this->field['placeholder']['select'])) ? esc_attr($this->field['placeholder']['select']) : __( 'Select an Option', 'redux-framework' );
                        if ( isset( $this->field['select2'] ) ) { // if there are any let's pass them to js
                            $select2_params = json_encode( esc_attr( $this->field['select2'] ) );
                            $select2_params = htmlspecialchars( $select2_params , ENT_QUOTES);
                            echo '<input type="hidden" class="select2_params" value="'. $select2_params .'">';
                        }

                        echo '<select '.$multi.' id="'.$this->field['id'].'-select" data-placeholder="'.$placeholder.'" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][select]" class="redux-select-item '.$this->field['class'].'" rows="6" style="width:93%;">';
                            echo '<option></option>';
                            foreach($this->field['options'] as $k => $v){
                                if (is_array($this->value)) {
                                    $selected = (is_array($this->value) && in_array($k, $this->value))?' selected="selected"':'';                   
                                } else {
                                    $selected = selected($this->value, $k, false);
                                }
                                echo '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
                            }//foreach
                        echo '</select>';                           
                    }

                echo '<li><a href="javascript:void(0);" class="button deletion redux-slides-remove">' . __('Delete Slide', 'redux-framework') . '</a></li>';
                echo '</ul></div></fieldset></div>';
            }
            echo '</div><a href="javascript:void(0);" class="button redux-slides-add button-primary" rel-id="' . $this->field['id'] . '-ul" rel-name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][title][]">' . __('Add Slide', 'redux-framework') . '</a><br/>';
            
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
                ReduxFramework::$_url . 'inc/fields/media/field_media.js',
                array( 'jquery', 'wp-color-picker' ),
                time(),
                true
            );

            wp_enqueue_style(
                'redux-field-media-css',
                ReduxFramework::$_url . 'inc/fields/media/field_media.css',
                time(),
                true
            );            

            wp_enqueue_script(
                'redux-field-slides-js',
                ReduxFramework::$_url . 'inc/fields/slides/field_slides.js',
                array('jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'wp-color-picker'),
                time(),
                true
            );

            wp_enqueue_style(
                'redux-field-slides-css',
                ReduxFramework::$_url . 'inc/fields/slides/field_slides.css',
                time(),
                true
            );


        }

    }
}
