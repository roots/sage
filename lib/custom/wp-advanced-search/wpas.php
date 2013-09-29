<?php
/**
 * WP Advanced Search 
 *
 * A PHP framework for building advanced search forms in WordPress
 *
 * @author Sean Butze
 * @link https://github.com/growthspark/wp-advanced-search
 * @version 1.0
 * @license MIT
 */

require_once('wpas-field.php');

if (!class_exists('WP_Advanced_Search')) {
    class WP_Advanced_Search {

        // Query Data
        private $wp_query;
        private $wp_query_args = array();
        private $taxonomy_operators = array();
        private $term_formats = array();
        private $meta_keys = array();
        private $orderby_relevanssi = 'relevance';
        private $relevanssi = false;
        private $orderby_values = array('ID','author','title','date','modified','parent','rand','comment_count','menu_order');
        private $orderby_meta_keys = array();

        // Form Input
        private $form_args = array();
        private $post_types = array();
        private $selected_taxonomies = array();
        private $selected_meta_keys = array();

        private $the_form;

        function __construct($args = '') {
            if ( !empty($args) ) {
                $this->process_args($args);
                $this->build_form();
            }
            $this->convert_orderby();
            $this->process_form_input();
        }

        /**
         * Parses arguments and sets variables
         *
         * @since 1.0
         */
        function process_args( $args ) {
            if (isset($args['wp_query'])) {
                $this->wp_query_args = $args['wp_query'];
                if (isset($args['wp_query']['post_type'])) {
                    if (is_array($args['wp_query']['post_type']))
                        $this->post_types = $args['wp_query']['post_type'];
                    else
                        $this->post_types = array($args['wp_query']['post_type']);
                }
            }
            if (isset($args['form'])) 
                $this->form_args = $args['form'];
            if (isset($args['fields']))
                $this->fields = $args['fields'];
            if (isset($args['relevanssi']))
                $this->relevanssi = $args['relevanssi'];

            $fields = $this->fields;
            foreach ($fields as $field) {

                if (isset($field['type'])) {
                    switch($field['type']) {
                        case  'taxonomy':
                            if (isset($field['taxonomy'])) {
                                $tax = $field['taxonomy'];
                                if (isset($field['operator'])) {
                                    $operator = $field['operator'];
                                } else {
                                    $operator = 'AND';
                                }
                                $this->taxonomy_operators[$tax] = $operator;
                            }
                            break;
                        case 'meta_key':
                            if (isset($field['meta_key'])) {
                                $meta = $field['meta_key'];

                                if (isset($field['compare'])) {
                                    $operator = $field['compare'];
                                } else {
                                    $operator = '=';
                                }

                                if (isset($field['data_type'])) {
                                    $data_type = $field['data_type'];
                                } else {
                                    $data_type = 'CHAR';
                                }

                                $this->meta_keys[$meta]['compare'] = $operator;
                                $this->meta_keys[$meta]['data_type'] = $data_type;
                            }
                            break;  
                        case 'orderby':
                            if (isset($field['orderby_values']) && is_array($field['orderby_values'])) {
                                foreach ($field['orderby_values'] as $k=>$v) {
                                    // Special handling for meta_key values
                                    if (isset($v['meta_key']) && $v['meta_key']) {
                                        if (isset($v['orderby']) && $v['orderby'] == 'meta_value_num') $type = $v['orderby'];
                                        else $type = 'meta_value';
                                        $this->orderby_meta_keys[$k] = $type;
                                    }
                                }
                            }
                            break;  
                    }
                }
            }
        }


        /**
         * Generates the search form
         *
         * @since 1.0
         */
        function build_form() {
            global $post;
            global $wp_query;

            $defaults = array('action' => get_permalink($post->ID),
                                'method' => 'GET',
                                'id' => 'wp-advanced-search',
                                'name' => 'wp-advanced-search',
                                'class' => 'wp-advanced-search');

            $args = wp_parse_args($this->form_args, $defaults);
            $fields = $this->fields;
            $tax_fields = array();
            $has_search = false;
            $has_submit = false;
            $has_orderby = false;
            $html = 1;

            if (isset($_REQUEST['filter_page'])) {
                $page = $_REQUEST['filter_page'];
            } else {
                $page = 1;
            }

            $output = '<form id="'.$args['id'].'" name="'.$args['name'].'" class="'.$args['class'].'" method="'.$args['method'].'" action="'.$args['action'].'">';

                // URL fix if pretty permalinks are not enabled
                if ( get_option('permalink_structure') == '' ) { 
                    $output .= '<input type="hidden" name="page_id" value="'.$post->ID.'">'; 
                }

                foreach ($fields as $field) {
                    if (isset($field['type'])) {
                        switch($field['type']) {
                            case 'taxonomy':
                                if (isset($field['taxonomy']) && !in_array($field['taxonomy'], $tax_fields)) {
                                    $tax_fields[] = $field['taxonomy'];
                                    $output .= $this->tax_field($field);
                                }
                                break;
                            case 'meta_key':
                                $output .= $this->meta_field($field);
                                break;
                            case 'author':
                                $output .= $this->author_field($field);
                                break;
                            case 'date':
                                $output .= $this->date_field($field);
                                break;
                            case 'post_type':
                                $output .= $this->post_type_field($field);
                                break;
                            case 'order':
                                $output .= $this->order_field($field);
                                break;
                            case 'orderby':
                                $has_orderby = true;
                                $output .= $this->orderby_field($field);
                                break;
                            case 'html':
                                if (empty($field['id'])) {
                                    $field['id'] = $html;
                                    $html++;
                                }
                                $output .= $this->html_field($field);
                                break;
                            case 'generic':
                                $output .= $this->generic_field($field);
                                break;
                            case 'posts_per_page':
                                $output .= $this->posts_per_page_field($field);
                                break;
                            case 'search':
                                if (!$has_search) {
                                    $output .= $this->search_field($field);
                                    $has_search = true;
                                }
                                break;
                            case 'submit':
                                if (!$has_submit) {
                                    $output .= $this->submit_button($field);
                                    $has_submit = true;
                                }
                                break;
                        } //end switch
                    } //endif
                } //endforeach

            if ($this->relevanssi && !$has_orderby) {
                $output .= '<input type="hidden" name="orderby" value="'.$this->orderby_relevanssi.'">';
            }

            $output .= '</form>';
            $this->the_form = $output;
        }

        /**
         * Displays the search form
         *
         * @since 1.0
         */
        function the_form() {
            echo $this->the_form;
        }

        /**
         * Generates a search field
         *
         * @since 1.0
         */
        function search_field( $args ) {
            $defaults = array(
                            'label' => '',
                            'format' => 'text',
                            'value' => ''
                        );
            $args = wp_parse_args($args, $defaults);
            $format = $args['format'];
            if (isset($_REQUEST['search_query'])) {
                $value = $_REQUEST['search_query'];
            } else {
                $value = $args['value'];
            }
            $args['values'] = $value;
            $field = new WPAS_Field('search_query', $args);
            return $field->build_field();
        }

        /**
         * Generates a submit button
         *
         * @since 1.0
         */
        function submit_button( $args ) {
            $defaults = array('value' => 'Search');
            $args = wp_parse_args($args, $defaults);
            extract($args);
            $args['values'] = $value;
            $args['format'] = 'submit';
            $field = new WPAS_Field('submit', $args);
            return $field->build_field();
        }


        /**
         * Generates a form field containing terms for a given taxonomy
         *
         * @param array $args Arguments for configuring the field.
         * @since 1.0
         */
        function tax_field( $args ) {
            $defaults = array( 
                            'taxonomy' => 'category',
                            'format' => 'select',
                            'term_format' => 'slug',
                            'hide_empty' => false,
                            'terms' => array(),
                            'term_args' => array()
                        );

            $term_defaults = array( 
                            'hide_empty' => false
                        );

            $args = wp_parse_args($args, $defaults);
            extract(wp_parse_args($args, $defaults));

            $this->term_formats[$taxonomy] = $term_format;

            $the_tax = get_taxonomy( $taxonomy );
            $tax_name = $the_tax->labels->name;
            $tax_slug = $the_tax->name;

            if (!$the_tax) {
                return;
            }

            if (isset($args['label'])) {
                $label = $args['label'];
            } 

            if (isset($term_args) && is_array($term_args)) {
                $term_args = wp_parse_args($term_args, $term_defaults);
            }

            $terms_objects = array();
            $term_values = array();

            if (isset($terms) && is_array($terms) && (count($terms) < 1)) {
                $term_objects = get_terms($taxonomy, $term_args); 
            } else {
                foreach ($terms as $term_identifier) {
                    $term = get_term_by($term_format, $term_identifier, $taxonomy);
                    if ($term) {
                        $term_objects[] = $term;
                    }
                }
            }
                
            foreach ($term_objects as $term) {
                switch($term_format) {
                    case 'id' :
                    case 'ID' :
                        $term_values[$term->term_id] = $term->name;
                        break;
                    case 'Name' :
                    case 'name' :
                        $term_values[$term->name] = $term->name;
                        break;
                    default :
                        $term_values[$term->slug] = $term->name;
                        break;
                }
            }

            // Don't populate with values if this is a text or textarea field
            if (empty($values)) {
                if (!($format == 'text' || $format == 'textarea')) {
                    $args['values'] = $term_values;
                }
            }

            $args['label'] = $label;

            $field = new WPAS_Field('tax_'.$tax_slug, $args);
            return $field->build_field();

        }


        /**
         * Generates a form field containing terms for a given meta key (custom field)
         *
         * @param array $args Arguments for configuring the field.
         * @since 1.0
         */
        function meta_field( $args ) {

            $defaults = array(
                            'label' => '',
                            'meta_key' => '',
                            'format' => 'select',
                            'values' => array()
                        );

            $args = wp_parse_args($args, $defaults);
            $meta_key = $args['meta_key'];

            $field = new WPAS_Field('meta_'.$meta_key, $args);
            return $field->build_field();           
        }


         /**
         * Generates an order field
         *
         * @since 1.0
         */         
        function order_field( $args ) {
            $defaults = array(
                'label' => '',
                'format' => 'select',
                'orderby' => 'title',
                'values' => array('ASC' => 'ASC', 'DESC' => 'DESC')
            );

            $args = wp_parse_args($args, $defaults);

            $field = new WPAS_Field('order', $args);
            return $field->build_field();               

        }

         /**
         * Generates an orderby field
         *
         * @since 1.0
         */         
        function orderby_field( $args ) {
            $defaults = array('label' => '',
                              'format' => 'select',
                              'values' => array('ID' => 'ID', 
                                                'post_author' => 'Author', 
                                                'post_title' => 'Title', 
                                                'post_date' => 'Date', 
                                                'post_modified' => 'Modified',
                                                'post_parent' => 'Parent ID',
                                                'rand' => 'Random',
                                                'comment_count' => 'Comment Count',
                                                'menu_order' => 'Menu Order')
                        );

            $args = wp_parse_args($args, $defaults);

            if (isset($args['orderby_values']) && is_array($args['orderby_values'])) {
                $args['values'] = array(); // orderby_values overrides normal values
                foreach ($args['orderby_values'] as $k=>$v) {
                    if (isset($v['label'])) $label = $v['label'];
                    else $label = $k;
                    $args['values'][$k] = $label; // add to the values array
                }
            }

            $field = new WPAS_Field('orderby', $args);
            return $field->build_field();   
        }


         /**
         * Generates an author field
         *
         * @since 1.0
         */   
        function author_field( $args ) {
            $defaults = array(
                    'label' => '',
                    'format' => 'select',
                    'authors' => array()
                );

            $args = wp_parse_args($args, $defaults);
            $label = $args['label'];
            $format = $args['format'];
            $authors_list = $args['authors'];
            $selected_authors = array();

            if (isset($this->selected_authors)) {
                $selected_authors = $this->selected_authors;
            }

            $the_authors_list = array();

            if (count($authors_list) < 1) {
                    $authors = get_users();
                    foreach ($authors as $author) {
                        $the_authors_list[$author->ID] = $author->display_name;
                    }
            } else {
                foreach ($authors_list as $author) {
                    if (get_userdata($author)) {
                        $user = get_userdata($author);
                        $the_authors_list[$author] = $user->display_name;
                    }
                }
            }

            $args['values'] = $the_authors_list;

            $field = new WPAS_Field('a', $args);
            return $field->build_field();

        }

         /**
         * Generates a post type field
         *
         * @since 1.0
         */   
        function post_type_field( $args ) {
            $defaults = array(
                    'label' => '',
                    'format' => 'select',
                    'values' => array('post' => 'Post', 'page' => 'Page')
                );

            $args = wp_parse_args($args, $defaults);
            $label = $args['label'];
            $format = $args['format'];
            $values = $args['values'];
            $selected_values = array();

            if (isset($this->selected_post_types)) {
                $selected_values = $this->selected_post_types;
            }

            if (count($values) < 1) {
                $post_types = get_post_types(array('public' => true)); 
                foreach ( $post_types as $post_type ) {
                    $obj = get_post_type_object($post_type);
                    $post_type_id = $obj->name;
                    $post_type_name = $obj->labels->name;
                    $values[$post_type_id] = $post_type_name;
                }
            } 

            $args['values'] = $values;

            $field = new WPAS_Field('ptype', $args);
            return $field->build_field();
            
        }

        /**
         * Generates a date field
         *
         * @since 1.0
         */   
        function date_field( $args ) {
            $defaults = array(
            'label' => '',
            'id' => 'date_y',
            'format' => 'select',
            'date_type' => 'year',
            'date_format' => false,
            'values' => array() );

            $args = wp_parse_args($args, $defaults);
            extract($args);
 
            $selected_values = array();

            switch ($date_type) {
                case ('year') :
                    if (count($values) < 1) {
                        $d_values = $this->get_dates('year',$date_format);
                    }
                    $id = 'date_y';
                    break;
                case ('month') :
                    if (count($values) < 1) {
                        $d_values = $this->get_dates('month',$date_format);
                    }
                    $id = 'date_m';
                    break;
                case ('day') :
                    if (count($values) < 1) {
                        $d_values = $this->get_dates('day',$date_format);
                    }
                    $id = 'date_d';
            }

            if (empty($values)) {
                $args['values'] = $d_values;
            }
        
            $args['id'] = $id;

            $field = new WPAS_Field($id, $args);
            return $field->build_field();

        }

        /**
         * Generates an HTML content field
         * 
         * This "field" is not used for data entry but rather for inserting
         * custom markup within the form body.
         *
         * @since 1.0
         */   
        function html_field( $args ) {
            $defaults = array('id'=>1, 'value' => '');
            extract(wp_parse_args($args, $defaults));

            $args['format'] = 'html';
            $args['values'] = $value;

            $field = new WPAS_Field('html-'.$id, $args);
            return $field->build_field();
        }

        /**
         * Generates a generic form field
         * 
         * Used for creating form fields that do not affect
         * the WP_Query object
         *
         * @since 1.0
         */   
        function generic_field( $args ) {
            $defaults = array();
            extract(wp_parse_args($args, $defaults));

            if (isset($id) && !empty($id)) {
                $field = new WPAS_Field($id, $args);
                return $field->build_field();
            }
        }

        /**
         * Generates a generic form field
         * 
         * Used for creating form fields that do not affect
         * the WP_Query object
         *
         * @since 1.0
         */  
        function posts_per_page_field( $args ) {
            $defaults = array(
                'format' => 'text',
                'value' => ''
                );

            $args = wp_parse_args($args, $defaults);
            $field = new WPAS_Field('posts_per_page', $args);
            return $field->build_field();
        }

        /**
         * Builds the tax_query component of our WP_Query object based on form input
         *
         * @since 1.0
         */
        function build_tax_query() {
            $query = $this->wp_query_args;
            $taxonomies = $this->selected_taxonomies;
            

            foreach ($taxonomies as $tax => $terms) {
                $term_slugs = array(); // used when term_format is set to 'name'
                $term_format = $this->term_formats[$tax];
                $has_error = false;
                if (isset($this->term_formats[$tax]))
                    $term_format = $this->term_formats[$tax];
                else
                    $term_format = 'slug';

                if ($term_format == 'name') {
                    if (!is_array($terms)) 
                        $terms = array($terms);

                    foreach ($terms as $term) {
                        $the_term = get_term_by('name', $term, $tax);
                        if ($the_term) {
                            $term_slugs[] = $the_term->slug;
                        } else {
                            $has_error = true;
                            $term_slugs[] = $term; // even if term invalid, we have to add it anyway to produce 0 results
                        }
                    }

                    $terms = $term_slugs;
                    $term_format = 'slug';
                } else if (!($term_format == 'slug') && !($term_format == 'id')) {
                    $this->error('Invalid term_format for taxonomy "'.$tax.'"');
                    continue;
                }

                $this->wp_query_args['tax_query'][] = array(    
                                                        'taxonomy' => $tax,
                                                        'field' => $term_format,
                                                        'terms' => $terms,
                                                        'operator' => $this->taxonomy_operators[$tax]
                                                        );
            } // endforeach $taxonomies

        }

        /**
         * Builds the meta_query component of our WP_Query object based on form input
         *
         * @since 1.0
         */
        function build_meta_query() {
            $meta_keys = $this->selected_meta_keys;

            foreach ($meta_keys as $key => $values) {
                
                if ($this->meta_keys[$key]['compare'] == 'BETWEEN') {

                    //Special handling for BETWEEN comparisons.
                    
                    foreach($values as $value) {
                        if (strpos($value, '-')) {
                            $value_one = strstr($value,'-',true);
                            $value_two = substr(strstr($value, '-'),1);
                            if ($value_one == 0 || $value_two == 0) {
                                $values = 0;
                                $compare = '=';
                            } else {
                                $compare = $this->meta_keys[$key]['compare'];
                                $values = array($value_one, $value_two);
                            }
                            

                            $this->wp_query_args['meta_query'][] = array(   
                                                                    'key' => $key,
                                                                    'value' => $values,
                                                                    'compare' => $compare,
                                                                    'type' => $this->meta_keys[$key]['data_type']
                                                                    );

                        } else {
                            $this->error('Invalid meta_value "'. $values .'"" for BETWEEN comparison.');
                        }
                    }

                } else {

                    $this->wp_query_args['meta_query'][] = array(   
                                                            'key' => $key,
                                                            'value' => $values,
                                                            'compare' => $this->meta_keys[$key]['compare'],
                                                            'type' => $this->meta_keys[$key]['data_type']
                                                            );
                }
            }
     
        }

        /**
         * Processes form input and modifies the query accordingly
         *
         * @since 1.0
         */
        function process_form_input() {

            foreach ($_REQUEST as $request => $value) {

                if ($value) {
                    $this->selected_fields[$request] = $value;
                    if (substr($request, 0, 4) == 'tax_') {
                        $tax = $this->tax_from_arg($request);
                        if ($tax) {
                            $this->selected_taxonomies[$tax] = $value;
                        }
                    } elseif (substr($request, 0, 5) == 'meta_') {
                        $meta = $this->meta_from_arg($request);
                        if ($meta) {
                            $this->selected_meta_keys[$meta] = $value;
                        }
                    } else {
                        $selected = array();
                        if (!is_array($value)) {
                            $selected[] = $value;
                        } else {
                            foreach ($value as $the_value) {
                                $selected[] = $the_value;
                            }
                        }

                        switch($request) {
                            case('a') :
                                $this->wp_query_args['author'] = implode(',', $selected);
                                break;
                            case('ptype') :
                                $this->post_types = $selected;
                                $this->wp_query_args['post_type'] = $selected;
                                break;
                            case('order') :
                                $this->wp_query_args['order'] = implode(',', $selected);
                                break;
                            case('orderby') :
                                $orderby = implode(',', $selected);
                                if (array_key_exists($orderby,$this->orderby_meta_keys)) {
                                    $this->wp_query_args['orderby'] = $this->orderby_meta_keys[$orderby];
                                    $this->wp_query_args['meta_key'] = $orderby;
                                } else {
                                    $this->wp_query_args['orderby'] = $orderby;
                                }
                                break;
                            case('date_y') :
                                $this->wp_query_args['year'] = implode(',', $selected);
                                break;
                            case('date_m') :
                                $year = strstr(reset($selected), '-', true);
                                $month = substr(strstr(reset($selected), '-'), 1);
                                $this->wp_query_args['monthnum'] = $month;
                                $this->wp_query_args['year'] = $year;
                                break;      
                            case('date_d') :
                                $dates = explode('-', reset($selected));
                                if (isset($dates[0])) $this->wp_query_args['year'] = $dates[0];
                                if (isset($dates[1])) $this->wp_query_args['month'] = $dates[1];
                                if (isset($dates[2])) $this->wp_query_args['day'] = $dates[2];
                                break;  
                            case('posts_per_page') :
                                $ppp = implode(',', $selected);
                                $this->wp_query_args['posts_per_page'] = intval($ppp);
                                break;  
                            case('search_query') :
                                $this->wp_query_args['s'] = implode(',', $selected);
                                break;  
                        } // switch

                    } // endif
                } // endif ($value)

            }// endforeach $_REQUEST

        }

        /**
         * Converts certain orderby keys into formats compatible with WP_Query
         *
         * @since 1.0
         */
        function convert_orderby() {
            if (isset($this->wp_query_args['orderby'])) {
                switch($this->wp_query_args['orderby']) {
                    case 'post_title' :
                    case 'post_author' :
                    case 'post_date' :
                    case 'post_modified' :
                    case 'post_parent' :
                    case 'post_name' :
                        $this->orderby_relevanssi = $this->wp_query_args['orderby'];
                        $this->wp_query_args['orderby'] = substr($this->wp_query_args['orderby'], 5);
                        break;
                }   
            }
        }

        /**
         * Initializes a WP_Query object with the given search parameters
         *
         * @since 1.0
         */
        function query() {
            $this->build_tax_query();
            $this->build_meta_query();
            $this->convert_orderby();

            // Apply pagination
            if ( get_query_var('paged') ) {
                $paged = get_query_var('paged');
            } else if ( get_query_var('page') ) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }
            $this->wp_query_args['paged'] = $paged;

            $this->wp_query = new WP_Query($this->wp_query_args);

            $query = $this->wp_query;
            $query->query_vars['post_type'] = $this->wp_query_args['post_type'];

            if ($this->relevanssi) {
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                if (!empty($_REQUEST['search_query']) && is_plugin_active('relevanssi/relevanssi.php')) {
                    relevanssi_do_query($query);
                }
            }

            if (defined('WPAS_DEBUG') && WPAS_DEBUG) {
                echo '<pre>';
                print_r($query);
                echo '</pre>';
            }

            return $query;
        }


        /**
         * Displays pagination links
         *
         * @since 1.0
         */
        function pagination( $args = '' ) {
            global $wp_query;
            $current_page = max(1, get_query_var('paged'));
            $total_pages = $wp_query->max_num_pages;

            $big = '999999999';
            $base = str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) );

            $defaults = array(
                            'base' => $base,
                            'format' => 'page/%#%',
                            'current' => $current_page,
                            'total' => $total_pages
                        );

            $args = wp_parse_args($args, $defaults);

            if ($total_pages > 1){
                echo '<div class="pagination">';
                echo paginate_links($args);
                echo '</div>';
            }

        }

        /**
         * Displays range of results displayed on the current page.
         *
         * @since 1.0
         */
        function results_range( $args = array() ) {
            global $wp_query;

            $defaults = array(
                            'pre' => '',
                            'marker' => '-',
                            'post' => ''
                        );  

            $args = wp_parse_args($args, $defaults);    
            extract($args);

            $total = $wp_query->found_posts;
            $count = $wp_query->post_count;
            $query = $wp_query->query;
            $ppp = (!empty($query['posts_per_page'])) ? $query['posts_per_page'] : get_option('posts_per_page');
            $page =  get_query_var('paged');
            $range = 1;

            $range = $page;
            if ($ppp > 1) {
                $i = 1 + (($page - 1)*$ppp);
                $j = $i + ($ppp - 1);
                $range = sprintf('%d%s%d', $i, $marker, $j);
                if ($j > $total) {
                    $range = $total;
                } 
            }

            if ($count < 1) {
                $range = 0;
            }

            $output = sprintf('<span>%s</span> <span>%s</span> <span>%s</span>', $pre, $range, $post);

            return $output;
        }

        /**
         * Accepts a term slug & taxonomy and returns the ID of that term
         *
         * @since 1.0
         */
        function term_slug_to_id( $term, $taxonomy ) {
            $term = get_term_by('slug', $term, $taxonomy);
            return $term->term_id;
        }

        /**
         * Takes a specially-formatted taxonomy argument and returns the taxonomy name
         *
         * @since 1.0
         */
        function tax_from_arg( $arg ) {
            if (substr($arg, 0, 4) == 'tax_'){
                $tax = substr($arg, 4, strlen($arg) - 4);
                if (taxonomy_exists($tax)) {
                    return $tax;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        }

        /**
         * Takes a specially-formatted meta_key argument and returns the meta_key
         *
         * @since 1.0
         */
        function meta_from_arg( $arg ) {
            if (substr($arg, 0, 5) == 'meta_'){
                $meta = substr($arg, 5, strlen($arg) - 5);
                return $meta;
            } else {
                return false;
            }

        }

        /**
         *  Returns an array of dates in which content has been published
         *
         *  @since 1.0
         */
        function get_dates($date_type = 'year', $format = false) {

            $display_format = "Y";
            $compare_format = "Y";

            if ($date_type == 'month') {
                $display_format = "M Y";
                $compare_format = "Y-m";
            } else if ($date_type == 'day') {
                $display_format = "M j, Y";
                $compare_format = "Y-m-d";
            }

            if ($format) $display_format = $format;

            $post_type = $this->wp_query_args['post_type'];
            $posts = get_posts(array('numberposts' => -1, 'post_type' => $post_type));
            $previous_display = "";
            $previous_value = "";        
            $count = 0;

            $dates = array();

            foreach($posts as $post) {
                $post_date = strtotime($post->post_date);
                $current_display = date_i18n($display_format, $post_date);
                $current_value = date($compare_format, $post_date);

                if ($previous_value != $current_value) {
                    $dates[$current_value] = $current_display;
                }
                $previous_display = $current_display;
                $previous_value = $current_value;

            }
            return $dates;
        }

        /**
         *  Displays an error message for debugging
         *
         *  @since 1.0
         */
        function error($msg = false) {
            if ($msg) {
                if (defined('WPAS_DEBUG') && WPAS_DEBUG) {
                    echo '<p><strong>WPAS Error: </strong> ' . $msg . '</p>';
                }
            }
        }

    } // class
} // endif

new WP_Advanced_Search();