<header class="mdl-layout__header">
  <div class="mdl-layout__header-row">
    <!-- Title -->
    <a class="mdl-layout-title" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
    <!-- Add spacer, to align navigation to the right -->
    <div class="mdl-layout-spacer"></div>
    <!-- Navigation -->
    <?php
    if ( has_nav_menu( 'primary_navigation' ) ) :

      // Remove wrapping <li> from around links
      // https://css-tricks.com/snippets/wordpress/remove-li-elements-from-output-of-wp_nav_menu/#comment-542093
      $cleanermenu = wp_nav_menu( array(
        'theme_location' => 'primary_navigation',
        'container' => false,
        'items_wrap' => '<nav class="mdl-navigation">%3$s</nav>',
        'echo' => false,
        'depth' => 1,
      ) );
      $find = array('><a','li');
      $replace = array('','a');
      echo str_replace( $find, $replace, $cleanermenu );

    endif;
    ?>

  </div>
</header>
<div class="mdl-layout__drawer">
  <a class="mdl-layout-title" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>

  <?php
  if ( has_nav_menu( 'primary_navigation' ) ) :

    // Remove wrapping <li> from around links
    // https://css-tricks.com/snippets/wordpress/remove-li-elements-from-output-of-wp_nav_menu/#comment-542093
    $cleanermenu = wp_nav_menu( array(
      'theme_location' => 'primary_navigation',
      'container' => false,
      'items_wrap' => '<nav class="mdl-navigation">%3$s</nav>',
      'echo' => false,
      'depth' => 1,
    ) );
    $find = array('><a','li');
    $replace = array('','a');
    echo str_replace( $find, $replace, $cleanermenu );

  endif;
  ?>

</div>
