<?php

// Tap into WordPress
require('../../../../wp-blog-header.php');

	// Get Reseller API Key
	$bb_empower_options_arr = get_option('bb_empower_options');
	$bb_agency_option_agencyname = $bb_empower_options_arr['bb_agency_option_agencyname'];
	$bb_agency_option_agencyemail = $bb_empower_options_arr['bb_agency_option_agencyemail'];
	$bb_agency_option_agencylogo = $bb_empower_options_arr['bb_agency_option_agencylogo'];

/* Prepare Request ----------------------------------------------- */

	// Get Form Data
	$intakeLeadNameFirst = $_POST['intakeLeadNameFirst'];
	$intakeLeadNameLast = $_POST['intakeLeadNameLast'];
	$intakeLeadEmail = $_POST['intakeLeadEmail'];
	$intakeLeadMessage = $_POST['intakeLeadMessage'];
	
	
		$intakeLeadNameFirst = "Rob";
		$intakeLeadNameLast = "Bertholf";
		$intakeLeadEmail = "rob@bertholf.com";
		$intakeLeadMessage = "Test maven media message";
	// Initialize Email Message
	$intakeLeadMailTo = "rob1@bertholf.com";
	$intakeLeadMailFrom  = "notify@e.mp";
	$intakeLeadSubject = "Quote request from Maven Media Marketing";
	$intakeLeadDetails = "You have received a quote request from <a href=\"mailto:". $intakeLeadEmail ."\">". $intakeLeadNameFirst ." ". $intakeLeadNameLast ."</a>:<br /><blockquote>". $intakeLeadMessage ."</blockquote><br /> <a href=\"https://e.mp\">View in EMP</a>";
	// Add Headers
	$headers = 'From: E.MP Notifications <'. $intakeLeadMailFrom . ">\r\n" .
		'Reply-To: no-reply@e.mp' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	// To send HTML mail, the Content-type header must be set
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Additional headers
	//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
	//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
	
	// Do it to it...
	mail($intakeLeadMailTo, $intakeLeadSubject, $intakeLeadDetails, $headers);

?>