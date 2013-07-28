<?php
// This is a base for custom post type classes so they can all take advantage of the same
// logic for meta, transients etc...

// you should of course overwrite whatever you need to in your class

class MDG_Type_Base extends MDG_Generic {

	public $transient_title  = 'undefined';  // REQUIRED! for caching (you should override this)
	public $transient_expiry = '';           // will set on construct
	public $posts            = array();      // will hold array of post objects
	public $post_type        = '';           // REQUIRED! slug
	public $post_type_title  = '';           // REQUIRED! title of post type
	public $post_type_single = '';           // REQUIRED! singular title
	public $post_type_id     = '';           // id...should be the slug with underscore instead of hyphen
	public $query_limit      = -1;           // you should probably override this also
	public $supports         = array();      // what the post type supports (set dynamically)
	public $meta_helper      = '';           // we'll dynamically set this to hold the meta helper class

	public function __construct() {
		// Carefull what you put here!!
		// this class will self instantiate so we don't want
		// to slow down other areas of the site

		// first make sure the sub class has the required properties
		if ( $this->passed_config_test() ) {

			$this->meta_helper = new MDG_Meta_Helper();

			// set expiry
			$this->transient_expiry = 3 * HOUR_IN_SECONDS;

			// set post type id (slug with underscore instead of hyphen)
			$this->post_type_id = str_replace( '-', '_', $this->post_type );

			// set what the post type will support
			$this->set_supports();

			// hook to create post_type
			add_action( 'init', array( $this, 'make_post_type' ) );

			// hook into save post to reset cache
			add_action( 'save_post', array( $this, 'reset_transient' ) );

			// hook into save post again to save the custom meta
			add_action( 'save_post', array( $this, 'save_meta' ) );

			// meta box hook
			add_action( 'add_meta_boxes', array( $this, 'make_meta_box' ) );

			$this->mdg_init();

		} // end if passed_config_test
	}

	public function mdg_init() {
		// this will run after __construct(). just a way for sub classes
		// to run custom __construct stuff while still inheriting the construct from this class
	}

	public function passed_config_test() {
		// TODO: make something like the following to prevent simple errors
		// this wasn't working so for now, just returning true
		return true;

		// make sure that your subclass has the properties it needs
		// or output errors

		/*
		$errors = array();

		$required = array(
			$this->post_type,
			$this->post_type_title,
			$this->post_type_single
		);

		foreach( $required as $item ){

			if( !$item ){
				$this_error = '<br/>oops it looks like you forgot something in your post type class!';
				array_push($errors, $this_error);
			}
		}

		if( empty($errors) ){
			return true;
		} else {
			foreach( $errors as $error){
				echo $error;
			}
		}
		*/
	}

	public function set_supports() {
		$this->supports = array(
			'title',
			'editor',
			'post-thumbnails',
			'custom-fields',
			'page-attributes',
			'author',
			'thumbnail',
			'excerpt'
		);
	}

	public function make_post_type() {
		// this guy will make the actual post type
		// feel free to override it if you need to

		// make sure the post type info is set - none of this will work without it!
		if ( $this->post_type && $this->post_type_title && $this->post_type_single ) {
			$post_type_args = array(
				'label'    => __( $this->post_type_title ),
				'singular_label'  => __( $this->post_type_single ),
				'public'    => true,
				'show_ui'    => true,
				'menu_position'  => 5,
				'capability_type'  => 'post',
				'hierarchical'   => false,
				'publicly_queryable'=> true,
				'query_var'   => true,
				'rewrite'    => array( 'slug' => $this->post_type, 'with_front' => false ),
				'can_export'   => true,
				'supports'    => $this->supports
			);
			register_post_type( $this->post_type, $post_type_args );

			register_taxonomy(
				$this->post_type."-categories",
				array( $this->post_type ),
				array(
					"hierarchical"  => true,
					"label"   => "Categories",
					"singular_label"=> "Category",
					"rewrite"  => true
				)
			);

		}
	}

	public function reset_transient() {
		// seems easiest to just delete the transient and call set_projects()
		// which will set it since it will no longer exist...since we removed it
		// you get the idea ;)
		delete_transient( $this->transient_title );
		$this->set_posts();
	}

	public function set_posts() {

		$transient = get_transient( $this->transient_title );

		if ( $transient ) {

			$posts = $transient;

		} else {

			$q = new WP_Query( array(
					'post_type'      => $this->post_type,
					'posts_per_page' => $this->query_limit
				) );

			$posts = $q->get_posts();

			// set transient (cache)
			set_transient( $this->transient_title, $posts, $this->transient_expiry );

		} // end if transient

		$this->posts = $posts;
	}

