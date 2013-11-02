<?php

/**
 * Search Loop - Single Forum
 *
 * @package bbPress
 * @subpackage Theme
 */

?>
<div class="bbp-forum-header">
	<div class="bbp-meta well well-sm">
		<span class="bbp-forum-post-date"><?php printf( __( 'Last updated %s', 'bbpress' ), bbp_get_forum_last_active_time() ); ?></span>
		<a href="<?php bbp_forum_permalink(); ?>" class="bbp-forum-permalink pull-right">#<?php bbp_forum_id(); ?></a>
	</div><!-- .bbp-meta -->
	<div class="bbp-forum-title">
		<?php do_action( 'bbp_theme_before_forum_title' ); ?>
		<h4><?php _e( 'Forum: ', 'bbpress' ); ?><a href="<?php bbp_forum_permalink(); ?>"><?php bbp_forum_title(); ?></a></h4>
		<?php do_action( 'bbp_theme_after_forum_title' ); ?>
	</div><!-- .bbp-forum-title -->
</div><!-- .bbp-forum-header -->
<div id="post-<?php bbp_forum_id(); ?>" <?php bbp_forum_class(); ?>>
	<div class="bbp-forum-content col-md-12">
		<?php do_action( 'bbp_theme_before_forum_content' ); ?>
		<?php bbp_forum_content(); ?>
		<?php do_action( 'bbp_theme_after_forum_content' ); ?>
	</div><!-- .bbp-forum-content -->
</div><!-- #post-<?php bbp_forum_id(); ?> -->