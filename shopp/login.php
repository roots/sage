<form action="<?php shopp('customer','url'); ?>" method="post" class="shopp" id="login">

<ul>
	<?php if (shopp('customer','notloggedin')): ?>
	<li>
		<label for="login">Account Login</label>
		<span><?php shopp('customer','account-login','size=20&title=Login'); ?>
			<label for="login"><?php shopp('customer','login-label'); ?></label></span>
		<span><?php shopp('customer','password-login','size=20&title=Password'); ?>
			<label for="password">Password</label></span>
		<span><?php shopp('customer','login-button'); ?></span>
	</li>
	<li><a href="<?php shopp('customer','recover-url'); ?>">Lost your password?</a></li>
	<?php endif; ?>
</ul>

</form>
