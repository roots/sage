<?php

/*---------------------------------------------------------------------------*/
/* Theme :: Shortcodes
/*---------------------------------------------------------------------------*/

/**
	Alert
**/
function srain_alert_shortcode($atts,$content=NULL) {
	extract( shortcode_atts( array(
		'type'	=> 'notice',
	), $atts) );

	if(!in_array($type,array('notice','warning','success','error','info')))
		$type = 'notice';
	$output = '<div class="alert '.$type.'">'.$content.'<a class="alert-close" href="#">×</a></div>';
	return $output;
}
add_shortcode('alert','srain_alert_shortcode');

/**
	Accordion
**/
function srain_accordion_shortcode($atts,$content=NULL) {
	$output = '<div class="accordion">'.do_shortcode($content).'</div>';
	return $output;
}
add_shortcode('accordion','srain_accordion_shortcode');

/**
	Accordion element
**/
function srain_acc_shortcode($atts,$content=NULL) {
	extract( shortcode_atts( array(
		'title'	=> 'Title'
	), $atts) );

	global $srain_acc_count;
	if(!$srain_acc_count) { $srain_acc_count = 1; }

	$output  = '<div class="title"><a href="#acc-'.$srain_acc_count.'"><i class="icon"></i>'.$title.'</a></div>';
	$output .= '<div id="acc-'.$srain_acc_count.'" class="inner">'.do_shortcode($content).'</div>';

	$srain_acc_count++;

	return $output;
}
add_shortcode('acc','srain_acc_shortcode');

/**
	Button
**/
function srain_button_shortcode($atts,$content=NULL) {
	extract( shortcode_atts( array(
		'size'		=> false,
		'style'		=> false,
		'link'		=> '#',
		'target'	=> false
	), $atts) );

	// Set classes
	$classes = 'button';
	if($size) { $classes .= ' '.$size; }
	if($style) { $classes .= ' '.$style; }
	$target = $target?' target="'.$target.'"':'';

	// Button
	$output = '<span class="button-wrap"><a href="'.$link.'" class="'.$classes.'"'.$target.'>'.$content.'</a></span>';

	return $output;
}
add_shortcode('button','srain_button_shortcode');

/**
	Columns / Grid
**/
function srain_column_shortcode($atts,$content=NULL) {
	extract( shortcode_atts( array(
		'size'		=> 4,
		'small'		=> false,
		'offset' 	=> 0,
		'clear'		=> false,
	), $atts) );
	$off = "";
	$padding = "lg";
	if (strip_tags($small)) {
		$padding = "sm";
	}
	if (strip_tags($offset) > 0) {
		$off = " col-offset-".$offset;
	}

	$lastclass=$last?' last':'';
	$output='<div class="col col-'.strip_tags($padding).'-'.strip_tags($size).$off.'">'.do_shortcode($content).'</div>';
	if(strip_tags($clear))
		$output.='<div class="clear"></div>';
	return $output;
}
add_shortcode('column','srain_column_shortcode');

/**
	Dropcap
**/
function srain_dropcap_shortcode($atts,$content=NULL) {
	$output = '<span class="dropcap">'.strip_tags($content).'</span>';
	return $output;
}
add_shortcode('dropcap','srain_dropcap_shortcode');

/**
	Highlight
**/
function srain_highlight_shortcode($atts,$content=NULL) {
	$output = '<span class="highlight">'.strip_tags($content).'</span>';
	return $output;
}
add_shortcode('highlight','srain_highlight_shortcode');

/**
	hr
**/
function srain_hr_shortcode($atts,$content=NULL) {
	$output = '<div class="hr"></div>';
	return $output;
}
add_shortcode('hr','srain_hr_shortcode');

/**
	li
**/
function srain_li_shortcode($atts,$content=NULL) {
	$output = '<li>'.$content.'</li>';
	return $output;
}
add_shortcode('li','srain_li_shortcode');

