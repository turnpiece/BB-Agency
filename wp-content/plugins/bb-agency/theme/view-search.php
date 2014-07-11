<?php

// *************************************************************************************************** //
// Gobble Up The Variables, Set em' Sessions
foreach ($_REQUEST as $key => $value) {
  	if (substr($key, 0, 9) != "ProfileID") {
		$_SESSION[$key] = $value;  //$$key = $value;
  	}
}

?><pre><?php print_r($_REQUEST) ?></pre><?php



// *************************************************************************************************** //
// Get Search Results

if ($_REQUEST["action"] == "search") {
		
	// Sort By
	$sort = "";
	if (isset($_REQUEST['sort']) && !empty($_REQUEST['sort'])) {
		$sort = $_REQUEST['sort'];
	}
	else {
		$sort = "profile.ProfileContactNameFirst";
	}

	// Limit
	if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])) {
	}
	else {
		$limit = " LIMIT 0,100";
	}

	// Sort Order
	$dir = "";
	if (isset($_REQUEST['dir']) && !empty($_REQUEST['dir'])) {
		$dir = $_REQUEST['dir'];
		if ($dir == "desc" || !isset($dir) || empty($dir)) {
		   	$sortDirection = "asc";
	   	} else {
		   	$sortDirection = "desc";
		} 
	} else {
	   	$sortDirection = "desc";
	   	$dir = "asc";
	}

	// Filter
	$filterArray = array();

	// Name
	if ((isset($_REQUEST['fname']) && !empty($_REQUEST['fname'])) || 
		isset($_REQUEST['lname']) && !empty($_REQUEST['lname'])) {
	  	if (isset($_REQUEST['fname']) && !empty($_REQUEST['fname'])) {
			$filterArray['fname'] = $_REQUEST['fname'];
	  	}
	  	if (isset($_REQUEST['lname']) && !empty($_REQUEST['lname'])) {
			$filterArray['lname'] = $_REQUEST['lname'];
	  	}
	}

	// Location
	if (isset($_REQUEST['ProfileLocationCity']) && !empty($_REQUEST['ProfileLocationCity'])) {
		$filterArray['profilelocationcity'] = $_REQUEST['ProfileLocationCity'];
	}

	// Type
	if (isset($_REQUEST['type']) && !empty($_REQUEST['type'])) {
		$filterArray['profiletype'] = $_REQUEST['type'];
	}

	// Gender
	if (isset($_REQUEST['gender']) && !empty($_REQUEST['gender'])) {
		$filterArray['profilegender'] = $_REQUEST['gender'];
	}
	
	// Height
	if (isset($_REQUEST['height_min']) && !empty($_REQUEST['height_min'])) {
		$filterArray['profilestatheight_min'] = $_REQUEST['height_min'];
	}
	if (isset($_REQUEST['height_max']) && !empty($_REQUEST['height_max'])) {
		$filterArray['profilestatheight_max'] = $_REQUEST['height_max'];
	}

	// Weight
	if (isset($_REQUEST['weight_min']) && !empty($_REQUEST['weight_min'])) {
		$filterArray['profilestatweight_min'] = $_REQUEST['weight_min'];
	}
	if (isset($_REQUEST['weight_max']) && !empty($_REQUEST['weight_max'])) {
		$filterArray['profilestatweight_max'] = $_REQUEST['weight_max'];
	}

	// Age
	if (isset($_REQUEST['age_from']) && !empty($_REQUEST['age_from'])) {
		$filterArray['age_from'] = $_REQUEST['age_from'];
	}
	if (isset($_REQUEST['age_to']) && !empty($_REQUEST['age_to'])) {
		$filterArray['age_to'] = $_REQUEST['age_to'];
	}

	// Date of birth
	if (isset($_REQUEST['dob_min']) && !empty($_REQUEST['dob_min'])) {
		$filterArray['dob_min'] = $_REQUEST['dob_min'];
	}
	if (isset($_REQUEST['dob_max']) && !empty($_REQUEST['dob_max'])) {
		$filterArray['dob_max'] = $_REQUEST['dob_max'];
	}

	if (bb_agency_SITETYPE == 'bumps') {
		// Due date
		if (isset($_REQUEST['dd_min']) && !empty($_REQUEST['dd_min'])) {
			$filterArray['dd_min'] = $_REQUEST['dd_min'];
		}
		if (isset($_REQUEST['dd_max']) && !empty($_REQUEST['dd_max'])) {
			$filterArray['dd_max'] = $_REQUEST['dd_max'];
		}
	}

	// City
	if (isset($_REQUEST['city']) && !empty($_REQUEST['city'])) {
		$filterArray['profilecity'] = $_REQUEST['city'];
	}		

	// State
	if (isset($_REQUEST['state']) && !empty($_REQUEST['state'])) {
		$filterArray['profilestate'] = $_REQUEST['state'];
	}		

	// ZIP
	if (isset($_REQUEST['zip']) && !empty($_REQUEST['zip'])) {
		$filterArray['profilezip'] = $_REQUEST['zip'];
	}
	
	// Custom Fields
	foreach ($_POST as $key =>$val) {
	
		if (substr($key,0,15) == "ProfileCustomID") {
	      	if (is_array($val)) {
				if (count($val)>1) {	
					$filterArray[$key] = implode(",",$val);	
			 	} else {
				 	if (!empty($val)) {
						$filterArray[$key] = $val;
					}
			 	}
			} else {
		 		$filterArray[$key] = $val;
			}
		}
	}
	
	// Pagination
	$filterArray['paging'] = 1;
	$filterArray['pagingperpage'] = 1000; 

	?><pre><?php print_r($filterArray) ?></pre><?php
}

// *************************************************************************************************** //
// GET HEADER 

get_header(); ?>
<div id="primary" class="eight column">
    <div id="content" role="main" class="transparent">
		<div id="profile-search">
			<h1 class="entry-title">
			<?php if ($_REQUEST["action"] == "search") : ?>
		  	<?php _e("Search Results", bb_agency_TEXTDOMAIN) ?>
			<?php else : ?>
		 	<?php _e("Advanced Search", bb_agency_TEXTDOMAIN) ?>
			<?php endif; ?>
			</h1>
	  		<div class="cb"></div>
			<div id="profile-search-results">
				<?php if ($_REQUEST["action"] == "search") :
                     
				if (function_exists('bb_agency_profile_list')) { 
					bb_agency_profile_list($filterArray); 
				}
				?>
				<?php else : ?>
				<strong><?php _e("No search criteria selected.", bb_agency_TEXTDOMAIN) ?></strong>
				<?php endif; ?>
			</div><!-- #profile-search-results -->
		</div><!-- #profile-search -->
	</div><!-- #content -->
</div><!-- #primary -->
<?php get_footer() ?>