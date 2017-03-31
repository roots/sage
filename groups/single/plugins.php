<?php get_header( 'buddypress' ) ?>

	<?php roots_content_before(); ?>
    <div id="content" class="<?php echo CONTAINER_CLASSES; ?>">
    <?php roots_main_before(); ?>
        <div id="main" class="<?php echo MAIN_CLASSES; ?>" role="main">

			<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

			<?php do_action( 'bp_before_group_plugin_template' ) ?>

			<div id="item-header">
				<?php locate_template( array( 'groups/single/group-header.php' ), true ) ?>
			</div><!-- #item-header -->

			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
					<ul>
						<?php bp_get_options_nav() ?>

						<?php do_action( 'bp_group_plugin_options_nav' ) ?>
					</ul>
				</div>
			</div><!-- #item-nav -->

			<div id="item-body">

				<?php do_action( 'bp_before_group_body' ) ?>

				<?php do_action( 'bp_template_content' ) ?>

				<?php do_action( 'bp_after_group_body' ) ?>
			</div><!-- #item-body -->

			<?php do_action( 'bp_after_group_plugin_template' ) ?>

			<?php endwhile; endif; ?>

      </div><!-- /#main -->
    <?php roots_main_after(); ?>
    <?php roots_sidebar_before(); ?>
      <aside id="sidebar" class="<?php echo SIDEBAR_CLASSES; ?>" role="complementary">
      <?php roots_sidebar_inside_before(); ?>
        <?php get_sidebar( 'buddypress' ); ?>
      <?php roots_sidebar_inside_after(); ?>
      </aside><!-- /#sidebar -->
    <?php roots_sidebar_after(); ?>
    </div><!-- /#content -->
  <?php roots_content_after(); ?>

<?php get_footer( 'buddypress' ) ?>