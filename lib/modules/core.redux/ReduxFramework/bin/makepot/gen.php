<?php

// Load the makepot generator
require 'makepot.php';
$makepot = new Redux_Makepot;

foreach ( $makepot->projects as $name => $project ) {
	$results[ $name ] = $makepot->generate_pot( $name );
}