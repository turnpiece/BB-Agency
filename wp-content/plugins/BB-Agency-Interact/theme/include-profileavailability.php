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
                    
	$ProfileID					= $profile->ProfileID;
	$ProfileUserLinked			= $profile->ProfileUserLinked;


    ?>

    <?php

	$bookings = $wpdb->get_results( "SELECT * FROM ".table_agency_booking." WHERE `ProfileID` = $ProfileID AND `BookedTo` > NOW() ORDER BY `BookedFrom` DESC" );

	if (!empty($bookings)) : ?>
    <form method="post" action="<?php bloginfo("wpurl") ?>/profile-member/availability/">
        <input type="hidden" name="ProfileID" value="<?php echo $ProfileID ?>" />
        <input type="hidden" name="ProfileType" value="<?php echo $ptype1 ?>" />
    	<table cellspacing="0" class="wp-list-table widefat fixed">
    	  <tbody>
    	  <?php foreach ($bookings as $data) : $id = $data->JobID; ?>
    	    <tr class="<?php echo $data->IdBooked == 1 ? 'booked' : 'available' ?>">
    	      <td><?php _e('From', bb_agencyinteract_TEXTDOMAIN) ?> <?php echo date('jS F Y', strtotime($data->BookedFrom)) ?> <?php _e('to', bb_agencyinteract_TEXTDOMAIN) ?> <?php echo date('jS F Y', strtotime($data->BookedTo)) ?></td>
              <td><input type="checkbox" name="BookedID[]" value="<?php echo $data->BookedID ?>" /></td> 
    	    </tr>
    	  <?php endforeach; ?>
            <tr valign="top">
                <td scope="row"></td>
                <td>
                    <input type="hidden" name="action" value="deleteBookings" />
                    <input type="submit" name="submit" value="<?php _e("Remove selected", bb_agencyinteract_TEXTDOMAIN) ?>" class="button-primary" />
                </td>
            </tr>   
    	   </tbody>
    	</table>
    </form>
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