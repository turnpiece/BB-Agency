<?php
global $wpdb, $bb_agency_CURRENT_TYPE_ID;

$bb_agency_option_profilenaming = bb_agency_get_option('bb_agency_option_profilenaming');
$bb_agency_option_unittype = bb_agency_get_option('bb_agency_option_unittype');

// Custom fields display options
$bb_agency_option_customfields_profilepage = bb_agency_get_option('bb_agency_option_customfield_profilepage');
$bb_agency_option_customfields_searchpage = bb_agency_get_option('bb_agency_option_customfield_searchpage');
$bb_agency_option_customfields_loggedin_all = bb_agency_get_option('bb_agency_option_customfield_loggedin_all');
$bb_agency_option_customfields_loggedin_admin = bb_agency_get_option('bb_agency_option_customfield_loggedin_admin');

if (!empty($bb_agency_CURRENT_TYPE_ID)) 
	$_SESSION['ProfileType'] = $bb_agency_CURRENT_TYPE_ID;

if (isset($_REQUEST['t']) && !empty($_REQUEST['t'])) { 
	$_SESSION['ProfileType'] = $_REQUEST['t']; 
}
if (isset($DataTypeID) && !empty($DataTypeID)) { 
	$_SESSION['ProfileType'] = $DataTypeID; 
}
if (isset($_REQUEST['g']) && !empty($_REQUEST['g'])) {  
	$_SESSION['ProfileGender'] = $_REQUEST['g']; 
}

// fix advanced search to include
// custom fields from search fields
if (isset($_GET['srch'])){
   $profilesearch_layout = "advanced"; 	
}

if ($profilesearch_layout == "condensed" || $profilesearch_layout == "simple") : ?>
<div id="profile-search-form-condensed" class="rbsearch-form">
    <form method="post" id="search-form-condensed" action="<?php echo get_bloginfo("wpurl") ?>/profile-search/">
        <input type="hidden" name="action" value="search" />
	 	<div class="search-field single">
 			<label for="ProfileFirstName"><?php _e("First Name", bb_agency_TEXTDOMAIN) ?></label>
 			<input type="text" id="ProfileContactNameFirst" name="cnf" value="<?php echo $_SESSION["ProfileContactNameFirst"] ?>" />
	 	</div>		
		<div class="search-field single">
		    <label for="ProfileType"><?php _e("Type", bb_agency_TEXTDOMAIN) ?></label>
		    <select name="ProfileType" id="ProfileType">\              
				<option value=""><?php _e("Any Profile Type", bb_agency_TEXTDOMAIN) ?></option>
				<?php
					$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID <> ".bb_agency_CLIENTS_ID." ORDER BY DataTypeTitle DESC";
					$results2 = mysql_query($query);
					while ($dataType = mysql_fetch_array($results2)) :
						if ($_SESSION['ProfileType']) {
							if ($dataType["DataTypeID"] ==  $_SESSION['ProfileType']) { 
								$selectedvalue = " selected"; 
							} else { 
								$selectedvalue = ""; 
							} 
						} else { 
							$selectedvalue = ""; 
						}
						?>
				<option value="<?php echo $dataType["DataTypeID"] ?>"<?php echo $selectedvalue ?>><?php echo $dataType["DataTypeTitle"] ?></option>
					<?php endwhile; ?>
		    </select>
		</div>               
		<div class="search-field submit">
			<input type="submit" value="<?php _e("Search Profiles", bb_agency_TEXTDOMAIN) ?>" class="button-primary" onclick="this.form.action='<?php echo get_bloginfo("wpurl") ?>/profile-search/'" />
	
			<input type="submit" name="advanced_search" value="<?php _e("Advanced Search", bb_agency_TEXTDOMAIN) ?>" class="button-primary" onclick="this.form.action='<?php echo get_bloginfo("wpurl") ?>/search/?srch=1'" />
		</div>
    </form>
