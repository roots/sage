<?php
  $posttype = 'resource';
  $span = 'span3';
  $args = array(
    'post_type' => $posttype,
    'category_name' => 'formulas-conversions-calculators',
  );
  $formulasconversions = new WP_Query($args);
?>

<div class="row-fluid">
  <section id="library">
    <h2>Formulas, Conversions & Calculators</h2>
    <ul class="thumbnails">
    <?php if ($formulasconversions->have_posts()) : ?>
    <?php while ($formulasconversions->have_posts()) : $formulasconversions->the_post(); ?>
      <?php get_template_part('templates/styles', 'resources'); ?>
      <li class="<?php echo $span ?>">
      <div class="">
      <article <?php post_class(); ?>>
        <header>
          
        </header>
        <a class="thumbnail" href="<?php the_permalink(); ?>"><div class="entry-summary">
         <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'small-tall' ); } ?>
        </div></a>
        <footer>
          <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
          <?php // the_tags('<ul class="entry-tags"><li>','</li><li>','</li></ul>'); ?>
        </footer>
      </article>
      </div>
      </li>
    <?php endwhile; ?>
    <?php endif; ?>
    </ul>
  </section>
</div>

<?php wp_reset_postdata(); ?>