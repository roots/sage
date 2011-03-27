<?php
/*
Template Name: Full Width
*/
get_header(); ?>
		<div id="content" class="span-24">	
			<div id="main" class="span-24" role="main">
				<div class="container">
					<?php get_template_part('loop', 'page'); ?>
				</div>
			</div><!-- /#main -->
		</div><!-- /#content -->
<?php get_footer(); ?>
