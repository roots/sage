<?php
$args = array();
$args['wp_query'] = array('post_type' => 'shopp_products',
                          'posts_per_page' => 50);
$args['fields'][] = array('type' => 'search',
                          'title' => 'Search',
                          'value' => '');
$args['fields'][] = array('type' => 'taxonomy',
                          'format'    =>  'checkbox',
                          ''    =>    '');
$args['fields'][] = array('type' => 'taxonomy',
                          'format'    =>  'checkbox',
                          'taxonomy'  =>  'shopp_location');
$my_search = new WP_Advanced_Search($args);
?>
 
<div class="search-form">
   <?php $my_search->the_form();  ?>
</div>

<?php
$my_search = new WP_Advanced_Search($args);
$temp_query = $wp_query;
$wp_query = $my_search->query();
if ( have_posts() ): 
   while ( have_posts() ): the_post();
 
      the_title();
 
   endwhile;
endif;
wp_reset_query();
$wp_query = $temp_query;