	public function mdg_get_posts() {
		// Maybe keep this method prefixed just to avoid confusion...?

		// setup posts if they haven't been yet
		if ( empty( $this->posts ) ) {
			$this->set_posts();
		}

		return $this->posts;
	}

	public function get_custom_meta_fields() {
		// override me to create custom meta fields
		// By returning false in this method, we're telling the class to not to
		// do anything will custom meta (e.g. meta boxes, and saving meta etc...)

		/* should return an array like
		return array(
			array(
				'label'		=> 'Title/Position',
				'desc'		=> '',
				'id'		=> $prefix.'Title',
				'type'		=> 'text'
			),
			array(
				'label'		=> 'Quote',
				'desc'		=> '',
				'id'		=> $prefix.'Quote',
				'type'		=> 'textarea'
			)
		);
		*/

		return false;

	}

	public function make_meta_box() {
		if ( $this->get_custom_meta_fields() ) {
			add_meta_box(
				$this->post_type_id.'_meta_box',       // $id
				'Details',                   // $title
				array( $this, 'show_meta_box' ),        // $callback
				$this->post_type,                      // $page
				'normal',                              // $context
				'high'                                 // $priority
			);
		}
	}

	public function show_meta_box() {
		if ( $this->get_custom_meta_fields() ) {
			global $post;
			$this->meta_helper->mdg_make_form( array( 'meta_fields' => $this->get_custom_meta_fields() ) );
		}
	}

	public function save_meta( $post_id ) {
		if ( $this->get_custom_meta_fields() ) {
			$this->meta_helper->save_custom_meta( array(
					'post_id'    => $post_id,
					'custom_meta_fields' => $this->get_custom_meta_fields()
				) );
		}
	}

	public function make_dummy_content( $post_type, $title, $count ) {
		// manipulate and use this to create dummy content

		// sample usage...
		// $mdg_generic->make_dummy_content( 'project', 'Sample Project ' 20 );

		global $user_ID;

		for ( $i = 1; $i <= $count; $i++ ) {

			$text = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';

			// add an extra paragraph here and there
			$text = $i % 3 ? $text . '<br/><br/>' . $text : $text;

			$new_post = array(
				'post_title'    => $title.$i,
				'post_content'  => $text,
				'post_status'   => 'publish',
				'post_date'     => date( 'Y-m-d H:i:s' ),
				'post_author'   => $user_ID,
				'post_type'     => $post_type,
				'post_category' => array( 0 )
			);

			$post_id = wp_insert_post( $new_post );
		}

	}

	public function print_grid( $posts = array() ) {
		// pass me an array of post objects (projects)
		// and i'll output the project grid...

		// this method will be handy to ensure that the markup from
		// any ajax is the same as the original
		$html             = '';

		$html .= '<div class="masonry-grid image-grid">';
		$html .= '<ul>';
		$i = 1;
		foreach ( $posts as $post ) {
			$attached_service = '';
			$attached_service = get_post_meta( $post->ID, 'projectService', true );

			$src              = wp_get_attachment_image_src( get_post_thumbnail_id(  $post->ID ), '220x130', false, '' );

			// turn services into array
			$attached_services = explode( ',', $attached_service );

			if ( is_array( $attached_services ) ) {
				$services = '';
				foreach ( $attached_services as $service_id ) {
					$service_post = '';
					$service_post = get_post( $service_id );
					$services    .= ' filter-service-'.$service_post->post_name;
				}
			} else {
				$attached_service = get_post( $service_id );
				$services = ' filter-service-'.$attached_service->post_name;
			}

			$class = $i % 3 ? 'one' : 'two';
			$html .= '<li class="'.$class.'">';
			$html .= '<div class="image">';
			$html .= '<div class="image lazy-image" data-image-url="'.$src[0].'">';
			$html .= '<a href="'.get_permalink( $post->ID ).'">';
			$html .= '<noscript><img src="'.$src[0].'" alt="'.get_the_title( $post->ID ).'" /></noscript>';
			$html .= '</a>';
			$html .= '</div>'; // lazy-load
			$html .= '</div>'; // end image

			$html .= '<div class="title">';
			$html .= $post->post_title;
			$html .= '</div>';
			$html .= '</li>';

			$i++;
		}

		$html .= '</ul>';

		$html .= '</div><!-- masonry-grid -->';


		return $html;

	}

	public function get_posts_with_featured_images() {
		// TODO: setup transient for this guy!!!
		$q = new WP_Query( array(
				'post_type'      => $this->post_type,
				'meta_key'       => '_thumbnail_id',
				'posts_per_page' => 20
			) );

		return $q->get_posts();
	}

}

// self instantiate so hooks can do their thing
$GLOBALS['MDG_Type_Base'] = new MDG_Type_Base();
