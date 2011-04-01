<?php
/*
Template Name: Full Width
*/
get_header(); ?>
		<div id="content" class="<?php echo CONTAINER_CLASS; ?>">	
			<div id="main" class="<?php echo CONTAINER_CLASS; ?>" role="main">
				<div class="container">
					<?php get_template_part('loop', 'page'); ?>
				</div>
			</div><!-- /#main -->
		</div><!-- /#content -->
<?php get_footer(); ?>
