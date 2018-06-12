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

if (isset($_REQUEST['type']) && !empty($_REQUEST['type'])) { 
	$_SESSION['ProfileType'] = $_REQUEST['type']; 
}
if (isset($DataTypeID) && !empty($DataTypeID)) { 
	$_SESSION['ProfileType'] = $DataTypeID; 
}
if (isset($_REQUEST['gender']) && !empty($_REQUEST['gender'])) {  
	$_SESSION['ProfileGender'] = $_REQUEST['gender']; 
}

// fix advanced search to include
// custom fields from search fields
if (isset($_GET['srch'])) {
   $profilesearch_layout = "advanced"; 	
}

if ($profilesearch_layout == "condensed" || $profilesearch_layout == "simple") : ?>
<div id="profile-search-form-condensed" class="bbsearch-form">
    <form method="post" id="search-form-condensed" action="<?php echo get_bloginfo("wpurl") ?>/profile-search/">
        <input type="hidden" name="action" value="search" />
	 	<div class="search-field single">
 			<label for="ProfileFirstName"><?php _e("First Name", bb_agency_TEXTDOMAIN) ?></label>
 			<input type="text" id="ProfileContactNameFirst" name="fname" value="<?php echo $_SESSION["ProfileContactNameFirst"] ?>" />
	 	</div>
	 	<?php // get data types
	 	$dataTypes = bb_agency_get_datatypes();
	 	if (!empty($dataTypes)) :
	 	if (count($dataTypes) > 1) : ?>	
		<div class="search-field single">
		    <label for="ProfileType"><?php _e("Type", bb_agency_TEXTDOMAIN) ?></label>
		    <select name="ProfileType" id="ProfileType">            
				<option value=""><?php _e("Any Profile Type", bb_agency_TEXTDOMAIN) ?></option>
                <?php foreach ($dataTypes as $type) : ?>
                <option value="<?php echo $type->DataTypeID ?>" <?php selected($type->DataTypeID, $ProfileType) ?>><?php echo $type->DataTypeTitle ?></option>
                <?php endforeach; ?>
		    </select>
		</div>
	    <?php else : $type = $dataTypes[0]; ?>
	    <input type="hidden" name="ProfileType" value="<?php echo $type->DataTypeID ?>" />
	    <?php endif; endif; ?>      
		<div class="search-field submit">
			<input type="submit" value="<?php _e("Search Profiles", bb_agency_TEXTDOMAIN) ?>" class="button-primary" onclick="this.form.action='<?php echo get_bloginfo("wpurl") ?>/profile-search/'" />
	
			<input type="submit" name="advanced_search" value="<?php _e("Advanced Search", bb_agency_TEXTDOMAIN) ?>" class="button-primary" onclick="this.form.action='<?php echo get_bloginfo("wpurl") ?>/search/?srch=1'" />
		</div>
    </form>