/**
	List
**/
function srain_list_shortcode($atts,$content=NULL) {
	extract( shortcode_atts( array(
		'type'	=> 'arrow'
	), $atts) );

	$output  = '<ul class="list '.$type.'">';
	$output .= do_shortcode($content);
	$output .= '</ul>';

	return $output;
}
add_shortcode('list','srain_list_shortcode');

/**
	Google Maps
**/
function srain_googlemap_shortcode($atts,$content=NULL) {
	extract( shortcode_atts( array(
		'id'			=> 'googlemap',
		'latitude'		=> 0,
		'longitude'		=> 0,
		'maptype'		=> 'ROADMAP', // HYBRID, SATELLITE, ROADMAP, TERRAIN
		'width'			=> '425',
		'height'		=> '350',
		'scrollwheel'	=> 'true',
		'zoom'			=> 10,
		'address'		=> NULL,
		'marker'		=> 'true',
		'html'			=> '',
		'popup'			=> 'false',
		'fullwidth'		=> 'false'
	), $atts) );

	global $srain_gmaps_loaded;
	$output = '';

	# Google Maps API Script + jQuery plugin
	if(!$srain_gmaps_loaded) {
		$output .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>'."\n";
		$output .= '<script type="text/javascript" src="'.get_template_directory_uri().'/js/jquery.gmap.min.js"></script>'."\n";
	}

	# Prevent duplicate loading of scripts
	$srain_gmaps_loaded = TRUE;

	# Google Map Div
	if($fullwidth==='false') {
		$output .= '<div id="'.$id.'" class="google-map" style="width:'.$width.'px; height:'.$height.'px"></div>'."\n";
	} else {
		$output .= '<div id="'.$id.'" class="google-map google-map-full" style="height:'.$height.'px"></div>'."\n";
	}
	

	# Google Map Standard Options
	$opts = array(
		'maptype'		=> "'".$maptype."'",
		'scrollwheel'	=> ($scrollwheel==='true')?'true':'false',
		'zoom'			=> $zoom
	);

	# Latitude / Longitude
	if($latitude && $longitude) {
		$opts['latitude'] = $latitude;
		$opts['longitude'] = $longitude;
	}

	# Latitude and Longitude Marker
	if(($latitude && $longitude) && ($marker==='true')) {
		# Set popup
		$popup = ($popup==='true')?'true':'false';
		# Create marker
		$opts['markers'] = "[
		{
			latitude: '".$latitude."',
			longitude: '".$longitude."',
			html: '".preg_replace('/\s+/',' ',trim($content))."',
			popup: ".$popup.",
		}
	]";
	}

	# Address
	if($address && (!$latitude && $longitude)) { $opts['address'] = "'".$address."'"; }

	# Address Marker
	if(!($latitude || $longitude) && $address && $marker) {
		# Set popup
		$popup = ($popup==='true')?'true':'false';
		# Create marker
		$opts['markers'] = "[
		{
			address: '".$address."',
			html: '".preg_replace('/\s+/',' ',trim($content))."',
			popup: ".$popup.",
		}
	]";
	}

	# Build Google Map Options
	$options = '';
	foreach($opts as $key=>$value) {
		$options .= "\t".$key.': '.$value.','."\n";
	}

	# Google Map Initialize
	$output .= "
<script type=\"text/javascript\">
jQuery('#".$id."').gMap({
".$options."
});
</script>
";

	return $output;
}
add_shortcode('googlemap','srain_googlemap_shortcode');

/**
	Plan
**/
function srain_plan_shortcode($atts,$content=NULL) {
	extract( shortcode_atts( array(
		'name'		=> 'Plan Name',
		'link'		=> '#',
		'linkname'	=> 'Sign Up',
		'price'		=> '0',
		'per'		=> false,
		'color'		=> false,
		'featured'	=> false,
	), $atts) );

	// Global variables
	global
		// Plan count
		$srain_plan_count;

	$outer_style = ($featured && $color)?' style="border: 1px solid #'.$color.'"':'';
	
	$class = $featured?'plan featured':'plan';
	$per = $per?' <span>/ '.$per.'</span>':'';
	$style = $color?' style="background:#'.$color.';"':'';
	$button = $featured?'button large light':'button';

	$output  = '<div class="'.$class.'"'.$outer_style.'>';
	$output .= '<div class="plan-head"'.$style.'>';
	$output .= '<h3>'.$name.'</h3>';
	$output .= '<div class="price">'.$price.$per.'</div>';
	$output .= '</div>'; // end .plan-head
	$output .= do_shortcode($content);
	$output .= '<div class="signup"><a href="'.$link.'" class="'.$button.'">'.$linkname.'</a></div>';
	$output .= '</div>'; // end .plan

	// Increment tab count
	$srain_plan_count++;

	return $output;
}
add_shortcode('plan','srain_plan_shortcode');

