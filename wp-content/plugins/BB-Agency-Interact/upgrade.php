<?php
global $wpdb;
$bb_agency_storedversion = get_option('bb_agency_version');
// *************************************************************************************************** //
// Set Default Values for Options
/*
	$bb_agencyinteract_options_arr = array(
		"bb_agencyineract_option_registerapproval" => 1,
		"bb_agencyineract_option_registerallow" => 1
		);
		$results = $wpdb->query("CREATE TABLE ". table_agencyinteract_subscription ." (
			SubscriberID BIGINT(20) NOT NULL AUTO_INCREMENT,
			ProfileID BIGINT(20) NOT NULL DEFAULT '0',
			SubscriberDateStart TIMESTAMP DEFAULT NOW(),
			SubscriberDateExpire DATE,
			SubscriberPurchasePrice DECIMAL(12,2),
			SubscriberPurchaseDetails TEXT,
			PRIMARY KEY (SubscriberID)
			);");
		// Subscriptions
		$results = $wpdb->query("CREATE TABLE ". table_agencyinteract_subscription_rates ." (
			SubscriptionRateID BIGINT(20) NOT NULL AUTO_INCREMENT,
			SubscriptionRateTitle VARCHAR(255),
			SubscriptionRateType VARCHAR(255),
			SubscriptionRateText TEXT,
			SubscriptionRateTerm INT(10) NOT NULL DEFAULT '1',
			SubscriptionRatePrice DECIMAL(12,2),
			PRIMARY KEY (SubscriptionRateID)
			);");
*/
?>