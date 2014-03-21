<?php roots_html_before(); ?>
<html><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
  <?php roots_head_top(); ?>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php wp_title('|', true, 'right'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php wp_head(); ?>

  <link rel="alternate" type="application/rss+xml" title="<?php echo get_bloginfo('name'); ?> Feed" href="<?php echo esc_url(get_feed_link()); ?>">
  <?php roots_head_bottom(); ?>
</head>
