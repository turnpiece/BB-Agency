<?php 
session_start();

// Get Profile
$SearchMuxHash = get_query_var('target');

get_header();

$bb_agency_option_profilenaming = bb_agency_get_option('bb_agency_option_profilenaming');

echo "<div id=\"container\" class=\"one-column\">\n";
echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";

	echo " <div id=\"profile-private\">\n";
	
	if (isset($SearchMuxHash)) {
	
		global $wpdb;
		
		$_SESSION['SearchMuxHash'] = $SearchMuxHash;
		
		$query = "SELECT search.`SearchTitle`, search.`SearchProfileID`, search.`SearchOptions`, searchsent.`SearchMuxHash` FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.`SearchID` = searchsent.`SearchID` WHERE searchsent.`SearchMuxHash` = \"". $SearchMuxHash ."\"";

		$results = $wpdb->get_results( $query );
		$count = count($results);

		foreach ($results as $data) {                     
			if (function_exists('bb_agency_profile_list')) { 
				$atts = array("pagingperpage" => 9999, "getprofile_saved" => $data->SearchProfileID);
				bb_agency_profile_list($atts); 
			}
		}

	}
	if (empty($SearchMuxHash) || ($count == 0)) {
		echo "<strong>". __("No search results found.  Please check link again.", bb_agency_TEXTDOMAIN) ."</strong>";
	}

	echo "  <div style=\"clear: both;\"></div>\n";
	echo " </div>\n";
echo "  </div>\n";
echo "</div>\n";
	
//get_sidebar(); 
get_footer(); 
?>