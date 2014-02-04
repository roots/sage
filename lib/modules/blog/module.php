<?php

if ( !function_exists( 'shoestrap_module_blog' ) ) :
function shoestrap_module_blog( $sections ) {

	// Post Meta Options
	$section = array(
		'title' => __( 'Blog', 'shoestrap' ),
		'icon'  => 'el-icon-wordpress icon-large'
	);

	$fields[] = array(
		'id'          => 'shoestrap_entry_meta_config',
		'title'       => __( 'Activate and order Post Meta elements', 'shoestrap' ),
		'options'     => array(
			'tags'    => 'Tags',
			'date'    => 'Date',
			'category'=> 'Category',
			'author'  => 'Author',
			'sticky'  => 'Sticky'
		),
		'type'        => 'sortable',
		'mode'        => 'checkbox'
	);

	// Featured Images Options
	$settings  = get_option( REDUX_OPT_NAME );
	$screen_large_desktop = filter_var( $settings[ 'screen_large_desktop' ], FILTER_SANITIZE_NUMBER_INT );

	$fields[] = array( 
		'id'        => 'help3',
		'title'     => __( 'Featured Images', 'shoestrap' ),
		'desc'      => __( 'Here you can select if you want to display the featured images in post archives and individual posts.
										Please note that these apply to posts, pages, as well as custom post types.
										You can select image sizes independently for archives and individual posts view.', 'shoestrap' ),
		'type'      => 'info',
	);

	$fields[] = array( 
		'title'     => __( 'Featured Images on Archives', 'shoestrap' ),
		'desc'      => __( 'Display featured Images on post archives ( such as categories, tags, month view etc ). Default: OFF.', 'shoestrap' ),
		'id'        => 'feat_img_archive',
		'default'   => 0,
		'type'      => 'switch',
		'customizer'=> true,
	);


	$fields[] = array( 
		'title'     => __( 'Width of Featured Images on Archives', 'shoestrap' ),
		'desc'      => __( 'Set dimensions of featured Images on Archives. Default: Full Width', 'shoestrap' ),
		'id'        => 'feat_img_archive_custom_toggle',
		'default'   => 0,
		'required'  => array('feat_img_archive','=',array('1')),
		'off'       => __( 'Full Width', 'shoestrap' ),
		'on'        => __( 'Custom Dimensions', 'shoestrap' ),
		'type'      => 'switch',
		'customizer'=> true,
	);

	$fields[] = array( 
		'title'     => __( 'Archives Featured Image Custom Width', 'shoestrap' ),
		'desc'      => __( 'Select the width of your featured images on single posts. Default: 550px', 'shoestrap' ),
		'id'        => 'feat_img_archive_width',
		'default'   => 550,
		'min'       => 100,
		'step'      => 1,
		'max'       => $screen_large_desktop,
		'required'  => array('feat_img_archive','=',array('1')),
		'edit'      => 1,
		'type'      => 'slider'
	);

	$fields[] = array( 
		'title'     => __( 'Archives Featured Image Custom Height', 'shoestrap' ),
		'desc'      => __( 'Select the height of your featured images on post archives. Default: 300px', 'shoestrap' ),
		'id'        => 'feat_img_archive_height',
		'default'   => 300,
		'min'       => 50,
		'step'      => 1,
		'edit'      => 1,
		'max'       => $screen_large_desktop,
		'required'  => array('feat_img_archive','=',array('1')),
		'type'      => 'slider'
	);

	$fields[] = array( 
		'title'     => __( 'Featured Images on Posts', 'shoestrap' ),
		'desc'      => __( 'Display featured Images on posts. Default: OFF.', 'shoestrap' ),
		'id'        => 'feat_img_post',
		'default'   => 0,
		'type'      => 'switch',
		'customizer'=> true,
	);

	$fields[] = array( 
		'title'     => __( 'Width of Featured Images on Posts', 'shoestrap' ),
		'desc'      => __( 'Set dimensions of featured Images on Posts. Default: Full Width', 'shoestrap' ),
		'id'        => 'feat_img_post_custom_toggle',
		'default'   => 0,
		'off'       => __( 'Full Width', 'shoestrap' ),
		'on'        => __( 'Custom Dimensions', 'shoestrap' ),
		'type'      => 'switch',
		'required'  => array('feat_img_post','=',array('1')),
		'customizer'=> true,
	);

	$fields[] = array( 
		'title'     => __( 'Posts Featured Image Custom Width', 'shoestrap' ),
		'desc'      => __( 'Select the width of your featured images on single posts. Default: 550px', 'shoestrap' ),
		'id'        => 'feat_img_post_width',
		'default'   => 550,
		'min'       => 100,
		'step'      => 1,
		'max'       => $screen_large_desktop,
		'edit'      => 1,
		'required'  => array('feat_img_post','=',array('1')),
		'type'      => 'slider'
	);

	$fields[] = array( 
		'title'     => __( 'Posts Featured Image Custom Height', 'shoestrap' ),
		'desc'      => __( 'Select the height of your featured images on single posts. Default: 330px', 'shoestrap' ),
		'id'        => 'feat_img_post_height',
		'default'   => 330,
		'min'       => 50,
		'step'      => 1,
		'max'       => $screen_large_desktop,
		'edit'      => 1,
		'required'  => array('feat_img_post','=',array('1')),
		'type'      => 'slider'
	);

	$post_types = get_post_types( array( 'public' => true ), 'names' );
	$post_type_options  = array();
	$post_type_defaults = array();
	foreach ( $post_types as $post_type ) :
		$post_type_options[$post_type]  = $post_type;
		$post_type_defaults[$post_type] = 0;
	endforeach;

	$fields[] = array(
		'title'     => __( 'Disable featured images on single post types', 'shoestrap' ),
		'id'        => 'feat_img_per_post_type',
		'type'      => 'checkbox',
		'options'   => $post_type_options,
		'default'   => $post_type_defaults,
	);

	$fields[] = array( 
		'title'     => __( 'Post excerpt length', 'shoestrap' ),
		'desc'      => __( 'Choose how many words should be used for post excerpt. Default: 40', 'shoestrap' ),
		'id'        => 'post_excerpt_length',
		'default'   => 40,
		'min'       => 10,
		'step'      => 1,
		'max'       => 1000,
		'edit'      => 1,
		'type'      => 'slider'
	);
	
	$fields[] = array( 
		'title'     => __( '"more" text', 'shoestrap' ),
		'desc'      => __( 'Text to display in case of excerpt too long. Default: Continued', 'shoestrap' ),
		'id'        => 'post_excerpt_link_text',
		'default'   => __( 'Continued', 'roots' ),
		'type'      => 'text'
	);

	$fields[] = array( 
		'title'     => __( 'Select pagination style', 'shoestrap' ),
		'desc'      => __( 'Switch between default pager or default pagination. Default: Pager.', 'shoestrap' ),
		'id'        => 'pagination',
		'type'      => 'button_set',
		'options'   => array(
			'pager'       => 'Default Pager',
			'pagination'  => 'Default Pagination'
		),
		'default'   => 'pager',
		'customizer'=> array()
	);

	$fields[] = array( 
		'title'     => __( 'Show Breadcrumbs', 'shoestrap' ),
		'desc'      => __( 'Display Breadcrumbs. Default: OFF.', 'shoestrap' ),
		'id'        => 'breadcrumbs',
		'default'   => 0,
		'type'      => 'switch',
		'customizer'=> array(),
	);

	$section['fields'] = $fields;
	$section = apply_filters( 'shoestrap_module_blog_modifier', $section );
	$sections[] = $section;

	return $sections;
}
endif;
add_filter( 'redux/options/'.REDUX_OPT_NAME.'/sections', 'shoestrap_module_blog', 75 );   

include_once( dirname( __FILE__ ) . '/functions.metaconfig.php' );
include_once( dirname( __FILE__ ) . '/resize.php' );
include_once( dirname( __FILE__ ) . '/functions.images.php' );
include_once( dirname( __FILE__ ) . '/functions.advanced.php' );
include_once( dirname( __FILE__ ) . '/functions.breadcrumb.php' );

add_filter( 'shoestrap_compiler', 'shoestrap_admin_blog_styles' );
function shoestrap_admin_blog_styles( $bootstrap ) {
	return $bootstrap . '
	@import "' . get_template_directory() . '/lib/modules/blog/styles.less";';
}