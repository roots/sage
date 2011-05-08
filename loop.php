<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if (!have_posts()) : ?>
	<div class="notice">
		<p class="bottom"><?php _e('Sorry, no results were found.', 'roots'); ?></p>
	</div>
	<?php get_search_form(); ?>	
	
<?php endif; ?>

<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<time pubdate datetime="<?php the_time('c'); ?>"><?php printf( __('Posted on %s at %s.','roots'), get_the_time('l, F jS, Y'), get_the_time()) ?></time>
				<?php if (get_option('roots_post_author') == 'checked') { ?>
				<p class="byline author vcard">
					<?php _e('Written by', 'roots');?> <span class="fn"><?php the_author(); ?></span>
				</p>
				<?php } ?>
			</header>
			<div class="entry-content">
	<?php if (is_archive() || is_search()) : // Only display excerpts for archives and search ?>
			<?php the_excerpt(); ?>
	<?php else : ?>
			<?php the_content(); ?>
	<?php endif; ?>
			</div>
			<footer>
				<?php $tag = get_the_tags(); if (!$tag) { } else { ?><p><?php the_tags(); ?></p><?php } ?>
			</footer>
		</article>

		<?php comments_template('', true); ?>

<?php endwhile; // End the loop ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ($wp_query->max_num_pages > 1) : ?>
	<nav id="post-nav">
		<div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'roots' ) ); ?></div>
		<div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'roots' ) ); ?></div>
	</nav>
<?php endif; ?>
