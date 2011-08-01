<?php

class roots_vcard extends WP_Widget {

	function roots_vcard() {
		$widget_ops = array('description' => 'Display a vCard');
		parent::WP_Widget(false, __('Roots: vCard', 'roots'), $widget_ops);      
	}
   
	function widget($args, $instance) {  
		extract($args);
    extract($instance);

		echo $before_widget;
		if ($title) {
      echo $before_title, $title, $after_title;
    }
  ?>

		<p class="vcard">
			<a class="fn org url" href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a><br>
			<span class="adr">
			<span class="street-address"><?php echo $street_address; ?></span><br>
			<span class="locality"><?php echo $locality; ?></span>,
			<span class="region"><?php echo $region; ?></span>
			<span class="postal-code"><?php echo $postal_code; ?></span><br>
			</span>
			<span class="tel"><span class="value"><span class="hidden">+1-</span><?php echo $tel; ?></span></span><br>
			<a class="email" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
		</p>        
        
    <?php echo $after_widget;
        
	}
	
	function update($new_instance, $old_instance) {                
		return $new_instance;
	}

	function form($instance) {
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):', 'roots'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
		</p>       
		<p>
			<label for="<?php echo $this->get_field_id('street_address'); ?>"><?php _e('Street Address:', 'roots'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('street_address'); ?>" value="<?php echo esc_attr($instance['street_address']); ?>" class="widefat" id="<?php echo $this->get_field_id('street_address'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('locality'); ?>"><?php _e('City/Locality:', 'roots'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('locality'); ?>" value="<?php echo esc_attr($instance['locality']); ?>" class="widefat" id="<?php echo $this->get_field_id('locality'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('region'); ?>"><?php _e('State/Region:', 'roots'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('region'); ?>" value="<?php echo esc_attr($instance['region']); ?>" class="widefat" id="<?php echo $this->get_field_id('region'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('postal_code'); ?>"><?php _e('Zipcode/Postal Code:', 'roots'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('postal_code'); ?>" value="<?php echo esc_attr($instance['postal_code']); ?>" class="widefat" id="<?php echo $this->get_field_id('postal_code'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('tel'); ?>"><?php _e('Telephone:', 'roots'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('tel'); ?>" value="<?php echo esc_attr($instance['tel']); ?>" class="widefat" id="<?php echo $this->get_field_id('tel'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email:', 'roots'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('email'); ?>" value="<?php echo esc_attr($instance['email']); ?>" class="widefat" id="<?php echo $this->get_field_id('email'); ?>" />
		</p>                                   
	<?php
	}
} 

register_widget('roots_vcard');

?>