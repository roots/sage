<?php get_header(); ?>
  <?php roots_content_before(); ?>
    <div id="content" class="<?php echo CONTAINER_CLASSES; ?>">
    <?php roots_main_before(); ?>
      <div id="main" class="<?php echo MAIN_CLASSES; ?>" role="main">
        <div class="page-header">
          <h1>
            <?php
              $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
              if ($term) {
                echo $term->name;
              } elseif (is_post_type_archive()) {
                echo get_queried_object()->labels->name;
              } elseif (is_day()) {
                printf(__('Daily Archives: %s', 'roots'), get_the_date());
              } elseif (is_month()) {
                printf(__('Monthly Archives: %s', 'roots'), get_the_date('F Y'));
              } elseif (is_year()) {
                printf(__('Yearly Archives: %s', 'roots'), get_the_date('Y'));
              } elseif (is_author()) {
                global $post;
                $author_id = $post->post_author;
                printf(__('Author Archives: %s', 'roots'), get_the_author_meta('display_name', $author_id));
              } else {
                single_cat_title();
              }
            ?>
          </h1>
        </div>
        <?php roots_loop_before(); ?>
        <?php get_template_part('loop', 'category'); ?>
        <?php roots_loop_after(); ?>
      </div><!-- /#main -->
    <?php roots_main_after(); ?>
    <?php roots_sidebar_before(); ?>
      <aside id="sidebar" class="<?php echo SIDEBAR_CLASSES; ?>" role="complementary">
      <?php roots_sidebar_inside_before(); ?>
        <?php get_sidebar(); ?>
      <?php roots_sidebar_inside_after(); ?>
      </aside><!-- /#sidebar -->
    <?php roots_sidebar_after(); ?>
    </div><!-- /#content -->
  <?php roots_content_after(); ?>
<?php get_footer(); ?>