    <nav class="breadcrumb row">
          <ul>
            <li class="home first"><a href="<?php echo home_url(); ?>" title="<?php _e('home', 'roots'); ?>"><?php _e('home', 'roots'); ?></a></li>
                <?php
                if(is_singular()){
                  $link = get_post_type_archive_link(get_post_type());
                  if($link) {
                       $type_obj = get_post_type_object(get_post_type());
                        printf('<li class="post-type post-type-%s middle"><a href="%s" title="%s">%s</a></li>', get_post_type(), get_post_type_archive_link(get_post_type()), $type_obj->labels->name, $type_obj->labels->name);
                        printf('<li class="article-title last">%s</li>', get_the_title());
                  }else{
                        printf('<li class="article-title last">%s</li>', get_the_title());
                  }
               }
               //not a single post
               else {
                    if(is_category()){
                      //try category if post type has no archive - e.g. posts
                      $category = get_the_category();
                      if($category){
                          printf('<li class="category category-%s last"><a href="%s" title="%s">%s</a></li>', $category[0]->name, get_category_link($category), $category[0]->name, $category[0]->name);
                      }
                    }
                    if(is_tag()){
                      global $wp_query;
                      $tag = get_term_by('name', $wp_query->query['tag'], 'post_tag');
                      printf('<li class="tag tag-%s last"><a href="%s" title="%s">%s</a></li>', $tag->name, get_term_link($tag, 'post_tag'), $tag->name, $tag->name);
                      //need support tags
                    }
                    if(is_tax()){
                      global $wp_query;
                      $taxonomy = $wp_query->tax_query->queries[0]['taxonomy'];
                      $term = get_term_by('slug', $wp_query->tax_query->queries[0]['terms'][0], $taxonomy);
                      printf('<li class="term term-%s last"><a href="%s" title="%s">%s</a></li>', $term->name, get_term_link($term, $taxonomy), $term->name, $term->name);
                    }
                    if(is_author()){
                      $author = get_the_author();
                      if($author){
                          printf('<li class="author author-%s last"><a href="%s" title="%s">%s</a></li>', $author, get_author_posts_url(get_the_author_meta('ID')), $author, $author);
                      }
                    }
                    if(is_post_type_archive()){
                        $post_type = get_post_type();
                        $pto = get_post_type_object($post_type);
                        $plural = $pto->labels->name;

                          printf('<li class="post-type post-type-%s last"><a href="%s" title="%s">%s</a></li>', $post_type, get_post_type_archive_link($post_type), $plural, $plural);
                    }
               } ?>

        </ul>
    </nav>
