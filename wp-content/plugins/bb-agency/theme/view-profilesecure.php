<?php 
session_start();

// Get Profile
$SearchMuxHash = get_query_var('target');

get_header();

	$bb_agency_options_arr = get_option('bb_agency_options');
		$bb_agency_option_profilenaming 		= $bb_agency_options_arr['bb_agency_option_profilenaming'];

	echo "<div id=\"container\" class=\"one-column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";

		echo " <div id=\"profile-private\">\n";
		
		if (isset($SearchMuxHash)) {
		
			
			$_SESSION['SearchMuxHash'] = $SearchMuxHash;
			
			$query = "SELECT search.SearchTitle, search.SearchProfileID, search.SearchOptions, searchsent.SearchMuxHash FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = searchsent.SearchID WHERE searchsent.SearchMuxHash = \"". $SearchMuxHash ."\"";
			$results = mysql_query($query) or die ( __("Error, query failed", bb_agency_TEXTDOMAIN ));
			$count = mysql_num_rows($results);

			while ($data = mysql_fetch_array($results)) {
				 $SearchProfileID = $data['SearchProfileID'];
                         
				if (function_exists('bb_agency_profilelist')) { 
					$atts = array("pagingperpage" => 9999, "getprofile_saved" => $SearchProfileID);
					bb_agency_profilelist($atts); 
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