<?php if(shopp('collection','has-products','load=coverimages')): ?>
	<div class="collection">
	<?php shopp('collection','breadcrumb'); ?>
	
	<div class="row">
    <div class="thumbnails">
		<?php while(shopp('collection','products')): ?>
		<?php if(shopp('collection','row')): ?></div></div><div class="row"><div class="thumbnails"><?php endif; ?>
			 <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
     <article id="product-<?php shopp('product','id'); ?>" class="product product-<?php shopp('product','id'); ?>">
       <header>
         
       </header>
        <a title="<?php shopp('product','name'); ?>" class="thumbnail thumbnail-<?php shopp('product','id'); ?>"  href="<?php shopp('product','url'); ?>">
          <div class="entry-summary">
              <?php shopp('product','coverimage','setting=thumbnails'); ?>
          </div>
        </a>
        <footer>
          <h4><a href="<?php shopp('product','url'); ?>"><?php shopp('product','name'); ?></a></h4>
          <p><?php shopp('product','summary'); ?></p>
        </footer>
      </article>

			</div>
		<?php endwhile; ?>
	</div>


	<div class="alignright"><?php shopp('collection','pagination','show=10'); ?></div>

  </div>

	</div>
<?php else: ?>
	<?php if (!shopp('collection','is-landing')): ?>
	<?php shopp('collection','breadcrumb'); ?>
	<h3><?php shopp('collection','name'); ?></h3>
	<p>No products were found.</p>
	<?php endif; ?>
<?php endif; ?>