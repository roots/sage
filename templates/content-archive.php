<?php get_template_part('templates/page', 'header'); ?>
<?php
global $post;
$posttype = get_post_type( get_the_ID() );

$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$label = get_query_var( 'taxonomy' );
$termm = get_query_var( 'term' );

$tax_query = array( 'relation' => 'IN', array( 'taxonomy' => $label, 'field' => 'slug', 'terms' => array( $termm ) ));

$args = array('posts_per_page' => -1, 'orderby'=> 'title', 'order' => 'ASC', 'post_type' => $posttype, 'tax_query' => $tax_query );
$posts = get_posts( $args );

?>
<div class="row">
  <div class="col-lg-12">
		<?php if ( !empty( $term->description ) ): ?>
		<div class="archive-description">
		<p><?php echo esc_html($term->description); ?></p>
		</div>
		<?php endif; ?>
  </div>
</div>

<div class="row">
<ul class="thumbnails">
<?php foreach( $posts as $post ) :	setup_postdata($post); ?>
 <li class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
   <div class="">
     <article <?php post_class($post->ID); ?>>
       <header>
         
       </header>
        <a title="<?php echo get_the_title($post->ID); ?>" class="thumbnail thumbnail-<?php echo get_the_id(); ?>" id="archive-<?php echo get_the_id(); ?>" href="<?php echo get_permalink($post->ID);?>">
          <div class="entry-summary">
              <?php if ( has_post_thumbnail( get_the_id($post->ID) ) ) { the_post_thumbnail( 'small-tall' ); } else { ?><img src="http://placehold.it/180x210"/><?php } ?>
          </div>
        </a>
        <footer>
          <h4><a href="<?php echo get_permalink($post->ID);?>"><?php echo get_the_title($post->ID); ?></a></h4>
          <p><?php echo the_excerpt($post->ID); ?></p>
        </footer>
      </article>
    </div>
  </li>
<?php endforeach; ?>
</ul>
</div>
		
