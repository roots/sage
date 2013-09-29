<?php shopp('catalog','breadcrumb')?>
<?php if (shopp('product','found')): ?>
<div class="row">
	<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
    <?php get_template_part('templates/content', 'tabbable'); ?>
	</div>
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
  	<div class="pull-left">
  	  <?php shopp('product','gallery','p_setting=gallery-previews&thumbsetting=gallery-thumbnails'); ?>
      <p class="headline"><?php shopp('product','summary'); ?></p>
  	</div>	
    <div class="panel panel-default">
      <form action="<?php shopp('cart','url'); ?>" method="post" class="shopp product validate validation-alerts">
  		<?php if(shopp('product','has-variations')): ?>
  		<ul class="variations">
  			<?php shopp('product','variations','mode=multiple&label=true&defaults=Select an option&before_menu=<li>&after_menu=</li>'); ?>
  		</ul>
  		<?php endif; ?>
  		<?php if(shopp('product','has-addons')): ?>
  			<ul class="addons">
  				<?php shopp('product','addons','mode=menu&label=true&defaults=Select an add-on&before_menu=<li>&after_menu=</li>'); ?>
  			</ul>
  		<?php endif; ?>
  		<p><?php shopp('product','quantity','class=selectall&input=menu'); ?></p>
  		<p><?php shopp('product','addtocart'); ?></p>
      </form>
    </div>
		<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-body">
    <div class="btn-group" style="width: 100%;">
    <button type="button" class="btn btn-default btn-lg btn-block dropdown-toggle" data-toggle="dropdown">
      <span class="filetype-icon zip"></span> CAD Files <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
      <?php $dxf = get_field('dxf'); ?>
      <?php $dwg = get_field('dwg'); ?>
      <?php $sat = get_field('sat'); ?>
      <?php $stp = get_field('stp'); ?>
      <li><a href="<?php echo $dxf; ?>"><span class="filetype-icon zip"></span> Download 2D CAD Files as .DXF</a></li>
      <li><a href="<?php echo $dwg; ?>"><span class="filetype-icon zip"></span> Download 2D CAD Files as .DWG</a></li>
      <li class="divider"></li>
      <li><a href="<?php echo $sat; ?>"><span class="filetype-icon zip"></span> Download 3D CAD Files as .SAT</a></li>
      <li><a href="<?php echo $stp; ?>"><span class="filetype-icon zip"></span> Download 3D CAD Files as .STP</a></li>
    </ul>
    </div>
    <button type="button" class="btn btn-default btn-lg btn-block"><span class="filetype-icon pdf"></span> Download Spec Sheet (.PDF) </button>
    </div>

  	<div class="pull-right">

      <?php get_template_part('templates/product', 'images'); ?>

    	<?php shopp('product','description'); ?>
    
    	<?php if(shopp('product','has-specs')): ?>
    	<dl class="details">
    		<?php while(shopp('product','specs')): ?>
    		<dt><?php shopp('product','spec','name'); ?>:</dt><dd><?php shopp('product','spec','content'); ?></dd>
    		<?php endwhile; ?>
    	</dl>
    	<?php endif; ?>
	
  	</div>
	
	</div>
	
</div>
	
</div>
<div class="row">
	
	<div class="col-xs-12 col-sm-12 col-med-12 col-lg-12">
	
      <?php get_template_part('templates/content', 'related'); ?>
	</div>
      
</div>
      
<?php else: ?>
<h3>Product Not Found</h3>
<p>Sorry! The product you requested is not found in our catalog!</p>
<?php endif; ?>
