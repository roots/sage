<article <?php post_class(); ?>>
	<?php do_action( 'shoestrap_in_article_top' ); ?>
	<?php shoestrap_title_section( true, 'h2', true ); ?>
	<?php do_action( 'shoestrap_entry_meta' ); ?>
	<div class="entry-summary">
		<?php
		if ( !has_action( 'shoestrap_do_the_excerpt' ) )
			the_excerpt();
		else
			do_action( 'shoestrap_do_the_excerpt' );
		?>
		<div class="clearfix"></div>
	</div>
	<?php
	if ( has_action( 'shoestrap_entry_footer' ) ) :
		echo '<footer class="entry-footer">';
		do_action( 'shoestrap_entry_footer' );
		echo '</footer>';
	endif;
	?>
	<?php do_action( 'shoestrap_in_article_bottom' ); ?>
</article>