<?php 
$span = "span3";
$region = $_GET['Region'];
if ($region) {

$args=array(
	'role' => array('Administrator', 'Sales Rep', 'Tech Rep', ),
  'meta_value' => $region,
  );
$user_query = new WP_User_Query( $args );

} else {

$span = "span3";
$args = array(
	'role' => array('Administrator', 'Sales Rep', 'Tech Rep', ),
);
$user_query = new WP_User_Query( $args );

}
?>
<div class="row-fluid">
  <div class="span12">
    <div class="well">
    <form name="search" action="" method="get">
    <select class="region" name="Region">
     <?php
      $metakey = 'region';
      $regions = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT meta_value FROM $wpdb->usermeta WHERE meta_key = %s ORDER BY meta_value ASC", $metakey) );
        if ($regions) {
          foreach ($regions as $region) {
        echo "<option value=\"" . $region . "\">" . $region . "</option>";
          }
        }
     ?>
    </select>
    <!-- <select name="States">
     <?php
      $metakey = 'state';
      $states = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT meta_value FROM $wpdb->usermeta WHERE meta_key = %s ORDER BY meta_value ASC", $metakey) );
        if ($states) {
          foreach ($states as $state) {
        echo "<option value=\"" . $state . "\">" . $state . "</option>";
          }
        }
     ?>
    </select> -->
    <a class="btn btn-reset" href="<?php the_permalink();?>" />Reset</a> <input class="btn btn-primary btn-filter" type="submit" value="Filter" />
    </form>
    
    </div>
  </div>
</div>

<?php // User Loop
if ( !empty( $user_query->results ) ) { ?>

<div class="row-fluid">
  <ul class="inline">
    <?php	foreach ( $user_query->results as $user ) { ?>
    	 <li class="<?php echo $span ?>">
      	 <div data-toggle="tooltip" title="Tip" class="well well-reps">
        	 <?php $pattern = '<%1$s class="%2$s">%3$s</%1$s>';
        	 echo '<div class="rep rep-' . $user->ID . ' rep-' . $user->user_nicename . '">';
        	 echo '<address>';
        		printf( $pattern, 'div', 'rep-gravatar', get_avatar( $user->user_email, 32, null, $user->display_name ) );
        		printf( $pattern, 'h4', 'rep-name', $user->display_name );
        		if ($user->position == '' || $user->office_fax == 'N/A') {} else { printf( $pattern, 'p', 'rep-position', '<em>' . $user->position . '</em>' ); }
        		if ($user->region == '') {} else { printf( $pattern, 'p', 'rep-region', '' . $user->region . '' ); }
        		if ($user->state == '') {} else { printf( $pattern, 'p', 'rep-states', '' . $user->state . '' ); }
        		if ($user->office_phone == '' || $user->position == 'N/A') {} else { printf( $pattern, 'dl', 'rep-office-phone', '<dt><abbr title="Office Phone Number" class="initialism"><i class="icon-phone"></i></abbr></dt><dd>' . $user->office_phone . '</dd>' ); }
        		if ($user->office_fax == '' || $user->office_fax == 'N/A') {} else { printf( $pattern, 'dl', 'rep-office-fax', '<dt><abbr title="Office Fax Number" class="initialism">F</abbr></dt><dd>' . $user->office_fax . '</dd>' ); }
        		if ($user->cell_phone == '' || $user->cell_phone == 'N/A') {} else { printf( $pattern, 'dl', 'rep-cell-phone', '<dt><abbr title="Cell Phone Number" class="initialism"><i class="icon-mobile-phone"></i></abbr></dt><dd>' . $user->cell_phone . '</dd>' ); }
        		if ($user->email_address == '' || $user->email_address == 'N/A') {} else { printf( $pattern, 'dl',  'rep-email', '<dt><abbr title="Email Address" class="initialism"><i class="icon-envelope"></i></abbr></dt><dd>' . '<a href="mailto:' . $user->email_address . '">' . $user->email_address . '</a></dd>' ); }
        		echo '</address>';
        		echo '</div>'; ?>
      	 </div>
    	 </li>
	 <?php } ?>
  </ul>
</div><!-- ./row-fluid -->

<?php wp_reset_query();?>

<?php } else { ?> 
<p>No reps found.</p>
<?php } ?>
