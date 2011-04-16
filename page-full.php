<?php
/*
Template Name: Full Width
*/
get_header(); ?>
		<div id="content" class="<?php echo roots_container_class; ?>">	
			<div id="main" class="<?php echo roots_container_class; ?>" role="main">
				<div class="container">
					<?php get_template_part('loop', 'page'); ?>
				</div>
			</div><!-- /#main -->
		</div><!-- /#content -->
<?php get_footer(); ?>
