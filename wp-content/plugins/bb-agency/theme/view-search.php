<?php

// *************************************************************************************************** //
// Gobble Up The Variables, Set em' Sessions
foreach ($_REQUEST as $key => $value) {
  	if (substr($key, 0, 9) != "ProfileID") {
		$_SESSION[$key] = $value;  //$$key = $value;
  	}
}



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
	if ((isset($_REQUEST['cnf']) && !empty($_REQUEST['cnf'])) || isset($_REQUEST['cnl']) && !empty($_REQUEST['cnl'])) {
	  	if (isset($_REQUEST['cnf']) && !empty($_REQUEST['cnf'])) {
			$filterArray['profilecontactnamefirst'] = $_REQUEST['cnf'];
	  	}
	  	if (isset($_REQUEST['cnl']) && !empty($_REQUEST['cnl'])) {
			$filterArray['profilecontactnamelast'] = $_REQUEST['cnl'];
	  	}
	}

	// Location
	if (isset($_REQUEST['ProfileLocationCity']) && !empty($_REQUEST['ProfileLocationCity'])) {
		$filterArray['profilelocationcity'] = $_REQUEST['ProfileLocationCity'];
	}

	// Type
	if (isset($_REQUEST['t']) && !empty($_REQUEST['t'])) {
		$filterArray['profiletype'] = $_REQUEST['t'];
	}

	// Gender
	if (isset($_REQUEST['g']) && !empty($_REQUEST['g'])) {
		$filterArray['profilegender'] = $_REQUEST['g'];
	}
	
	// Height
	if (isset($_REQUEST['sh_min']) && !empty($_REQUEST['sh_min'])) {
		$filterArray['profilestatheight_min'] = $_REQUEST['sh_min'];
	}
	if (isset($_REQUEST['sh_max']) && !empty($_REQUEST['sh_max'])) {
		$filterArray['profilestatheight_max'] = $_REQUEST['sh_max'];
	}

	// Weight
	if (isset($_REQUEST['sw_min']) && !empty($_REQUEST['sw_min'])) {
		$filterArray['profilestatweight_min'] = $_REQUEST['sw_min'];
	}
	if (isset($_REQUEST['sw_max']) && !empty($_REQUEST['sw_max'])) {
		$filterArray['profilestatweight_max'] = $_REQUEST['sw_max'];
	}

	// Age
	if (isset($_REQUEST['a_min']) && !empty($_REQUEST['a_min'])) {
		$filterArray['profileage_min'] = $_REQUEST['a_min'];
	}
	if (isset($_REQUEST['a_max']) && !empty($_REQUEST['a_max'])) {
		$filterArray['profileage_max'] = $_REQUEST['a_max'];
	}

	// Date of birth
	if (isset($_REQUEST['dob_min']) && !empty($_REQUEST['dob_min'])) {
		$filterArray['profiledatebirth_min'] = $_REQUEST['dob_min'];
	}
	if (isset($_REQUEST['dob_max']) && !empty($_REQUEST['dob_max'])) {
		$filterArray['profiledatebirth_max'] = $_REQUEST['dob_max'];
	}

	// Due date
	if (isset($_REQUEST['dd_min']) && !empty($_REQUEST['dd_min'])) {
		$filterArray['profiledatedue_min'] = $_REQUEST['dd_min'];
	}
	if (isset($_REQUEST['dd_max']) && !empty($_REQUEST['dd_max'])) {
		$filterArray['profiledatedue_max'] = $_REQUEST['dd_max'];
	}

	// City
	if (isset($_REQUEST['c']) && !empty($_REQUEST['c'])) {
		$filterArray['profilecity'] = $_REQUEST['c'];
	}		

	// State
	if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
		$filterArray['profilestate'] = $_REQUEST['s'];
	}		

	// ZIP
	if (isset($_REQUEST['z']) && !empty($_REQUEST['z'])) {
		$filterArray['profilezip'] = $_REQUEST['z'];
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
                     
				if (function_exists('bb_agency_profilelist')) { 
					bb_agency_profilelist($filterArray); 
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