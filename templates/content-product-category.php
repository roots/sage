<?php
  global $post;
?>
<div class="row">
  <div class="col-lg-12">
    <?php echo category_description(); ?>
  </div>
</div>
<?php
//get all categories then display all posts in each term
$taxonomy = 'product-category';
$param_type = 'category__in';
$term_args=array(
  'orderby' => 'name',
  'order' => 'ASC'
);
$terms = get_terms($taxonomy,$term_args);
if ($terms) {
  foreach( $terms as $term ) {
    $args=array(
      "$param_type" => array($term->term_id),
      'post_type' => 'product',
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'caller_get_posts'=> 1
      );
    $my_query = null;
    $my_query = new WP_Query($args);
    if( $my_query->have_posts() ) {  ?>
      <div class="category section">
	    <h3><?php echo .$term->name;?></h3>
  	    <div class="row">
          <ul class="thumbnails">
    	    <?php
          while ($my_query->have_posts()) : $my_query->the_post(); ?>
            <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
           <?php
          endwhile;
          ?>
          </ul>
        </div>
      </div>
 <?php
    }
  }
}
wp_reset_query();  // Restore global post data stomped by the_post().
?>