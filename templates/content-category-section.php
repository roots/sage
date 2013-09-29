<?php
  global $categoryid;
  global $posttype;
  global $span;

while(has_sub_field("category_template_row")): ?>

	<?php if(get_row_layout() == "headline"): ?>
  <div class="row">
		<div class="col-lg-12">
			<h3><?php the_sub_field("headline_text"); ?></h3>
		</div>
  </div>

	<?php elseif(get_row_layout() == "text_area"): ?>
  <div class="row">
		<div class="col-lg-12">
			<?php the_sub_field("text_area"); ?>
		</div>
  </div>

	<?php elseif(get_row_layout() == "items"): ?>
  <div class="row">
  		<div>
<?php

$post_objects = get_field('item');

if( $post_objects ): ?>
    <ul class="thumbnails">
    <?php foreach( $post_objects as $post_object): ?>
        				  <li class="<?php echo $span ?>">
        				  <div class="">
                  <article <?php post_class($post_object->ID); ?>>
                    <header>
                    </header>
        					 <a title="<?php echo get_the_title($post_object->ID); ?>" class="thumbnail thumbnail-<?php get_the_id($post_object->ID); ?>" id="resource-<?php get_the_id($post_object->ID); ?>" href="<?php echo the_field('document', $post_object->ID); ?><?php echo the_field('link', $post_object->ID); ?><?php echo the_field('page', $post_object->ID); ?>">
                      <div class="entry-summary">
                       <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'small-tall' ); } else { ?><img src="http://placehold.it/180x210"/><?php } ?>
                      </div>
                    </a>
                    <footer>
                      <h4><a href="<?php echo the_field('document', $post_object->ID); ?><?php echo the_field('link', $post_object->ID); ?><?php echo the_field('page', $post_object->ID); ?>"><?php get_the_title($post_object->ID); ?></a></h4>
                    </footer>
                  </article>
                  </li>
    <?php endforeach; ?>
    </ul>
<?php endif;

?>
  		</div>
  </div>
	<?php endif; ?>

<?php endwhile; ?>
<?php wp_reset_postdata(); ?>



