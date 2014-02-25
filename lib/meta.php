<?php

function shoestrap_meta( $context = 'tags' ) {
	global $ss_framework;

	$panel_open = $ss_framework->make_panel( 'post-meta-' . $context );
	$panel_head = $ss_framework->make_panel_heading();
	$tags_label = '<i class="el-icon-tags"></i> ' . __( 'Tags:', 'shoestrap' );
	$cats_label = '<i class="el-icon-tag"></i> ' . __( 'Categories:', 'shoestrap' );
	$panel_body = $ss_framework->make_panel_body();
	$label_def  = '<span class="label label-tag">';

	if ( $context == 'tags' && get_the_tag_list() )
		echo apply_filters( 'shoestrap_the_tags', get_the_tag_list( $panel_open . $panel_head . $tags_label . '</div>' . $panel_body . $label_def,
			'</span> ' . $label_def,
			'</span></div></div>'
		) );

	if ( $context == 'cats' && get_the_category_list() )
		echo apply_filters( 'shoestrap_the_cats', $panel_open . $panel_head . $cats_label . '</div>' . $panel_body . $label_def . get_the_category_list( '</span> ' . $label_def ) . '</span></div></div>' );
}