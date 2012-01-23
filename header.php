<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
  <meta charset="utf-8">

  <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>

  <meta name="viewport" content="width=device-width,initial-scale=1">

  <?php roots_stylesheets(); ?>

  <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> Feed" href="<?php echo home_url(); ?>/feed/">

  <script src="<?php echo get_template_directory_uri(); ?>/js/libs/modernizr-2.0.6.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="<?php echo get_template_directory_uri(); ?>/js/libs/jquery-1.7.1.min.js"><\/script>')</script>

  <?php wp_head(); ?>
  <?php roots_head(); ?>

</head>

<body <?php body_class(roots_body_class()); ?>>

  <?php roots_wrap_before(); ?>
  <div id="wrap" class="container" role="document">
  <?php roots_header_before(); ?>
    <header id="banner" class="<?php global $roots_options; echo $roots_options['container_class']; ?>" role="banner">
      <?php roots_header_inside(); ?>
      <div class="container">

        <a id="logo" href="<?php echo home_url(); ?>/">
          <img src="http://placehold.it/300x75" width="300" height="75" alt="<?php bloginfo('name'); ?>">
        </a>

        <nav id="nav-main" role="navigation">
          <?php wp_nav_menu(array('theme_location' => 'primary_navigation')); ?>
        </nav>

        <?php if (wp_get_nav_menu_items('Utility Navigation')) { ?>
        <nav id="nav-utility">
          <?php wp_nav_menu(array('theme_location' => 'utility_navigation')); ?>
        </nav>
        <?php } ?>

      </div>
    </header>
  <?php roots_header_after(); ?>
