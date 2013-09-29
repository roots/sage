<form action="<?php shopp('checkout','url'); ?>" method="post" class="shopp validate" id="checkout">
<?php shopp('checkout','cart-summary'); ?>

<?php if (shopp('cart','hasitems')): ?>
	<?php shopp('checkout','function'); ?>
	<ul>
		<?php if (shopp('customer','notloggedin')): ?>
		<li>
			<label for="login">Login to Your Account</label>
			<span><?php shopp('customer','account-login','size=20&title=Login'); ?><label for="account-login">Email</label></span>
			<span><?php shopp('customer','password-login','size=20&title=Password'); ?><label for="password-login">Password</label></span>
			<span><?php shopp('customer','login-button','context=checkout&value=Login'); ?></span>
		</li>
		<li></li>
		<?php endif; ?>
		<li>
			<label for="firstname">Contact Information</label>
			<span><?php shopp('checkout','firstname','required=true&minlength=2&size=8&title=First Name'); ?><label for="firstname">First</label></span>
			<span><?php shopp('checkout','lastname','required=true&minlength=3&size=14&title=Last Name'); ?><label for="lastname">Last</label></span>
			<span><?php shopp('checkout','company','size=22&title=Company/Organization'); ?><label for="company">Company/Organization</label></span>
		</li>
		<li>
		</li>
		<li>
			<span><?php shopp('checkout','phone','format=phone&size=15&title=Phone'); ?><label for="phone">Phone</label></span>
			<span><?php shopp('checkout','email','required=true&format=email&size=30&title=Email'); ?>
			<label for="email">Email</label></span>
		</li>
		<?php if (shopp('customer','notloggedin')): ?>
		<li>
			<span><?php shopp('checkout','password','required=true&format=passwords&size=16&title=Password'); ?>
			<label for="password">Password</label></span>
			<span><?php shopp('checkout','confirm-password','required=true&format=passwords&size=16&title=Password Confirmation'); ?>
			<label for="confirm-password">Confirm Password</label></span>
		</li>
		<?php endif; ?>
		<li></li>
		<?php if (shopp('cart','needs-shipped')): ?>
			<li class="half" id="billing-address-fields">
		<?php else: ?>
			<li>
		<?php endif; ?>
			<label for="billing-address">Billing Address</label>
			<div>
				<?php shopp('checkout','billing-name','required=false&title=Bill to'); ?>
				<label for="billing-name">Name</label>
			</div>
			<div>
				<?php shopp('checkout','billing-address','required=true&title=Billing street address'); ?>
				<label for="billing-address">Street Address</label>
			</div>
			<div>
				<?php shopp('checkout','billing-xaddress','title=Billing address line 2'); ?>
				<label for="billing-xaddress">Address Line 2</label>
			</div>
			<div class="left">
				<?php shopp('checkout','billing-city','required=true&title=City billing address'); ?>
				<label for="billing-city">City</label>
			</div>
			<div class="right">
				<?php shopp('checkout','billing-state','required=true&title=State/Provice/Region billing address'); ?>
				<label for="billing-state">State / Province</label>
			</div>
			<div class="left">
				<?php shopp('checkout','billing-postcode','required=true&title=Postal/Zip Code billing address'); ?>
				<label for="billing-postcode">Postal / Zip Code</label>
			</div>
			<div class="right">
				<?php shopp('checkout','billing-country','required=true&title=Country billing address'); ?>
				<label for="billing-country">Country</label>
			</div>
		<?php if (shopp('cart','needs-shipped')): ?>
			<div class="inline"><?php shopp('checkout','same-shipping-address'); ?></div>
			</li>
			<li class="half right" id="shipping-address-fields">
				<label for="shipping-address">Shipping Address</label>
				<div>
					<?php shopp('checkout','shipping-name','required=false&title=Ship to'); ?>
					<label for="shipping-address">Name</label>
				</div>
				<div>
					<?php shopp('checkout','shipping-address','required=true&title=Shipping street address'); ?>
					<label for="shipping-address">Street Address</label>
				</div>
				<div>
					<?php shopp('checkout','shipping-xaddress','title=Shipping address line 2'); ?>
					<label for="shipping-xaddress">Address Line 2</label>
				</div>
				<div class="left">
					<?php shopp('checkout','shipping-city','required=true&title=City shipping address'); ?>
					<label for="shipping-city">City</label>
				</div>
				<div class="right">
					<?php shopp('checkout','shipping-state','required=true&title=State/Provice/Region shipping address'); ?>
					<label for="shipping-state">State / Province</label>
				</div>
				<div class="left">
					<?php shopp('checkout','shipping-postcode','required=true&title=Postal/Zip Code shipping address'); ?>
					<label for="shipping-postcode">Postal / Zip Code</label>
				</div>
				<div class="right">
					<?php shopp('checkout','shipping-country','required=true&title=Country shipping address'); ?>
					<label for="shipping-country">Country</label>
				</div>
			</li>
		<?php else: ?>
			</li>
		<?php endif; ?>
		<?php if (shopp('checkout','billing-localities')): ?>
			<li class="half locale hidden">
				<div>
				<?php shopp('checkout','billing-locale'); ?>
				<label for="billing-locale">Local Jurisdiction</label>
				</div>
			</li>
		<?php endif; ?>
		<li></li>
		<li>
			<?php shopp('checkout','payment-options'); ?>
			<?php shopp('checkout','gateway-inputs'); ?>
		</li>
		<?php if (shopp('checkout','card-required')): ?>
		<li class="payment">
			<label for="billing-card">Payment Information</label>
			<span><?php shopp('checkout','billing-card','required=true&size=30&title=Credit/Debit Card Number'); ?><label for="billing-card">Credit/Debit Card Number</label></span>
			<span><?php shopp('checkout','billing-cardexpires-mm','size=4&required=true&minlength=2&maxlength=2&title=Card\'s 2-digit expiration month'); ?> /<label for="billing-cardexpires-mm">MM</label></span>
			<span><?php shopp('checkout','billing-cardexpires-yy','size=4&required=true&minlength=2&maxlength=2&title=Card\'s 2-digit expiration year'); ?><label for="billing-cardexpires-yy">YY</label></span>
			<span><?php shopp('checkout','billing-cardtype','required=true&title=Card Type'); ?><label for="billing-cardtype">Card Type</label></span>
		</li>
		<li class="payment">
			<span><?php shopp('checkout','billing-cardholder','required=true&size=30&title=Card Holder\'s Name'); ?><label for="billing-cardholder">Name on Card</label></span>
			<span><?php shopp('checkout','billing-cvv','size=7&minlength=3&maxlength=4&title=Card\'s security code (3-4 digits on the back of the card)'); ?><label for="billing-cvv">Security ID</label></span>
		</li>
		<?php if (shopp('checkout','billing-xcsc-required')): // Extra billing security fields ?>
		<li class="payment">
		<span><?php shopp('checkout','billing-xcsc','input=start&size=7&minlength=5&maxlength=5&title=Card\'s start date (MM/YY)'); ?><label for="billing-xcsc-start">Start Date</label></span>
			<span><?php shopp('checkout','billing-xcsc','input=issue&size=7&minlength=3&maxlength=4&title=Card\'s issue number'); ?><label for="billing-xcsc-issue">Issue #</label></span>
		</li>
		<?php endif; ?>

		<?php endif; ?>
		<li></li>
		<li>
		<div class="inline"><label for="marketing"><?php shopp('checkout','marketing'); ?> Yes, I would like to receive e-mail updates and special offers!</label></div>
		</li>
	</ul>
	<p class="submit"><?php shopp('checkout','submit','value=Submit Order'); ?></p>

<?php endif; ?>
</form>
