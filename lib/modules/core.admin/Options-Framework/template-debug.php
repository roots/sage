<?php
/*
Template Name: Debug
*/
get_header();
?>
		<pre>
		<?php 
		$smof_data_r = print_r($smof_data, true); 
		$smof_data_r_sans = htmlspecialchars($smof_data_r, ENT_QUOTES); 
		echo $smof_data_r_sans; ?>
		</pre>
	
<?php get_footer(); ?>
