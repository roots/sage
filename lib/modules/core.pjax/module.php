<?php

function shoestrap_pjax_open_container() { ?>
	<div id="pjax-container">
	<?php
}
add_action( 'shoestrap_pre_wrap', 'shoestrap_pjax_open_container' );

function shoestrap_pjax_close_container() { ?>
	</div>
	<div id="loading"></div>
	<?php
}
add_action( 'shoestrap_after_wrap', 'shoestrap_pjax_close_container' );

function shoestrap_pjax_trigger_script() { ?>
	<script>
	$(document).on('pjax:send', function() {
		$('#loading').fadeToggle("slow", "linear")
	})
	$(document).on('pjax:complete', function() {
		$('#loading').fadeToggle("fast")
	})
	$(document).pjax('a', '#pjax-container')
	</script>
	<?php
}
add_action( 'wp_footer', 'shoestrap_pjax_trigger_script', 200 );
