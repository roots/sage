<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>

	<?php if (function_exists('yoast_breadcrumb')) { if (is_page() && $post->post_parent) { yoast_breadcrumb('<p id="breadcrumbs">','</p>'); } } ?>
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
	<?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>' )); ?>

<?php endwhile; // End the loop ?>

