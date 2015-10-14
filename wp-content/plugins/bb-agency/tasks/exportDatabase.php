<?php

// Tap into WordPress Database
include_once('../../../../wp-config.php');
include_once('../../../../wp-load.php');
include_once('../../../../wp-includes/wp-db.php');

global $wpdb;

// *************************************************************************************************** //
// Get Going

	$cols = $wpdb->query("SHOW COLUMNS FROM `bb_agency_profile`", ARRAY_A);
	$i = 0;
	if (count($cols) > 0) {
	  	foreach ($cols as $col) {
			$csv_output .= $col['Field'].", ";
			$i++;
	  	}
	}
	$csv_output .= ", Media\n";
	
    // get profile type
    $ProfileType = isset($_POST['ProfileType']) ? $_POST['ProfileType'] : 0;

    $sql = "SELECT * FROM `bb_agency_profile`";

    if ($ProfileType > 0)
        $sql .= " WHERE `ProfileType` = $ProfileType";

	$profiles = $wpdb->get_results($sql, ARRAY_N);

	foreach ($profiles as $profile) {
		for ($j = 0; $j < $i; $j++) {
			$csv_output .= $profile[$j].", ";
		}
		// get images & media attachments
		$media_sql = "SELECT * FROM `bb_agency_profile_media` WHERE `ProfileID` = ".$profile[0];

		$files = $wpdb->get_results( $media_sql, ARRAY_A );

		$media = array()

		foreach ($files as $file) {
			$media[] = $file['ProfileMediaType'] . '=' . $file['ProfileMediaURL'];
		}

	  	$csv_output .= implode('&', $media) . "\n";
	}
	
	$filename = "bb_agency_".date("Y-m-d_H-i",time());
	header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: csv" . date("Y-m-d") . ".csv");
	header( "Content-disposition: filename=".$filename.".csv");
	print $csv_output;
	exit;