</div>
<?php else : // Advanced ?>
   
 <form method="post" id="search-form-advanced" action="<?php bloginfo('wpurl') ?>/profile-search/" class="bbsearch-form">
	<input type="hidden" name="page" id="page" value="bb_agency_search" />
	<input type="hidden" name="action" value="search" />
	<div class="search-field single">
		<label for="fname"><?php _e("First Name", bb_agency_TEXTDOMAIN) ?></label>
		<input type="text" id="ProfileContactNameFirst" name="fname" value="<?php echo $_SESSION["ProfileContactNameFirst"] ?>" />
	</div>
 	<?php // get data types
 	$dataTypes = bb_agency_get_datatypes();

 	if (!empty($dataTypes)) :
 	if (count($dataTypes) > 1) : ?>	
	<div class="search-field single">
	    <label for="ProfileType"><?php _e("Type", bb_agency_TEXTDOMAIN) ?></label>
	    <select name="ProfileType" id="ProfileType">            
			<option value=""><?php _e("Any Profile Type", bb_agency_TEXTDOMAIN) ?></option>
            <?php foreach ($dataTypes as $type) : ?>
            <option value="<?php echo $type->DataTypeID ?>" <?php selected($type->DataTypeID, $ProfileType) ?>><?php echo $type->DataTypeTitle ?></option>
            <?php endforeach; ?>
	    </select>
	</div>
    <?php else : $type = $dataTypes[0]; ?>
    <input type="hidden" name="ProfileType" value="<?php echo $type->DataTypeID ?>" />
    <?php endif; endif; ?> 

    <fieldset class="search-field multi">
    	<?php if (bb_agency_SITETYPE == 'bumps') : // date of birth ?>
        <legend><?php _e("Date of birth", bb_agency_TEXTDOMAIN) ?></legend>
	    <div>
	        <label for="dob_min"><?php _e("From", bb_agency_TEXTDOMAIN) ?></label>
	        <input type="text" class="stubby bbdatepicker" id="ProfileDateBirth_min" name="dob_min" value="<?php echo $_SESSION['ProfileDateBirth_min'] ?>" />
	    </div>
	    <div>
	    	<label for="dob_max"><?php _e("To", bb_agency_TEXTDOMAIN) ?></label>
	        <input type="text" class="stubby bbdatepicker" id="ProfileDateBirth_max" name="dob_max" value="<?php echo $_SESSION['ProfileDateBirth_max'] ?>" />
	    </div>
		<?php else : // age ?>
        <legend><?php _e("Age", bb_agency_TEXTDOMAIN) ?></legend>
	    <div>
	        <select name="age_from">
	        	<option value=""><?php _e("From", bb_agency_TEXTDOMAIN) ?></option>
	        <?php for ($i = 0; $i <= bb_agency_MAX_AGE; $i++) : ?>
	        	<option value="<?php echo $i ?>" <?php selected($i, $_SESSION['ProfileAge_min']) ?>><?php echo $i ?></option>
	        <?php endfor; ?>
	        </select>
	    </div>
	    <div>
	       	<select name="age_to">
	       		<option value=""><?php _e("To", bb_agency_TEXTDOMAIN) ?></option>
	        <?php for ($i = 0; $i <= bb_agency_MAX_AGE; $i++) : ?>
	        	<option value="<?php echo $i ?>" <?php selected($i, $_SESSION['ProfileAge_max']) ?>><?php echo $i ?></option>
	        <?php endfor; ?>
	        </select>
	    </div>
		<?php endif; ?>
	</fieldset>

	<?php if (bb_agency_SITETYPE == 'bumps') : ?>
	<fieldset class="search-field multi">
	    <legend><?php _e("Due date", bb_agency_TEXTDOMAIN) ?></legend>
	    <div>
	        <label for="dd_min"><?php _e("From", bb_agency_TEXTDOMAIN) ?></label>
	        <input type="text" class="stubby bbdatepicker" id="ProfileDateDue_min" name="dd_min" value="<?php echo $_SESSION['ProfileDateDue_min'] ?>" />
	    </div>
	    <div>
	    	<label for="dd_max"><?php _e("To", bb_agency_TEXTDOMAIN) ?></label>
	        <input type="text" class="stubby bbdatepicker" id="ProfileDateDue_max" name="dd_max" value="<?php echo $_SESSION['ProfileDateDue_max'] ?>" />
	    </div>
	</fieldset>							
	<?php endif;

	if ($bb_agency_option_customfields_searchpage == 1 || $bb_agency_option_customfield_profilepage == 1 OR $_POST['advanced_search']) { // Show on Search Page or Profile Page

		if (!is_user_logged_in()) {
			// Show custom fields to public
	    	if ($_REQUEST["action"] != 'search' || $_REQUEST["action"] =='') {
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
		<?php /*
		<?php if (!isset($_GET['srch'])) : ?>
		<input type="submit" name="advanced_search" value="<?php _e("Advanced Search", bb_agency_TEXTDOMAIN) ?>" class="button-primary" onclick="this.form.action='<?php echo get_bloginfo("wpurl") ?>/search/?srch=1'" />
		<?php else : ?>
		<input type="submit" name="basic_search" value="<?php _e("Basic Search", bb_agency_TEXTDOMAIN) ?>" class="button-primary" onclick="this.form.action='<?php echo get_bloginfo("wpurl") ?>/search'" />
		<?php endif; ?>
		*/ ?>
	</div>
	<?php if (isset($_GET['srch'])) { 
		echo'<div></div>'; 
		$style="style='margin-left:0px'"; 
	} else {
		$style="";
	} ?>
</form>
    	
<?php endif; ?>