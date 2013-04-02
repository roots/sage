<?php /*
class PostsOrderedByMetaQuery extends WP_Query {
  var $posts_ordered_by_meta = true;
  var $orderby_order = 'ASC';
  var $orderby_meta_key;
  function __construct($args=array()) {
    add_filter('posts_join',array(&$this,'posts_join'),10,2);
    add_filter('posts_orderby',array(&$this,'posts_orderby'),10,2);
    $this->posts_ordered_by_meta = true;
    $this->orderby_meta_key = $args['orderby_meta_key'];
    unset($args['orderby_meta_key']);
    if (!empty($args['orderby_order'])) {
      $this->orderby_order = $args['orderby_order'];
      unset($args['orderby_order']);
    }
    parent::query($args);
  }
  function posts_join($join,$query) {
    if (isset($query->posts_ordered_by_meta)) {
      global $wpdb;
      $join .=<<<SQL
INNER JOIN {$wpdb->postmeta} postmeta_price ON postmeta_price.post_id={$wpdb->posts}.ID
       AND postmeta_price.meta_key='{$this->orderby_meta_key}'
SQL;
    }
    return $join;
  }
  function posts_orderby($orderby,$query) {
    if (isset($query->posts_ordered_by_meta)) {
      global $wpdb;
      $orderby = "postmeta_price.meta_value {$this->orderby_order}";
    }
    return $orderby;
  }
}


function atkoreAlphaSearch() {

add_action( 'posts_where', 'startswithaction' );
function startswithaction( $sql ){
    global $wpdb;
    $startswith = get_query_var( 'startswith' );

    if( $startswith ){
        $sql .= $wpdb->prepare( " AND $wpdb->posts.post_title LIKE %s ", $startswith.'%' );
    }

    return $sql;
}


add_action( 'posts_where', 'startswithnumberaction' );
function startswithnumberaction( $sql ){
    global $wpdb;
    $startswithnumber = get_query_var( 'startswithnumber' );

    if( $startswithnumber ){ 
        $sql .= $wpdb->prepare( " AND $wpdb->posts.post_title NOT REGEXP %s ", '^[[:alpha:]]' );
    }

    return $sql;
}

query_posts( $query_string .'&startswith='.$_GET['letter'].'&posts_per_page=-1&startswithnumber='.$_GET['number']);
?>

<?php echo "<a href='$PHP_Self/?$query_string&number=true' ># </a> - ";


    foreach (range('A', 'Z') as $i)
    {
     $letter =strtolower($i);
        echo "<a href='$PHP_Self/?$query_string&letter=$letter' >$i </a> - ";
    }
    echo "<a href='$PHP_Self/?$query_string'>All </a>" ?>
}

*/