<header class="banner" role="banner">
  <div class="container">
    <a class="brand" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
    <?php if (is_home()): ?>
      <h2 class="brand-description"><?= get_bloginfo('description') ?></h2>
    <?php endif; ?>
  </div>
</header>
