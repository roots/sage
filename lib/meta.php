<?php

function shoestrap_meta( $context = 'tags' ) {
	$panel_open = '<div class="panel panel-default post-meta-' . $context . '">';
	$panel_head = '<div class="panel-heading">';
	$tags_label = '<i class="el-icon-tags"></i> ' . __( 'Tags:', 'shoestrap' );
	$cats_label = '<i class="el-icon-tag"></i> ' . __( 'Categories:', 'shoestrap' );
	$panel_body = '<div class="panel-body">';
	$label_def  = '<span class="label label-default">';

	if ( $context == 'tags' )
		echo apply_filters( 'shoestrap_the_tags', the_tags( $panel_open . $panel_head . $tags_label . '</div>' . $panel_body . $label_def,
			'</span> ' . $label_def,
			'</span></div></div>'
		) );

	if ( $context == 'cats' && get_the_category_list() )
		echo apply_filters( 'shoestrap_the_cats', $panel_open . $panel_head . $cats_label . '</div>' . $panel_body . $label_def . get_the_category_list( '</span> ' . $label_def ) . '</span></div></div>' );
}