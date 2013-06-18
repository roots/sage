<?php
// calculate how many columns there are for the current post's metadata
if ( has_tag() && get_comments_number() >= 1 )
  $columnclass = 3;
elseif ( ( has_tag() && get_comments_number() < 1 ) || ( !has_tag() && get_comments_number() >= 1 ) )
  $columnclass = 4;
elseif ( !has_tag() && get_comments_number() < 1 )
  $columnclass = 6;
?>
<div class="row meta-row">

  <div class="col col-lg-<?php echo $columnclass; ?>">
    <div class="byline author vcard">
      <i class="glyphicon glyphicon-user"></i>
      <?php echo __( 'By', 'shoestrap' ); ?>
      <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author" class="fn"><?php echo get_the_author(); ?></a>
    </div>
  </div>

  <div class="col col-lg-<?php echo $columnclass; ?>">
    <i class="glyphicon time-icon glyphicon-time"></i>
    <time class="updated" datetime="<?php echo get_the_time( 'c' ); ?>" pubdate><?php echo get_the_date(); ?></time>
  </div>

  <?php if ( get_comments_number() >= 1 ) : ?>
    <div class="col col-lg-<?php echo $columnclass; ?>">
      <i class="glyphicon glyphicon-comment"></i>
      <a href="<?php comments_link(); ?>"><?php comments_number(); ?></a>
    </div>
  <?php endif; ?>

  <?php if ( has_tag() ) : ?>
    <div class="col col-lg-<?php echo $columnclass; ?>">
      <div class="tags-container">
        <i class="glyphicon glyphicon-tags"></i>
        <?php the_tags('<span class="label label-tag">', '</span> <span class="label label-tag">', '</span>'); ?>
      </div>
    </div>
  <?php endif; ?>

</div>
