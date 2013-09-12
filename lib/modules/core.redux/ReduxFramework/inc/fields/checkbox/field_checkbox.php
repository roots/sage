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
 * @subpackage  Field_Checkbox
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_checkbox' ) ) {

    /**
     * Main ReduxFramework_checkbox class
     *
     * @since       1.0.0
     */
    class ReduxFramework_checkbox extends ReduxFramework {   
    
        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function __construct( $field = array(), $value ='', $parent ) {
        
            parent::__construct( $parent->sections, $parent->args, $parent->extra_tabs );
        
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

            // Use data from Wordpress to populate options array
            if( !empty( $this->field['data'] ) && empty( $this->field['options'] ) ) {
                if( empty( $this->field['args'] ) )
                    $this->field['args'] = array();

                $this->field['options'] = array();
                $args = wp_parse_args( $this->field['args'], array() ); 

                if( $this->field['data'] == 'categories' || $this->field['data'] == 'category' ) {
                    $cats = get_categories($args); 

                    if( !empty( $cats ) ) {     
                        foreach( $cats as $cat ) {
                            $this->field['options'][$cat->term_id] = $cat->name;
                        }
                    }
                } else if( $this->field['data'] == 'menus' || $this->field['data'] == 'menu' ) {
                    $menus = wp_get_nav_menus( $args );
                    if( !empty( $menus ) ) {
                        foreach( $menus as $k=>$item ) {
                            $this->field['options'][$item->term_id] = $item->name;
                        }
                    }
                } else if( $this->field['data'] == 'pages' || $this->field['data'] == 'page' ) {
                    $pages = get_pages( $args ); 

                    if( !empty( $pages ) ) {
                        foreach( $pages as $page ) {
                            $this->field['options'][$page->ID] = $page->post_title;
                        }
                    }
                } else if( $this->field['data'] == 'posts' || $this->field['data'] == 'post' ) {
                    $posts = get_posts( $args );

                    if( !empty( $posts ) ) {
                        foreach( $posts as $post ) {
                            $this->field['options'][$post->ID] = $post->post_title;
                        }
                    }
                } else if( $this->field['data'] == 'post_type' || $this->field['data'] == 'post_types' ) {
                    $post_types = get_post_types( $args, 'object' ); 

                    if( !empty( $post_types ) ) {
                        foreach ( $post_types as $k => $post_type ) {
                            $this->field['options'][$k] = $post_type->labels->name;
                        }
                    }
                } else if( $this->field['data'] == 'tags' || $this->field['data'] == 'tag' ) {
                    $tags = get_tags( $args );

                    if( !empty( $tags ) ) {
                        foreach( $tags as $tag ) {
                            $this->field['options'][$tag->term_id] = $tag->name;
                        }
                    }
                } else if( $this->field['data'] == 'menu_location' || $this->field['data'] == 'menu_locations' ) {
                    global $_wp_registered_nav_menus;

                    foreach( $_wp_registered_nav_menus as $k => $v ) {
                        $this->field['options'][$k] = $v;
                    }
                }
            }

            echo '<fieldset>';

            if( !empty( $this->field['options'] ) && ( is_array( $this->field['options'] ) || is_array( $this->field['default'] ) ) ) {
                echo '<ul>';
            
                foreach( $this->field['options'] as $k => $v ) {
                
                    $this->value[$k] = ( isset( $this->value[$k] ) ) ? $this->value[$k] : '';

                    echo '<li>';
                    echo '<label for="' . strtr($this->args['opt_name'] . '[' . $this->field['id'] . '][' . $k . ']', array('[' => '_', ']' => '')) . '_' . array_search( $k, array_keys( $this->field['options'] ) ) . '">';
                    echo '<input type="checkbox" class="checkbox ' . $this->field['class'] . '" id="' . strtr($this->args['opt_name'] . '[' . $this->field['id'] . '][' . $k . ']', array('[' => '_', ']' => '')) . '_' . array_search( $k, array_keys( $this->field['options'] ) ) . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $k . ']" value="1" ' . checked( $this->value[$k], '1', false ) . '/>';
                    echo ' ' . $v . '</label>';
                    echo '</li>';
                
                }

                echo '</ul>';   

                echo ( isset( $this->field['desc'] ) && !empty( $this->field['desc'] ) ) ? '<div class="description">' . $this->field['desc'] . '</div>' : '';

            } else {

                echo ( $this->field['desc'] != '' ) ? ' <label for="' . strtr($this->args['opt_name'] . '[' . $this->field['id'] . ']', array('[' => '_', ']' => '')) . '">' : '';
        
                echo '<input type="checkbox" id="' . strtr($this->args['opt_name'] . '[' . $this->field['id'] . ']', array('[' => '_', ']' => '')) . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" value="1" class="checkbox ' . $this->field['class'] . '" ' . checked( $this->value, '1', false ) . '/>';
        
                echo ( isset( $this->field['desc'] ) && !empty( $this->field['desc'] ) ) ? ' ' . $this->field['desc'] . '</label>' : '';

            }

            echo '</fieldset>';         
        }
    }
}
?>