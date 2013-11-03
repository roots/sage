<?php while( has_sub_field('icons', 'options')): ?>
	<?php if(get_row_layout() == "icns"): ?>
		<link rel="icon" href="<?php echo the_sub_field('image', 'options');?>" sizes="<?php echo implode(' ', get_sub_field('sizes', 'options')); ?>">
	<?php elseif(get_row_layout() == "ico"): ?>
		<link rel="icon" href="<?php echo the_sub_field('image', 'options');?>" sizes="<?php echo implode(' ', get_sub_field('sizes', 'options')); ?>" type="image/vnd.microsoft.icon">
	<?php elseif(get_row_layout() == "png"): ?>
		<link rel="icon" href="<?php echo the_sub_field('image', 'options');?>" sizes="<?php echo implode(' ', get_sub_field('sizes', 'options')); ?>" type="image/png">
	<?php elseif(get_row_layout() == "ios"): ?>
		<link rel="apple-touch-icon" href="<?php echo the_sub_field('image', 'options');?>" sizes="<?php echo implode(' ', get_sub_field('sizes', 'options')); ?>" type="image/png">
	<?php elseif(get_row_layout() == "android"): ?>
		<link rel="icon" href="<?php echo the_sub_field('image', 'options');?>" sizes="<?php echo implode(' ', get_sub_field('sizes', 'options')); ?>" type="image/png">
	<?php elseif(get_row_layout() == "svg"): ?>
		<link rel="icon" href="<?php echo the_sub_field('image', 'options');?>" sizes="<?php echo implode(' ', get_sub_field('sizes', 'options')); ?>" type="image/svg+xml">
	<?php endif; ?>
<?php endwhile; ?>