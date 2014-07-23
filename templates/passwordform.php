<?php
  global $post;
  $label = 'pwbox-'.(empty($post->ID) ? rand() : $post->ID);
?>
<form action="<?php echo esc_url(site_url('wp-login.php?action=postpass', 'login_post')); ?>" class="post-password-form" method="post">
  <p><?php _e('This content is password protected. To view it please enter your password below:', 'roots'); ?></p>
  <p><label for="<?php echo $label; ?>"><?php _e('Password:', 'roots'); ?></label></p>
  <div>
    <input name="post_password" id="<?php echo $label; ?>" type="password" placeholder="<?php _e('Enter password', 'roots'); ?>" />
    <span>
      <button type="submit" class="post-password-submit"><?php _e('Submit', 'roots'); ?></button>
    </span>
  </div>
</form>
