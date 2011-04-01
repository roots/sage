<!doctype html>
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">

	<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
	
	<meta name="viewport" content="width=device-width; initial-scale=1.0">

	<?php echo get_roots_css_framework_stylesheets(); ?>
	
	<?php if (class_exists('RGForms')) { ?>
		<link rel="stylesheet" href="<?php echo plugins_url(); ?>/gravityforms/css/forms.css">
	<?php } ?>
	
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/style.css">
	
	<?php if(!IS_960_GS) { ?>
		<!--[if lt IE 8]><link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/blueprint/ie.css"><![endif]-->
	<?php } ?>
	
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> Feed" href="<?php site_url(); ?>/feed/">

	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/libs/modernizr-1.7.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	<script>window.jQuery || document.write("<script src='<?php echo get_stylesheet_directory_uri(); ?>/js/libs/jquery-1.5.1.min.js'>\x3C/script>")</script>

	<?php wp_head(); ?>

	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/scripts.js"></script>
	<?php if (get_option('roots_google_analytics') !== "") { ?>
		<script>
			var _gaq=[["_setAccount","<?php echo get_option('roots_google_analytics') ?>"],["_trackPageview"]];
			(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
			g.src=("https:"==location.protocol?"//ssl":"//www")+".google-analytics.com/ga.js";
			s.parentNode.insertBefore(g,s)}(document,"script"));
		</script>
	<?php } ?>
</head>
<body <?php $page_slug = $post->post_name; body_class($page_slug); ?>>
	<div id="wrap" class="container" role="document">
		<header id="banner" class="<?php echo CONTAINER_CLASS; ?>" role="banner">
			<div class="container">
				<a id="logo" href="<?php site_url(); ?>/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo.png" width="300" height="75" alt="<?php bloginfo('name'); ?>"></a>
				<nav id="nav-main" class="<?php echo CONTAINER_CLASS; ?>" role="navigation">
					<?php wp_nav_menu(array('theme_location' => 'primary_navigation')); ?>
				</nav>
				<nav id="nav-utility">
					<?php wp_nav_menu(array('theme_location' => 'utility_navigation')); ?>
				</nav>				
			</div>
			<?php echo get_roots_960gs_cleardiv() ?>
		</header>

		
