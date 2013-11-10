<?php // NEWSLETTER SIGNUP // ?>
<div id="newsletter" role="dialog" class="modal fade" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 id="" class="modal-title">Newsletter Signup</h4>
  </div>
  <div class="modal-body">
    <p>
<?php
    $form = get_field('newsletter_form', 'option');
    gravity_form_enqueue_scripts($form->id, true);
    gravity_form($form->id, false, false, false, '', true, 1);
?></p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
</div>
</div>
<?php // REQUEST LITERATURE // ?>
<div id="request-literature" role="dialog" class="modal fade" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class="fa fa-book"></i> Request Literature</h4>
  </div>
  <div class="modal-body">
    <p>
<?php
    $form = get_field('request_literature_form', 'option');
    gravity_form_enqueue_scripts($form->id, true);
    gravity_form($form->id, false, false, false, '', true, 1);
?></p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
</div>
</div>
<?php // REQUEST CALL BACK // ?>
<div id="request-call-back" role="dialog" class="modal fade" aria-labelledby="Request Call Back" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class="fa fa-phone"></i> Request Call Back</h4>
  </div>
  <div class="modal-body">
    <p>
<?php
    $form = get_field('request_call_back_form', 'option');
    gravity_form_enqueue_scripts($form->id, true);
    gravity_form($form->id, false, false, false, '', true, 1);
?></p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
</div>
</div>

<?php // SHARE THIS PAGE // ?>
<div id="share" role="dialog" class="modal fade" aria-labelledby="Share this Page" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class="fa fa-share"></i> Share This Page</h4>
  </div>
  <div class="modal-body">
    <p>
<?php
    $form = get_field('share_form', 'option');
    gravity_form_enqueue_scripts($form->id, true);
    gravity_form($form->id, false, false, false, '', true, 1);
?></p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
</div>
</div>
<?php // LOGIN // ?>
<div id="login" role="dialog" class="modal fade" aria-labelledby="Login" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">Login</h4>
  </div>
  <div class="modal-body">
    <p>
<?php $args = array(
        'echo' => true,
        'redirect' => site_url( $_SERVER['REQUEST_URI'] ), 
        'form_id' => 'loginform',
        'label_username' => __( 'Username' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in' => __( 'Log In' ),
        'id_username' => 'user_login',
        'id_password' => 'user_pass',
        'id_remember' => 'rememberme',
        'id_submit' => 'wp-submit',
        'remember' => true,
        'value_username' => NULL,
        'value_remember' => false ); ?> 
    </p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
</div>
</div>

<?php // SEARCH DISTRIBUTORS // ?>
<div id="search-distributors" role="dialog" class="modal fade" aria-labelledby="Search Distributors" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class="fa fa-search"></i> Search <?php bloginfo('url'); ?></h4>
  </div>
  <div class="modal-body">
    <?php get_search_form(); ?>
  </div>
  <div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
</div>
</div>
</div>

<?php // SEARCH PRODUCTS // ?>
<div id="search-products" role="dialog" class="modal fade" aria-labelledby="Search Products" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class="fa fa-search"></i> Search <?php bloginfo('url'); ?></h4>
  </div>
  <div class="modal-body">
    <?php get_search_form(); ?>
  </div>
  <div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
</div>
</div>
</div>