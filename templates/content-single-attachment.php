<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <a href="<?= wp_get_attachment_image_src($post->id, 'full')[0] ?>">
        <img src="<?= wp_get_attachment_image_src($post->id, 'full')[0] ?>" class="img-responsive" />
      </a>
      <a href="<?= get_permalink($post->post_parent) ?>" class="btn btn-lg btn-primary">
        <i class="glyphicon glyphicon-chevron-left"></i> Back to <?= get_the_title($post->post_parent) ?>
      </a>
    </header>
    <div class="entry-content">
    </div>
    <?php comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
