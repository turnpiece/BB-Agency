<?php
	global $wpdb;
	global $user_ID; 
	global $current_user;
	get_currentuserinfo();
	$ProfileUserLinked = $current_user->id;
	// Get Settings
	$bb_agency_options_arr = get_option('bb_agency_options');
	$bb_agency_option_unittype  			= $bb_agency_options_arr['bb_agency_option_unittype'];
	$bb_agency_option_locationtimezone 		= (int)$bb_agency_options_arr['bb_agency_option_locationtimezone'];
	
	// Get Values
	$profile = $wpdb->get_row("SELECT * FROM " . table_agency_profile . " WHERE `ProfileUserLinked` = '$ProfileUserLinked'");

    /*
     * Get profile type and Gender
     */
    $ptype = (int)get_user_meta($current_user->id, "bb_agency_interact_profiletype", true);
	$ptype = retrieve_title($ptype);
    $ProfileGender = get_user_meta($current_user->id, "bb_agency_interact_pgender", true);
    if (empty($ProfileGender)) {
    	update_usermeta($current_user->id, 'bb_agency_interact_pgender', $profile->ProfileGender);
    	$ProfileGender = $profile->ProfileGender;
    }
    $ProfileTypeArray = array();
    $profileType = ""; 
    $ptype1 = get_user_meta($current_user->id, "bb_agency_interact_profiletype", true);
    if (empty($ptype)) {
    	// Model or Client
		update_usermeta($current_user->id, 'bb_agency_interact_profiletype', $profile->ProfileType);
		$ptype = $profile->ProfileType;
    }
    $ProfileTypeArray = explode(",", $ptype1);
    $query3 = "SELECT * FROM " . table_agency_data_type . " ORDER BY DataTypeTitle";
    $results3 = $wpdb->get_results($query3);
    $count3 = count($results3);
	$i = 1;
	$ptypes = array();
    foreach ($results3 as $data3) {
        if (in_array($data3->DataTypeID, $ProfileTypeArray)) {
             $ptypes[] =  $data3->DataTypeTitle;
        }
    }
    $profileType = implode(', ', $ptypes);
                
	$ProfileID					= $profile->ProfileID;
	$ProfileUserLinked			= $profile->ProfileUserLinked;
	$ProfileDateUpdated			= stripslashes($profile->ProfileDateUpdated);
	$ProfileType				= stripslashes($profile->ProfileType);

	$bookings = $wpdb->get_results( "SELECT * FROM ".table_agency_booking." WHERE `ProfileID` = $ProfileID AND `BookedTo` > NOW() ORDER BY `BookedFrom` DESC" );

	if (!empty($bookings)) : ?>
	<table cellspacing="0" class="wp-list-table widefat fixed">
	  <thead>
	    <tr class="thead">
	      <th></th>
	      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=BookedTo&dir=". $sortDirection) ?>"><?php _e('From', bb_agencyinteract_TEXTDOMAIN) ?></a></th>
	      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=BookedTo&dir=". $sortDirection) ?>"><?php _e('To', bb_agencyinteract_TEXTDOMAIN) ?></a></th>
	    </tr>
	  </thead>
	  <tbody>

	  <?php foreach ($bookings as $data) : $id = $data->JobID; ?>
	    <tr class="<?php echo $data->IdBooked == 1 ? 'booked' : 'available' ?>">
	      <td>
	        <div class="row-actions">
	            <span class="edit">
	                <a href="<?php echo admin_url('admin.php?page=bb_agency_bookings&amp;action=edit&amp;BookedID='. $id) ?>" title="Edit this booking"><?php _e('Edit', bb_agencyinteract_TEXTDOMAIN) ?></a> | 
	            </span>
	            <span class="delete">
	                <a class="submitdelete" title="Remove this booking" href="<?php echo admin_url('admin.php?page=bb_agency_bookings&amp;action=delete&amp;BookedID='. $id) ?>" onclick="if ( confirm('You are about to delete a booking.') ) { return true; } return false;"><?php _e('Delete', bb_agencyinteract_TEXTDOMAIN) ?></a>
	            </span>
	        </div>
	      </td> 
	      <td><?php echo $data->BookedFrom ?></td>
	      <td><?php echo $data->BookedTo ?></td>
	    </tr>
	  <?php endforeach; ?>
	     
	  </tbody>
	  <tfoot>
	    <tr class="thead">
	      <th><?php _e('Model', bb_agencyinteract_TEXTDOMAIN) ?></th>
	      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=BookedTo&dir=". $sortDirection) ?>"><?php _e('From', bb_agencyinteract_TEXTDOMAIN) ?></a></th>
	      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=BookedTo&dir=". $sortDirection) ?>"><?php _e('To', bb_agencyinteract_TEXTDOMAIN) ?></a></th>
	    </tr>
	  </tfoot>
	</table>
	<?php endif; ?>

	<form method="post" action="<?php bloginfo("wpurl") ?>/profile-member/availability/">
		<input type="hidden" name="ProfileID" value="<?php echo $ProfileID ?>" />
		<input type="hidden" name="ProfileType" value="<?php echo $ptype1 ?>" />
	    <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><?php _e('Start Date', bb_agencyinteract_TEXTDOMAIN) ?>*</th>
                    <td>
                        <input type="text" class="bbdatepicker" id="BookedFrom" name="BookedFrom" value="" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('End Date', bb_agencyinteract_TEXTDOMAIN) ?>*</th>
                    <td>
                        <input type="text" class="bbdatepicker" id="BookedTo" name="BookedTo" value="" />
                    </td>
                </tr>
	    		<tr valign="top">
					<td scope="row"></td>
					<td>
						<input type="hidden" name="action" value="addBooking" />
						<input type="submit" name="submit" value="<?php _e("Save and Continue", bb_agencyinteract_TEXTDOMAIN) ?>" class="button-primary" />
					</td>
		  		</tr>
			</tbody>
	 	</table>
	</form>