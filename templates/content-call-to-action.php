                      <?php if( get_field('show_call_to_action_button') ) { ?>
                        <?php if(get_field('call_to_action_type') == "internal") { ?>
                        <a class="btn btn-primary btn-large btn-sequence btn-internal" href="<?php the_field('internal'); ?>"><i class="icon-circle-arrow-right"></i> <?php the_field('call_to_action_button_text'); ?></a>
                        <?php } ?>
                        <?php if(get_field('call_to_action_type') == "external") { ?>
                        <a class="btn btn-primary btn-large btn-sequence btn-external" href="<?php the_field('external'); ?>"><i class="icon-circle-arrow-right"></i> <?php the_field('call_to_action_button_text'); ?></a>
                        <?php } ?>
                        <?php if(get_field('call_to_action_type') == "download") { ?>
                        <a class="btn btn-primary  btn-large btn-sequence btn-download" href="<?php the_field('download'); ?>"><i class="icon-download"></i> <?php the_field('call_to_action_button_text'); ?></a>
                        <?php } ?>
                        <?php if(get_field('call_to_action_type') == "form") { ?>
                        <a class="btn btn-primary  btn-large btn-sequence btn-form" href="#callToActionForm"><i class="icon-circle-arrow-right"></i> <?php the_field('call_to_action_button_text'); ?></a>
                        <?php } ?>
                    <?php } ?>