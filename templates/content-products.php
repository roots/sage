<?php get_template_part('templates/page', 'header'); ?>
<?php
global $post;
$posttype = get_post_type( get_the_ID() );

$terms = get_terms('product_cat');
$posttype = 'product';
foreach ($terms as $term) { ?>
<div class="well">
  <section title="<?php echo $term->name;?>" id="product-category-<?php echo $term->slug;?>" class="product-category product-category-<?php echo $term->slug;?>">
  <?php
  $wpq = array ('post_type' => $posttype, 'taxonomy'=>'product_cat','term'=>$term->slug, 'orderby' => 'menu_order title', 'order' => 'ASC');
  $myquery = new WP_Query ($wpq);
  $article_count = $myquery->post_count;
  echo "<div class=\"row\">";
  echo "<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12\"><h2 class=\"term-heading\" id=\"".$term->slug."\">";
  echo $term->name;
  echo "</h2></div>";
  if ($article_count) { ?>
    <ul class="thumbnails">
    <?php while ($myquery->have_posts()) : $myquery->the_post(); ?>
      <li class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
        <div class="">
          <article <?php post_class(); ?>>
           <header>
    
           </header>          
            
        <a title="<?php the_title(); ?>" class="thumbnail thumbnail-<?php the_id(); ?>" id="product-<?php the_id(); ?>" href="<?php the_permalink(); ?>">
          <div class="entry-summary">
              <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'small-tall', array('class' => 'img-responsive')); } else { ?><img src="http://placehold.it/180x210"/><?php } ?>
          </div>
        </a>
        <footer>
          <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
        </footer>
          </article>
        </div>
      </li>
    <?php endwhile; ?>
</ul>
<?php } ?>
  </div>
</section>
</div>
<?php } ?>










