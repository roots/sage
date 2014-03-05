<?php

function shoestrap_meta( $context = 'tags' ) {
	global $ss_framework;

	$panel_open       = $ss_framework->open_panel( 'post-meta-' . $context );
	$panel_close      = $ss_framework->close_panel();
	$panel_head_open  = $ss_framework->open_panel_heading();
	$panel_head_close = $ss_framework->close_panel_heading();
	$tags_label       = '<i class="el-icon-tags"></i> ' . __( 'Tags:', 'shoestrap' );
	$cats_label       = '<i class="el-icon-tag"></i> ' . __( 'Categories:', 'shoestrap' );
	$panel_body_open  = $ss_framework->open_panel_body();
	$panel_body_close = $ss_framework->close_panel_body();
	$label_def        = '<span class="label label-tag">';

	if ( $context == 'tags' && get_the_tag_list() ) {
		echo apply_filters( 'shoestrap_the_tags', get_the_tag_list( $panel_open . $panel_head_open . $tags_label . $panel_head_close . $panel_body_open . $label_def,
			'</span> ' . $label_def,
			'</span>' . $panel_body_close . $panel_close
		) );
	}

	if ( $context == 'cats' && get_the_category_list() ) {
		echo apply_filters( 'shoestrap_the_cats', $panel_open . $panel_head_open . $cats_label . $panel_head_close . $panel_body_open . $label_def . get_the_category_list( '</span> ' . $label_def ) . '</span>' . $panel_body_close . $panel_close );
	}
}