/**
	Price Table
**/
function srain_price_shortcode($atts,$content=NULL) {
	extract(shortcode_atts(array(), $atts));

	// Global variables
	global
		// Plan count
		$srain_plan_count;

	// Start count
	$count = $srain_plan_count = 0;

	// Set tab contents
	$table_contents = do_shortcode($content);

	$output = '<div class="pricing-table col-'.$srain_plan_count.' fix">'."\n";
	$output .= $table_contents;
	$output .= '<div class="clear"></div>';
	$output .= '</div>'."\n";

	return $output;
}
add_shortcode('price-table','srain_price_shortcode');

/**
	Pullquote
**/
function srain_pullquote_shortcode($atts,$content=NULL) {
	extract( shortcode_atts( array(
		'align'	=> 'left',
	), $atts) );

	if(!in_array($align,array('left','right')))
		$align = 'left';
	$output = '<span class="pullquote-'.$align.'">'.strip_tags($content).'</span>';

	return $output;
}
add_shortcode('pullquote','srain_pullquote_shortcode');

/**
	Tabs container and links
**/
function srain_tabs_shortcode($atts,$content=NULL) {
	extract(shortcode_atts(array(), $atts));

	// Global variables
	global
		// Tab counts
		$srain_tab_count,
		// Tab titles 
		$srain_tab_title;

	// Start count
	$count = $srain_tab_count = 1;

	// Reset tab title
	$srain_tab_title = NULL;

	// Set tab contents
	$tab_contents = do_shortcode($content);
	
	$output  = '<div class="tabs fix">'."\n";
	$output .= '<ul class="tabs-nav fix">'."\n";
	foreach($srain_tab_title as $title) {
		$output.='<li><a href="#tab-'.$count.'">'.$title.'</a></li>'."\n";
		$count++;
	}
	$output .= '</ul>'."\n";
	$output .= ''."\n";
	$output .= $tab_contents;
	$output .= ''."\n";
	$output .= '</div>'."\n";
	
	// Remove wp auto formatting - <br /> tags
	$output = str_replace(array('<br />'),'',$output);
	
	return $output;
}
add_shortcode('tabs','srain_tabs_shortcode');

/**
	Tab
**/
function srain_tab_shortcode($atts,$content=NULL) {
	extract(shortcode_atts( array(
		'title'	=> 'Title',
	), $atts) );
	
	// Global variables
	global
		// Tab count
		$srain_tab_count,
		// Tab titles
		$srain_tab_title;

	// Append tab title
	$srain_tab_title[] = $title;

	// Tab
	$output  = '<div id="tab-'.$srain_tab_count.'" class="tab"><div class="tab-content">';
	$output .= do_shortcode($content);
	$output .= '</div></div>';
	
	// Increment tab count
	$srain_tab_count++;

	return $output;
}
add_shortcode('tab','srain_tab_shortcode');

/**
	Toggle
**/
function srain_toggle_shortcode($atts,$content=NULL) {
	extract( shortcode_atts( array(
		'title'	=>	'Title',
	), $atts) );

	$output  = '<div class="toggle">';
	$output .= '<div class="title"><i class="icon"></i>'.$title.'</div>';
	$output .= '<div class="inner"><div class="content">'.do_shortcode($content).'</div></div>';
	$output .= '</div>';
	return $output;
}
add_shortcode('toggle','srain_toggle_shortcode');
