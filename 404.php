<?php get_header(); ?>
		<div id="content" class="span-24">
			<div id="main" role="main">
				<div class="container">
					<h1>File Not Found</h1>
					<div class="error">
						<p class="bottom">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
					</div>
					<p>Please try the following:</p>
					<ul> 
						<li>Check your spelling</li>
						<li>Return to the <a href="<?php echo home_url(); ?>/">home page</a></li> 
						<li>Click the <a href="javascript:history.back()">Back</a> button</li>
					</ul>
				</div>
			</div><!-- /#main -->
		</div><!-- /#content -->
<?php get_footer(); ?>
