<?php
// http://wp.tutsplus.com/tutorials/creative-coding/the-rewrite-api-post-types-taxonomies/
add_rewrite_tag('%product%','([^/]+)','post_type=');
add_rewrite_tag('%product_cat%','([^/]+)','product_cat=');

//add_permastruct('product', '%product_cat%/%product%');
//add_permastruct('product_cat', '/%product_cat%');