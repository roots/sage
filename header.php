<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="utf-8">

	<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php echo get_roots_stylesheets(); ?>
	
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> Feed" href="<?php echo home_url(); ?>/feed/">

	<script src="<?php echo get_template_directory_uri(); ?>/js/libs/modernizr-2.0.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/libs/respond.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo get_template_directory_uri(); ?>/js/libs/jquery-1.6.1.min.js">\x3C/script>')</script>

	<?php wp_head(); ?>
	<?php roots_head(); ?>

	<script src="<?php echo get_template_directory_uri(); ?>/js/scripts.js"></script>
	<?php
		global $roots_options;
		$google_analytics_id = $roots_options['google_analytics_id'];
		if ($google_analytics_id !== '') { ?>

	<script>
		var _gaq=[['_setAccount','<?php echo esc_attr($roots_options['google_analytics_id']); ?>'],['_trackPageview'],['_trackPageLoadTime']];
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
		g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>
<?php } ?>
</head>
<body <?php $page_slug = $post->post_name; body_class($page_slug); ?>>
	<?php roots_wrap_before(); ?>
	<div id="wrap" class="container" role="document">
	<?php roots_header_before(); ?>
		<header id="banner" class="<?php global $roots_options; echo $roots_options['container_class']; ?>" role="banner">
			<?php roots_header_inside(); ?>
			<div class="container">
				<a id="logo" href="<?php echo home_url(); ?>/"><img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" width="300" height="75" alt="<?php bloginfo('name'); ?>"></a>
        <?php if ($roots_options['clean_menu']) { ?>
				<nav id="nav-main" role="navigation">
          <?php wp_nav_menu(array('theme_location' => 'primary_navigation', 'walker' => new roots_nav_walker())); ?>
				</nav>
				<nav id="nav-utility">
          <?php wp_nav_menu(array('theme_location' => 'utility_navigation', 'walker' => new roots_nav_walker())); ?>
				</nav>				
        <?php } else { ?>
				<nav id="nav-main" role="navigation">
          <?php wp_nav_menu(array('theme_location' => 'primary_navigation')); ?>
				</nav>
				<nav id="nav-utility">
          <?php wp_nav_menu(array('theme_location' => 'utility_navigation')); ?>
				</nav>				
        <?php } ?>
			</div>
		</header>
	<?php roots_header_after(); ?>