</div>
<?php else : // Advanced ?>
   
 <form method="post" id="search-form-advanced" action="". get_bloginfo("wpurl") ."/profile-search/" class="rbsearch-form">
	<input type="hidden" name="page" id="page" value="bb_agency_search" />
	<input type="hidden" name="action" value="search" />
	<div class="search-field single">
		<label for="cnf"><?php _e("First Name", bb_agency_TEXTDOMAIN) ?></label>
		<input type="text" id="ProfileContactNameFirst" name="cnf" value="<?php echo $_SESSION["ProfileContactNameFirst"] ?>" />
	</div>
    <div class="search-field single">
       <label for="t"><?php _e("Type", bb_agency_TEXTDOMAIN) ?></label>\
		<select name="t" id="ProfileType">\               
			<option value=""><?php _e("Any Profile Type", bb_agency_TEXTDOMAIN) ?></option>
			<?php
				$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID <> ".bb_agency_CLIENTS_ID." ORDER BY DataTypeTitle DESC";
				$results2 = mysql_query($query);
				while ($dataType = mysql_fetch_array($results2)) : ?>
			<option value="<?php echo $dataType["DataTypeID"] ?>" <?php selected($_SESSION['ProfileType'], $dataType["DataTypeID"], false) ?>><?php echo $dataType["DataTypeTitle"] ?></option>
				<?php endwhile; ?>
        </select>
    </div>

    <fieldset class="search-field multi">
        <legend><?php _e("Date of birth", bb_agency_TEXTDOMAIN) ?> (* <?php _e("applies to baby searches only", bb_agency_TEXTDOMAIN) ?>)</legend>

	    <div>
	        <label for="dob_min"><?php _e("From", bb_agency_TEXTDOMAIN) ?></label>
	        <input type="text" class="stubby bbdatepicker" id="ProfileDateBirth_min" name="dob_min" value="<?php echo $_SESSION['ProfileDateBirth_min'] ?>" />
	    </div>
	    <div>
	    	<label for="dob_max"><?php _e("To", bb_agency_TEXTDOMAIN) ?></label>\
	        <input type="text" class="stubby bbdatepicker" id="ProfileDateBirth_max" name="dob_max" value="<?php echo $_SESSION['ProfileDateBirth_max'] ?>" />
	    </div>
	</fieldset>

	<fieldset class="search-field multi">
	    <legend><?php _e("Due date", bb_agency_TEXTDOMAIN) ?></legend>
	    <div>
	        <label for="dd_min"><?php _e("From", bb_agency_TEXTDOMAIN) ?></label>
	        <input type="text" class="stubby bbdatepicker" id="ProfileDateDue_min" name="dd_min" value="<?php echo $_SESSION['ProfileDateDue_min'] ?>" />
	    </div>
	    <div>
	    	<label for="dd_max"><?php _e("To", bb_agency_TEXTDOMAIN) ?></label>\
	        <input type="text" class="stubby bbdatepicker" id="ProfileDateDue_max" name="dd_max" value="<?php echo $_SESSION['ProfileDateDue_max'] ?>" />
	    </div>
	</fieldset>							
	<?php	
	if ($bb_agency_option_customfields_searchpage == 1 || $bb_agency_option_customfield_profilepage == 1 OR $_POST['advanced_search']) { // Show on Search Page or Profile Page

		if (is_user_logged_in()) { // All with loggedin permissions

		} else { // All with non-logged here
		
			// Show custom fields to public
	    	if($_REQUEST["action"] != 'search' || $_REQUEST["action"] ==''){
		    	//include("include-custom-fields.php");
				$profilesearch_layout = "";
			}
		}
	}
	if (isset($_GET['srch'])) {
		include("include-custom-fields.php");
	}
	?>
	<div class="search-field submit">
		<input type="submit" value="<?php _e("Search Profiles", bb_agency_TEXTDOMAIN) ?>" class="button-primary" onclick="this.form.action='<?php echo get_bloginfo("wpurl") ?>/profile-search/'" />
		<input type="reset" class="button-primary" value="<?php _e("Empty Form", bb_agency_TEXTDOMAIN) ?>">
		<?php if (!isset($_GET[srch])) : ?>
		<input type="submit" name="advanced_search" value="<?php _e("Advanced Search", bb_agency_TEXTDOMAIN) ?>" class="button-primary" onclick="this.form.action='<?php echo get_bloginfo("wpurl") ?>/search/?srch=1'" />
		<?php else : ?>
		<input type="submit" name="basic_search" value="<?php _e("Basic Search", bb_agency_TEXTDOMAIN) ?>" class="button-primary" onclick="this.form.action='<?php echo get_bloginfo("wpurl") ?>/search'" />
		<?php endif; ?>
	</div>
	<?php if (isset($_GET['srch'])) { 
		echo'<div></div>'; 
		$style="style='margin-left:0px'"; 
	} else {
		$style="";
	} ?>
</form>
    	
<?php endif; ?>