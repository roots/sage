<form action="<?php shopp('customer','action'); ?>" method="post" class="shopp validate" autocomplete="off">

	<?php if(shopp('customer','password-changed')): ?>
	<div class="notice">Your password has been changed successfully.</div>
	<?php endif; ?>
	<?php if(shopp('customer','profile-saved')): ?>
	<div class="notice">Your account has been updated.</div>
	<?php endif; ?>

	<p><a href="<?php shopp('customer','url'); ?>">&laquo; Return to Account Management</a></p>

	<ul>
		<li>
			<label for="firstname">Your Account</label>
			<span><?php shopp('customer','firstname','required=true&minlength=2&size=8&title=First Name'); ?><label for="firstname">First</label></span>
			<span><?php shopp('customer','lastname','required=true&minlength=3&size=14&title=Last Name'); ?><label for="lastname">Last</label></span>
		</li>
		<li>
			<span><?php shopp('customer','company','size=20&title=Company'); ?><label for="company">Company</label></span>
		</li>
		<li>
			<span><?php shopp('customer','phone','format=phone&size=15&title=Phone'); ?><label for="phone">Phone</label></span>
		</li>
		<li>
			<span><?php shopp('customer','email','required=true&format=email&size=30&title=Email'); ?>
			<label for="email">Email</label></span>
		</li>
		<li>
			<div class="inline"><label for="marketing"><?php shopp('customer','marketing','title=I would like to continue receiving e-mail updates and special offers!'); ?> I would like to continue receiving e-mail updates and special offers!</label></div>
		</li>
		<?php while (shopp('customer','hasinfo')): ?>
		<li>
			<span><?php shopp('customer','info'); ?>
			<label><?php shopp('customer','info','mode=name'); ?></label></span>
		</li>
		<?php endwhile; ?>
		<li>
			<label for="password">Change Your Password</label>
			<span><?php shopp('customer','password','size=14&title=New Password'); ?><label for="password">New Password</label></span>
			<span><?php shopp('customer','confirm-password','&size=14&title=Confirm Password'); ?><label for="confirm-password">Confirm Password</label></span>
		</li>
		<li id="billing-address-fields">
		<label for="billing-address">Billing Address</label>
		<div>
			<?php shopp('customer','billing-address','title=Billing street address'); ?>
			<label for="billing-address">Street Address</label>
		</div>
		<div>
			<?php shopp('customer','billing-xaddress','title=Billing address line 2'); ?>
			<label for="billing-xaddress">Address Line 2</label>
		</div>
		<div class="left">
			<?php shopp('customer','billing-city','title=City billing address'); ?>
			<label for="billing-city">City</label>
		</div>
		<div class="right">
			<?php shopp('customer','billing-state','title=State/Provice/Region billing address'); ?>
			<label for="billing-state">State / Province</label>
		</div>
		<div class="left">
			<?php shopp('customer','billing-postcode','title=Postal/Zip Code billing address'); ?>
			<label for="billing-postcode">Postal / Zip Code</label>
		</div>
		<div class="right">
			<?php shopp('customer','billing-country','title=Country billing address'); ?>
			<label for="billing-country">Country</label>
		</div>
		</li>
		<li id="shipping-address-fields">
			<label for="shipping-address">Shipping Address</label>
			<div>
				<?php shopp('customer','shipping-address','title=Shipping street address'); ?>
				<label for="shipping-address">Street Address</label>
			</div>
			<div>
				<?php shopp('customer','shipping-xaddress','title=Shipping address line 2'); ?>
				<label for="shipping-xaddress">Address Line 2</label>
			</div>
			<div class="left">
				<?php shopp('customer','shipping-city','title=City shipping address'); ?>
				<label for="shipping-city">City</label>
			</div>
			<div class="right">
				<?php shopp('customer','shipping-state','title=State/Provice/Region shipping address'); ?>
				<label for="shipping-state">State / Province</label>
			</div>
			<div class="left">
				<?php shopp('customer','shipping-postcode','title=Postal/Zip Code shipping address'); ?>
				<label for="shipping-postcode">Postal / Zip Code</label>
			</div>
			<div class="right">
				<?php shopp('customer','shipping-country','title=Country shipping address'); ?>
				<label for="shipping-country">Country</label>
			</div>
		</li>
	</ul>
	<p><?php shopp('customer','save-button','label=Save'); ?></p>

	<p><a href="<?php shopp('customer','url'); ?>">&laquo; Return to Account Management</a></p>

</form>