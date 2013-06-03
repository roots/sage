<?php

remove_action( 'init', 'of_options' );
remove_filter( 'of_options_before_save', 'of_filter_save_media_upload' );
