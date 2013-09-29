      <div class="panel-group" id="accordion">
        <?php $count = 1; ?>
        <?php while( has_sub_field("panels")): ?>
        
        <?php $panel_title = get_sub_field("panel_title"); ?>
        <?php $panel_slug = convert_to_slug($panel_title); ?>
    
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $panel_slug ?>">
                   <?php echo $panel_title ?>
                </a>
              </h4>
            </div>
            <div id="<?php echo $panel_slug ?>" class="panel-collapse collapse in">
              <div class="panel-body">
              <?php if(get_row_layout() == "panel_default"): ?>
                <?php the_sub_field("panel_body"); ?>
              <?php endif; ?>
              </div>
            </div>
          </div>
          
        <?php $count++; ?>
        <?php endwhile; ?>

        </div><!--/.accordion -->