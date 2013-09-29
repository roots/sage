<?php
  global $post;
?>
<div class="row">
  <div class="col-lg-12">
    <?php echo category_description(); ?>
  </div>
</div>

<div class="row">
<ul class="thumbnails">
<?php
$posttype = get_post_type( get_the_ID() );
$args = array('posts_per_page' => -1, 'orderby'=> 'title', 'order' => 'ASC', 'post_type' => $posttype);
$resources = get_posts( $args );

foreach( $resources as $post ) :	setup_postdata($post);
 ?>
        				  <li class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
        				  <div class="">
                  <article <?php post_class($post->ID); ?>>
                    <header>
                    </header>
        					 <a title="<?php echo get_the_title($post->ID); ?>" class="thumbnail thumbnail-<?php echo get_the_id(); ?>" id="resource-<?php echo get_the_id(); ?>" href="<?php echo the_field('document', $post->ID); ?><?php echo the_field('link', $post->ID); ?><?php echo the_field('page', $post->ID); ?>">
                      <div class="entry-summary">
                       <?php if ( has_post_thumbnail( get_the_id($post->ID) ) ) { the_post_thumbnail( 'small-tall' ); } else { ?><img src="http://placehold.it/180x210"/><?php } ?>
                      </div>
                    </a>
                    <footer>
                      <h4><a href="<?php echo the_field('document', $post->ID); ?><?php echo the_field('link', $post->ID); ?><?php echo the_field('page', $post->ID); ?>"><?php echo get_the_title($post->ID); ?></a></h4>
                    </footer>
                  </article>
        				  </div>
                  </li>
<?php endforeach; ?>
</ul>
</div>
		
