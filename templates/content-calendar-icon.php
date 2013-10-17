<?php $posttype = get_post_type( get_the_ID() ); ?>
<?php if ( $posttype == 'wprss_feed_item') { ?><?php } ?>
<?php if ( $posttype == 'post') { ?><p class="calendar"><?php the_time('j');?> <em><?php the_time('M');?></em></p><?php } ?>