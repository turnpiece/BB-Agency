<?php 
/*
  Plugin Name: BB Agency
  Text Domain: bb-agency
  Plugin URI: http://rbplugin.com/wordpress/model-talent-agency-software/
  Description: Forked from RB Agency plugin and adapted for the Beautiful Bumps agency. With this plugin you can easily manage models' profiles and information.
  Author: Paul Jenkins
  Author URI: http://turnpiece.com/
  Version: 0.0.1
*/
$bb_agency_VERSION = "2.0.0"; // starter
if(!get_option("bb_agency_version")){  add_option("bb_agency_version", $bb_agency_VERSION , '', 'no');  update_option("bb_agency_version", $bb_agency_VERSION);}
	
if (!session_id()) session_start();

// Requires 2.8 or more
if ( ! isset($GLOBALS['wp_version']) || version_compare($GLOBALS['wp_version'], '2.8', '<') ) { // if less than 2.8
	echo "<div class=\"error\" style=\"margin-top:30px;\"><p>This plugin requires WordPress version 2.8 or newer.</p></div>";
	return;
}

// Avoid direct calls to this file, because now WP core and framework has been used
	if ( !function_exists('add_action') ) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
	}

// Plugin Definitions
	define("bb_agency_BASENAME", plugin_basename(__FILE__) );  // bb-agency/bb-agency.php
	$bb_agency_WPURL = get_bloginfo("wpurl"); // http://domain.com/wordpress
	$bb_agency_WPUPLOADARRAY = wp_upload_dir(); // Array  $bb_agency_WPUPLOADARRAY['baseurl'] $bb_agency_WPUPLOADARRAY['basedir']
	define("bb_agency_BASEDIR", get_bloginfo("wpurl") ."/". PLUGINDIR ."/". dirname( plugin_basename(__FILE__) ) ."/" );  // http://domain.com/wordpress/wp-content/plugins/bb-agency/
	define("bb_agency_BASEREL", str_replace(get_bloginfo('url'), '', bb_agency_BASEDIR));  // /wordpress/wp-content/uploads/profile-media/
	define("bb_agency_BASEPATH", "/". PLUGINDIR ."/". dirname( plugin_basename(__FILE__) ) ."/" );  // wordpress/wp-content/plugins/bb-agency/
	define("bb_agency_UPLOADREL", str_replace(get_bloginfo('url'), '', $bb_agency_WPUPLOADARRAY['baseurl']) ."/profile-media/" );  // /wordpress/wp-content/uploads/profile-media/
	define("bb_agency_UPLOADDIR", $bb_agency_WPUPLOADARRAY['baseurl'] ."/profile-media/" );  // http://domain.com/wordpress/wp-content/uploads/profile-media/
	define("bb_agency_UPLOADPATH", $bb_agency_WPUPLOADARRAY['basedir'] ."/profile-media/" ); // /home/content/99/6048999/html/domain.com/wordpress/wp-content/uploads/profile-media/
	define("bb_agency_TEXTDOMAIN", basename(dirname( __FILE__ )) ); //   bb-agency
	define("bb_agency_MUMSTOBE_ID", 1); // id of mums to be data type
	define("bb_agency_AFTERBIRTH_ID", 2); // id of data type to move mums to be to once they've given birth
	define("bb_agency_CLIENTS_ID", 7); // id of clients
	// Clean Up:
	$pageURL = '';
 	if ($_SERVER["SERVER_PORT"] != "80") {
  		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 	} else {
  		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 	}

// Call Language Options

	// Check for WPMU installation
	if (!defined ('IS_WPMU')){
		global $wpmu_version;
		$is_wpmu = ((function_exists('is_multisite') and is_multisite()) or $wpmu_version) ? 1 : 0;
		define('IS_WPMU', $is_wpmu);
	}

	add_action('init', 'bb_agency_loadtranslation');
	function bb_agency_loadtranslation(){
		load_plugin_textdomain( bb_agency_TEXTDOMAIN, false, basename( dirname( __FILE__ ) ) . '/translation/' ); 
	}
	

// *************************************************************************************************** //

// Set Table Names
	if (!defined("table_agency_casting"))
		define("table_agency_casting", "bb_agency_casting");
	if (!defined("table_agency_profile"))
		define("table_agency_profile", "bb_agency_profile");
	if (!defined("table_agency_profile_media"))
		define("table_agency_profile_media", "bb_agency_profile_media");
	if (!defined("table_agency_data_ethnicity"))
		define("table_agency_data_ethnicity", "bb_agency_data_ethnicity");
	if (!defined("table_agency_data_colorskin"))
		define("table_agency_data_colorskin", "bb_agency_data_colorskin");
	if (!defined("table_agency_data_coloreye"))
		define("table_agency_data_coloreye", "bb_agency_data_coloreye");
	if (!defined("table_agency_data_colorhair"))
		define("table_agency_data_colorhair", "bb_agency_data_colorhair");
	if (!defined("table_agency_data_gender"))
		define("table_agency_data_gender", "bb_agency_data_gender");
	if (!defined("table_agency_rel_taxonomy"))
		define("table_agency_rel_taxonomy", "bb_agency_rel_taxonomy");
	if (!defined("table_agency_data_type"))
		define("table_agency_data_type", "bb_agency_data_type");
	if (!defined("table_agency_customfields"))
		define("table_agency_customfields", "bb_agency_customfields");
	if (!defined("table_agency_customfield_mux"))
		define("table_agency_customfield_mux", "bb_agency_customfield_mux");
	if (!defined("table_agency_searchsaved"))
		define("table_agency_searchsaved", "bb_agency_searchsaved");
	if (!defined("table_agency_searchsaved_mux"))
		define("table_agency_searchsaved_mux", "bb_agency_searchsaved_mux");
	if (!defined("table_agency_savedfavorite"))
		define("table_agency_savedfavorite", "bb_agency_savedfavorite");	
	if (!defined("table_agency_castingcart"))
		define("table_agency_castingcart", "bb_agency_castingcart");
	if (!defined("table_agency_mediacategory"))
		define("table_agency_mediacategory", "bb_agency_mediacategory");
	if (!defined("table_agency_customfields_types"))
	define("table_agency_customfields_types", "bb_agency_customfields_types");				


// Declare Global WordPress Database Access
    global $wpdb;


// Do the tables exist?
	if ($wpdb->get_var("show tables like '". table_agency_profile ."'") == table_agency_profile) { // No, it doesn't
		// Time for a diaper change, call the upgrade script
		include_once(dirname(__FILE__).'/upgrade.php');
	}
	

// Declare Version
	$bb_agency_storedversion = get_option("bb_agency_version");
	$bb_agency_VERSION = get_option("bb_agency_version");	
	define("bb_agency_VERSION", $bb_agency_VERSION); // e.g. 1.0


// Call default functions
	include_once(dirname(__FILE__).'/functions.php');


// Now Call the Lanuage
	define("bb_agency_PROFILEDIR", get_bloginfo('wpurl') . bb_agency_getActiveLanguage() ."/profile/" ); // http://domain.com/wordpress/de/profile/

// *************************************************************************************************** //
	


// *************************************************************************************************** //
// Creating tables on plugin activation

	function bb_agency_install() {
		// Required for all WordPress database manipulations
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		// Ensure directory is setup
		if (!is_dir(bb_agency_UPLOADPATH)) {
			@mkdir(bb_agency_UPLOADPATH, 0755);
			@chmod(bb_agency_UPLOADPATH, 0777);
		}
           
		// Update the options in the database
		if(!get_option("bb_agency_options"))
		add_option("bb_agency_options",$bb_agency_options_arr);
		update_option("bb_agency_options",$bb_agency_options_arr);
		
		// Hold the version in a seprate option
		if(!get_option("bb_agency_version"))
		add_option("bb_agency_version", $bb_agency_VERSION);
		update_option("bb_agency_version", $bb_agency_VERSION);
		
		/*
		 * Fixed this so that tables will always be
		 * checked if created.
		 */
		// Creating the tables!
			$sql = "CREATE TABLE IF NOT EXISTS " . table_agency_profile . " (
				ProfileID BIGINT(20) NOT NULL AUTO_INCREMENT,
				ProfileUserLinked BIGINT(20) NOT NULL DEFAULT '0',
				ProfileGallery VARCHAR(255),
				ProfileContactDisplay VARCHAR(255),
				ProfileContactNameFirst VARCHAR(255),
				ProfileContactNameLast VARCHAR(255),
				ProfileGender VARCHAR(255),
				ProfileDateBirth DATE,
				ProfileDateDue DATE,
				ProfileLocationStreet VARCHAR(255),
				ProfileLocationCity VARCHAR(255),
				ProfileLocationState VARCHAR(255),
				ProfileLocationZip VARCHAR(255),
				ProfileLocationCountry VARCHAR(255),
				ProfileContactEmail VARCHAR(255),
				ProfileContactWebsite VARCHAR(255),
				ProfileContactPhoneHome VARCHAR(255),
				ProfileContactPhoneCell VARCHAR(255),
				ProfileContactPhoneWork VARCHAR(255),
				ProfileContactLinkTwitter VARCHAR(255),
				ProfileContactLinkFacebook VARCHAR(255),
				ProfileContactLinkYoutube VARCHAR(255),
				ProfileContactLinkFlickr VARCHAR(255),
				ProfileDateCreated TIMESTAMP DEFAULT NOW(),
				ProfileDateUpdated TIMESTAMP,
				ProfileDateViewLast TIMESTAMP,
				ProfileType VARCHAR(255),
				ProfileIsActive INT(10) NOT NULL DEFAULT '0',
				ProfileIsFeatured INT(10) NOT NULL DEFAULT '0',
				ProfileIsPromoted INT(10) NOT NULL DEFAULT '0',
				ProfileStatHits INT(10) NOT NULL DEFAULT '0',
				PRIMARY KEY (ProfileID)
				);";
			dbDelta($sql);
	
			// Setup > Profile Media
			$sql = "CREATE TABLE IF NOT EXISTS ".table_agency_profile_media." (
				ProfileID INT(10) NOT NULL DEFAULT '0',
				ProfileMediaID INT(10) NOT NULL AUTO_INCREMENT,
				ProfileMediaType VARCHAR(255),
				ProfileMediaTitle VARCHAR(255),
				ProfileMediaText TEXT,
				ProfileMediaURL VARCHAR(255),
				ProfileMediaPrimary INT(10) NOT NULL DEFAULT '0',
				ProfileMediaFeatured INT(10) NOT NULL DEFAULT '0',
				ProfileMediaOrder VARCHAR(55),
				PRIMARY KEY (ProfileMediaID)
				);";
			dbDelta($sql);
	
			// Setup > Classification
			$sql = "CREATE TABLE IF NOT EXISTS ".table_agency_data_type." (
				DataTypeID INT(10) NOT NULL AUTO_INCREMENT,
				DataTypeTitle VARCHAR(255),
				DataTypeTag VARCHAR(50),
				PRIMARY KEY (DataTypeID)
				);";
			dbDelta($sql);
			$results = $wpdb->query("INSERT INTO " . table_agency_data_type . " (DataTypeID, DataTypeTitle) VALUES ('','Model')");
			$results = $wpdb->query("INSERT INTO " . table_agency_data_type . " (DataTypeID, DataTypeTitle) VALUES ('','Talent')");

			// Setup > Taxonomy: Gender
			$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_data_gender ." (
				GenderID INT(10) NOT NULL AUTO_INCREMENT,
				GenderTitle VARCHAR(255),
				PRIMARY KEY (GenderID)
				);";
			dbDelta($sql);
			$results = $wpdb->query("INSERT INTO " . table_agency_data_gender . " (GenderID, GenderTitle) VALUES ('','Male')");
			$results = $wpdb->query("INSERT INTO " . table_agency_data_gender . " (GenderID, GenderTitle) VALUES ('','Female')");
	
			// Setup > Taxonomy: Actual Taxonomy
			$sql = "CREATE TABLE IF NOT EXISTS ".table_agency_rel_taxonomy." (
				ProfileID BIGINT(20) NOT NULL DEFAULT 0,
				term_taxonomy_id BIGINT(20) NOT NULL DEFAULT 0,
				PRIMARY KEY (ProfileID,term_taxonomy_id)
				);";
			dbDelta($sql);
	
			
	      	// Setup > Custom Field Types
			$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_customfields." (
				ProfileCustomID BIGINT(20) NOT NULL AUTO_INCREMENT,
				ProfileCustomTitle VARCHAR(255),
				ProfileCustomType INT(10) NOT NULL DEFAULT '0',
				ProfileCustomOptions TEXT,
				ProfileCustomView INT(10) NOT NULL DEFAULT '0',
				ProfileCustomOrder INT(10) NOT NULL DEFAULT '0',
				ProfileCustomShowGender INT(10) NOT NULL DEFAULT '0',
				ProfileCustomShowProfile INT(10) NOT NULL DEFAULT '1',
				ProfileCustomShowSearch INT(10) NOT NULL DEFAULT '1',
				ProfileCustomShowLogged INT(10) NOT NULL DEFAULT '1',
				ProfileCustomShowRegistration INT(10) NOT NULL DEFAULT '1',
				ProfileCustomShowAdmin INT(10) NOT NULL DEFAULT '1',
				PRIMARY KEY (ProfileCustomID)
				);";
			dbDelta($sql);
	 	// Populate Custom Fields
		$query = mysql_query("SELECT ProfileCustomTitle FROM ". table_agency_customfields ." WHERE ProfileCustomTitle = 'Ethnicity'");
		$count = mysql_num_rows($query);
		if ($count < 1) {
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (1, 'Ethnicity', 	3, '|African American|Caucasian|American Indian|East Indian|Eurasian|Filipino|Hispanic/Latino|Asian|Chinese|Japanese|Korean|Polynesian|Other|', 0, 1, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (2, 'Skin Tone', 	3, '|Fair|Medium|Dark|', 0, 2, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (3, 'Hair Color', 	3, '|Blonde|Black|Brown|Dark Brown|Light Brown|Red|Strawberry|Auburn|', 0, 3, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (4, 'Eye Color', 	3, '|Blue|Brown|Hazel|Green|Black|', 0, 4, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (5, 'Height', 		7, '3', 0, 5, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (6, 'Weight', 		7, '2', 0, 6, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (7, 'Shirt', 		1, '', 0, 8, 1, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (8, 'Waist', 		7, '1', 0, 9, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (9, 'Hips', 		7, '1', 0, 10, 2, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(10, 'Shoe Size', 	7, '1', 0, 11, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(11, 'Suit', 		3, '|36S|37S|38S|39S|40S|41S|42S|43S|44S|45S|46S|36R|38R|40R|42R|44R|46R|48R|50R|52R|54R|40L|42L|44L|46L|48L|50L|52L|54L|', 0, 7, 1, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(12, 'Inseam', 		7, '1', 0, 10, 1, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(13, 'Dress', 		3, '|2|4|6|8|10|12|14|16|18|', 0, 8, 2, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(14, 'Bust', 		3, '|32A|32B|32C|32D|32DD|34A|34B|34C|34D|34DD|36C|36D|36DD|', 0, 7, 2, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(15, 'Union', 		3, '|SAG/AFTRA|SAG ELIG|NON-UNION|', 0, 20, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(16, 'Experience', 	4, '', 0, 13, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(17, 'Language', 	1, '', 0, 14, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(18, 'Booking', 	4, '', 0, 15, 0, 1, 1, 0, 1, 0)");
		}
	
			// Setup > Custom Field Types > Mux Values
			$sql9mux = "CREATE TABLE IF NOT EXISTS ". table_agency_customfield_mux ." (
				ProfileCustomMuxID BIGINT(20) NOT NULL AUTO_INCREMENT,
				ProfileCustomID BIGINT(20) NOT NULL DEFAULT '0',
				ProfileID BIGINT(20) NOT NULL DEFAULT '0',
				ProfileCustomValue TEXT,
				PRIMARY KEY (ProfileCustomMuxID)
				);";
			dbDelta($sql9mux);
	
			// Setup > Search Saved
			$sql10 = "CREATE TABLE IF NOT EXISTS ". table_agency_searchsaved ." (
				SearchID BIGINT(20) NOT NULL AUTO_INCREMENT,
				SearchTitle VARCHAR(255),
				SearchType INT(10) NOT NULL DEFAULT '0',
				SearchProfileID TEXT,
				SearchOptions VARCHAR(255),
				SearchDate TIMESTAMP DEFAULT NOW(),
				PRIMARY KEY (SearchID)
				);";
			dbDelta($sql10);

			// Setup > Custom Field Types > Mux Values
			$sql10mux = "CREATE TABLE IF NOT EXISTS ". table_agency_searchsaved_mux ." (
				SearchMuxID BIGINT(20) NOT NULL AUTO_INCREMENT,
				SearchID BIGINT(20) NOT NULL DEFAULT '0',
				SearchMuxHash VARCHAR(255),
				SearchMuxToName VARCHAR(255),
				SearchMuxToEmail VARCHAR(255),
				SearchMuxSubject VARCHAR(255),
				SearchMuxMessage TEXT,
				SearchMuxCustomValue VARCHAR(255),
				SearchMuxSent TIMESTAMP DEFAULT NOW(),
				PRIMARY KEY (SearchMuxID)
				);";
			dbDelta($sql10mux);
           	// Setup > Save Favorite
			$sql11 = "CREATE TABLE IF NOT EXISTS ". table_agency_savedfavorite." (
				SavedFavoriteID BIGINT(20) NOT NULL AUTO_INCREMENT,
				SavedFavoriteProfileID VARCHAR(255),
			      SavedFavoriteTalentID VARCHAR(255),
				PRIMARY KEY (SavedFavoriteID)
				);";
			dbDelta($sql11);

			
			// Setup > Add to Casting Cart
			$sql12 = "CREATE TABLE IF NOT EXISTS ". table_agency_castingcart." (
				CastingCartID BIGINT(20) NOT NULL AUTO_INCREMENT,
				CastingCartProfileID VARCHAR(255),
			      CastingCartTalentID VARCHAR(255),
				PRIMARY KEY (CastingCartID)
				);";
			dbDelta($sql12);		
			// Setup > Add to Casting Cart
			$sql13 = "CREATE TABLE IF NOT EXISTS ". table_agency_mediacategory." (
				MediaCategoryID BIGINT(20) NOT NULL AUTO_INCREMENT,
				MediaCategoryTitle VARCHAR(255),
				MediaCategoryGender VARCHAR(255),
			      MediaCategoryOrder VARCHAR(255),
				PRIMARY KEY (MediaCategoryID)
				);";
			mysql_query($sql13);	
		  
		   // Setup > Custom Field Types
			$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_customfields_types." (
				ProfileCustomTypesID BIGINT(20) NOT NULL AUTO_INCREMENT,
				ProfileCustomID BIGINT(20) NOT NULL,
				ProfileCustomTitle VARCHAR(255),
				ProfileCustomTypes VARCHAR(255),
				PRIMARY KEY (ProfileCustomTypesID)
				);";
			dbDelta($sql);		
	}
//Activate Install Hook
register_activation_hook(__FILE__,'bb_agency_install');
		

// *************************************************************************************************** //
// Register Administrative Settings

if ( is_admin() ){

	/****************  Add Options Page Settings Group ***************/

	add_action('admin_init', 'bb_agency_register_settings');
		// Register our Array of settings
		function bb_agency_register_settings() {
			register_setting('bb-agency-settings-group', 'bb_agency_options'); //, 'bb_agency_options_validate'
			register_setting( 'bb-agency-dummy-settings-group', 'bb_agency_dummy_options' ); //, setup dummy profile options
		}
		// Validate/Sanitize Data
		function bb_agency_options_validate($input) {
			// Our first value is either 0 or 1
			//$input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );
			
			// Say our second option must be safe text with no HTML tags
			//$input['sometext'] =  wp_filter_nohtml_kses($input['sometext']);
			
			//return $input;
		}	

	
	/****************  Settings in Plugin Page ***********************/
	
	add_action( 'plugins_loaded', 'bb_agency_init' );
		// Initialize Settings
		function bb_agency_init() {
		  	if ( is_admin() ){
				add_action('admin_menu', 'bb_agency_addsettingspage');
		  	}
		}
		function bb_agency_on_load() {
			add_filter( 'plugin_action_links_' . bb_agency_BASENAME, 'bb_agency_filter_plugin_meta', 10, 2 );  
		}
		
		// Add Link to Admin Menu
		function bb_agency_filter_plugin_meta($links, $file) {
			if (empty($links))
				return;
			/* create link */
			if ( $file == bb_agency_BASENAME ) {
				array_unshift(
					$links,
					sprintf( '<a href="tools.php?page=%s">%s</a>', bb_agency_BASENAME, __('Settings') )
				);
			}
			return $links;
		}
		
		function bb_agency_addsettingspage() {
			if ( !current_user_can('update_core') )
				return;
			$pagehook = add_management_page( __("BB Agency", bb_agency_TEXTDOMAIN), __("BB Agency", bb_agency_TEXTDOMAIN), 'update_core', bb_agency_BASENAME, 'bb_agency_settings', '' );
			add_action( 'load-plugins.php', 'bb_agency_on_load' );
			//wp_enqueue_script('jquery');
		}
	
	
	
	/****************  Add Custom Meta Box to Pages/Posts  *********/
	
	add_action('admin_menu', 'bb_agency_add_custom_box');
		// Add Custom Meta Box to Posts / Pages
		function bb_agency_add_custom_box() {
		  	if( function_exists( 'add_meta_box' )) {
				add_meta_box( 'bb_agency_sectionid', __( 'Insert Profiles', bb_agency_TEXTDOMAIN), 
					'bb_agency_inner_custom_box', 'post', 'advanced' );
				add_meta_box( 'bb_agency_sectionid', __( 'Insert Profiles', bb_agency_TEXTDOMAIN), 
					'bb_agency_inner_custom_box', 'page', 'advanced' );
		   	} else {
				add_action('dbx_post_advanced', 'bb_agency_old_custom_box' );
				add_action('dbx_page_advanced', 'bb_agency_old_custom_box' );
		  	}
		}
	   
		/* Prints the inner fields for the custom post/page section */
		function bb_agency_inner_custom_box() {
		  // Use nonce for verification
		  echo '<input type="hidden" name="bb_agency_noncename" id="bb_agency_noncename" value="'. wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
			echo "<div class=\"submitbox\" id=\"add_ticket_box\">";
			?><script type="text/javascript">
				function create_profile_list(){

					var $bbagency = jQuery.noConflict();
					str='';

					gender=$bbagency('#bb_agency_gender').val();
					if(gender!=''&& gender!='')
					str+=' gender="'+gender+'"';
		
					age_start=$bbagency('#bb_agency_age_start').val();
					if(age_start!=''&& age_start!='')
					str+=' age_start="'+age_start+'"';
		
					age_stop=$bbagency('#bb_agency_age_stop').val();
					if(age_stop!=''&& age_stop!='')
					str+=' age_stop="'+age_stop+'"';
		
					type=$bbagency('#bb_agency_type').val();
					if(type!='')
					str+=' type="'+type+'"';		
		
					send_to_editor('[profile_list'+str+']');return;
				}

				function create_profile_search(){
					send_to_editor('[profile_search]');return;
				}
			</script>
			<?php
			echo "<table>\n";
			echo "	<tr><td>Type:</td><td><select id=\"bb_agency_type\" name=\"bb_agency_type\">\n";
					global $wpdb;
					$profileDataTypes = mysql_query("SELECT * FROM ". table_agency_data_type ."");
					echo "<option value=\"\">". __("Any Profile Type", bb_agency_TEXTDOMAIN) ."</option>\n";
					while ($dataType = mysql_fetch_array($profileDataTypes)) {
						if ($_SESSION['ProfileType']) {
							if ($dataType["DataTypeID"] ==  $ProfileType) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
						} else { $selectedvalue = ""; }
						echo "<option value=\"". $dataType["DataTypeID"] ."\"".$selectedvalue.">". $dataType["DataTypeTitle"] ." ". __("Only", bb_agency_TEXTDOMAIN) ."</option>";
					}
					echo "</select></td></tr>\n";
			echo "	<tr><td>". __("Starting Age", bb_agency_TEXTDOMAIN) .":</td><td><input type=\"text\" id=\"bb_agency_age_start\" name=\"bb_agency_age_start\" value=\"18\" /></td></tr>\n";
			echo "	<tr><td>". __("Ending Age", bb_agency_TEXTDOMAIN) .":</td><td><input type=\"text\" id=\"bb_agency_age_stop\" name=\"bb_agency_age_stop\" value=\"99\" /></td></tr>\n";
			echo "	<tr><td>". __("Gender", bb_agency_TEXTDOMAIN) .":</td><td>";
			echo "<select id=\"bb_agency_gender\" name=\"bb_agency_gender\">";
			$query= "SELECT GenderID, GenderTitle FROM " .  table_agency_data_gender . " GROUP BY GenderTitle ";
				
				echo "<option value=\"\">All Gender</option>";
				$queryShowGender = mysql_query($query);
				while($dataShowGender = mysql_fetch_assoc($queryShowGender)){
					echo "<option value=\"".$dataShowGender["GenderID"]."\" >".$dataShowGender["GenderTitle"]."</option>";
				 }
			echo "</select>";
			echo "</td></tr>\n";
			
			echo "</table>\n";
			echo "<p><input type=\"button\" onclick=\"create_profile_list()\" value=\"". __("Insert Profile List", bb_agency_TEXTDOMAIN) ."\" /></p>\n";
			echo "<p><input type=\"button\" onclick=\"create_profile_search()\" value=\"". __("Insert Search Form", bb_agency_TEXTDOMAIN) ."\" /></p>\n";
			echo "</div>\n";
		}
		
		/* Prints the edit form for pre-WordPress 2.5 post/page */
		function bb_agency_old_custom_box() {
		
		  echo '<div class="dbx-b-ox-wrapper">' . "\n";
		  echo '<fieldset id="bb_agency_fieldsetid" class="dbx-box">' . "\n";
		  echo "<div class=\"dbx-h-andle-wrapper\"><h3 class=\"dbx-handle\">". __("Profile", bb_agency_TEXTDOMAIN) ."</h3></div>";   
		  echo '<div class="dbx-c-ontent-wrapper"><div class="dbx-content">';
		  // output editing form
		  bb_agency_inner_custom_box();
		  // end wrapper
		  echo "</div></div></fieldset></div>\n";
		}

	
	
	/****************  Activate Admin Menu Hook ***********************/
	
	add_action('admin_menu','set_bb_agency_menu');
		//Create Admin Menu
		function set_bb_agency_menu(){
			add_menu_page( __("Agency", bb_agency_TEXTDOMAIN), __("Agency", bb_agency_TEXTDOMAIN), 1,"bb_agency_menu","bb_agency_dashboard","div");
			add_submenu_page("bb_agency_menu", __("Overview", bb_agency_TEXTDOMAIN), __("Overview", bb_agency_TEXTDOMAIN), 1,"bb_agency_menu","bb_agency_dashboard");
			add_submenu_page("bb_agency_menu", __("Manage Profiles", bb_agency_TEXTDOMAIN), __("Manage Profiles", bb_agency_TEXTDOMAIN), 7,"bb_agency_profiles","bb_agency_profiles");
			if (function_exists(bb_agencyinteract_approvemembers)) {
			add_submenu_page("bb_agency_menu", __("Approve Pending Profiles", bb_agency_TEXTDOMAIN), __("Approve Profiles", bb_agency_TEXTDOMAIN), 7,"bb_agencyinteract_approvemembers","bb_agencyinteract_approvemembers");
			}
			add_submenu_page("bb_agency_menu", __("Search &amp; Send Profiles", bb_agency_TEXTDOMAIN), __("Search Profiles", bb_agency_TEXTDOMAIN), 7,"bb_agency_search","bb_agency_search");
			add_submenu_page("bb_agency_menu", __("Saved Searches", bb_agency_TEXTDOMAIN), __("Saved Searches", bb_agency_TEXTDOMAIN), 7,"bb_agency_searchsaved","bb_agency_searchsaved");
			add_submenu_page("bb_agency_menu", __("Tools &amp; Reports", bb_agency_TEXTDOMAIN), __("Tools &amp; Reports", bb_agency_TEXTDOMAIN), 7,"bb_agency_reports","bb_agency_reports");
			add_submenu_page("bb_agency_menu", __("Edit Settings", bb_agency_TEXTDOMAIN), __("Settings", bb_agency_TEXTDOMAIN), 7,"bb_agency_settings","bb_agency_settings");
		}
		
		//Pages
		function bb_agency_dashboard(){
			include_once('admin/overview.php');
		}
		function bb_agency_profiles(){
			include_once('admin/profile.php');
		}
		function bb_agency_search(){
			include_once('admin/search.php');
		}
		function bb_agency_searchsaved(){
			include_once('admin/searchsaved.php');
		}
		function bb_agency_reports(){
			include_once('admin/reports.php');
		}
		function bb_agency_settings(){
			include_once('admin/settings.php');
		}
		function bb_agencyinteract_menu_approvemembers(){
			include_once('admin/profile-approve.php');
		}
}

// *************************************************************************************************** //
// Scripts

	// Remove All Known Scripts which effect
	add_action( 'wp_print_scripts', 'bb_agency_deregister_scripts', 100 );
		function bb_agency_deregister_scripts() {
			//lightbox
			wp_deregister_script('woo-shortcodes');
			//jquery
			wp_deregister_script('woocommerce_plugins');
			wp_deregister_script('woocommerce');
			wp_deregister_script('fancybox');
			wp_deregister_script('jqueryui');
			wp_deregister_script('wc_price_slider');
			wp_deregister_script('widgetSlider');
			wp_deregister_script('woo-feedback');
			wp_deregister_script('prettyPhoto');
			wp_deregister_script('general');


			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-script', bb_agency_BASEDIR .'/js/jquery-ui-1.10.3.custom.min.js', array('jquery') );
			wp_enqueue_style('jquery-ui-style', bb_agency_BASEDIR .'/js/jquery-ui-1.10.3.custom.min.css' );
		}

	add_action('wp_footer', 'bb_agency_wp_footer');
		function bb_agency_wp_footer() {
			echo "<script type=\"text/javascript\">\n";
			echo "jQuery(document).ready(function(){\n";
			echo "	jQuery('.bbdatepicker').datepicker({\n";
			echo "		dateFormat : 'yy-mm-dd'\n";
			echo "	});\n";
			echo "});\n";
			echo "</script>\n";
		}

// *************************************************************************************************** //
// Add Widgets

	// View Featured
	add_action('widgets_init', create_function('', 'return register_widget("bb_agency_widget_showpromoted");'));
		class bb_agency_widget_showpromoted extends WP_Widget {
			
			// Setup
			function bb_agency_widget_showpromoted() {
				$widget_ops = array('classname' => 'bb_agency_widget_showpromoted', 'description' => __("Displays promoted profiles", bb_agency_TEXTDOMAIN) );
				$this->WP_Widget('bb_agency_widget_showpromoted', __("BB Agency : Featured", bb_agency_TEXTDOMAIN), $widget_ops);
			}
		
			// What Displays
			function widget($args, $instance) {		
				extract($args, EXTR_SKIP);
				echo $before_widget;
				$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
					if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };		
				$count = $instance['count'];
					if ( empty( $count ) ) { $count = 1; };		
					
				if (function_exists('bb_agency_profilefeatured')) { 
				  $atts = array('count' => $count);
				  bb_agency_profilefeatured($atts); 
				} else {
					echo "Invalid Function.";
				}
				echo $after_widget;
			}
		
			// Update
			function update($new_instance, $old_instance) {				
				$instance = $old_instance;
				$instance['title'] = strip_tags($new_instance['title']);
				$instance['count'] = strip_tags($new_instance['count']);
				return $instance;
			}
		
			// Form
			function form($instance) {				
				$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
				$title = esc_attr($instance['title']);
				$count = esc_attr($instance['count']);
				?>
					<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
					<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number Shown:'); ?> <input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" /></label></p>
				<?php 
			}
		
		} // Featured


	// View Topics
	add_action('widgets_init', create_function('', 'return register_widget("bb_agency_widget_showsearch");'));
		class bb_agency_widget_showsearch extends WP_Widget {
			
			// Setup
			function bb_agency_widget_showsearch() {
				$widget_ops = array('classname' => 'bb_agency_widget_showsearch', 'description' => __("Displays profile search fields", bb_agency_TEXTDOMAIN) );
				$this->WP_Widget('bb_agency_widget_showsearch', __("BB Agency : Search", bb_agency_TEXTDOMAIN), $widget_ops);
			}
		
			// What Displays
			function widget($args, $instance) {		
				extract($args, EXTR_SKIP);
				echo $before_widget;
				$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
					if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };		
				$showlayout = $instance['showlayout'];
					if ( empty( $showlayout ) ) { $showlayout = "condensed"; };	
						
				if (function_exists('bb_agency_profilesearch')) { 
					$atts = array('profilesearch_layout' => $showlayout);
					bb_agency_profilesearch($atts);
				} else {
					echo "Invalid Function";
				}
				echo $after_widget;
			}
		
			// Update
			function update($new_instance, $old_instance) {				
				$instance = $old_instance;
				$instance['title'] = strip_tags($new_instance['title']);
				$instance['showlayout'] = strip_tags($new_instance['showlayout']);
				return $instance;
			}
		
			// Form
			function form($instance) {				
				$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
				$title = esc_attr($instance['title']);
				$showlayout = esc_attr($instance['showlayout']);
				?>
					<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
					<p><label for="<?php echo $this->get_field_id('showlayout'); ?>"><?php _e('Type:'); ?> <select id="<?php echo $this->get_field_id('showlayout'); ?>" name="<?php echo $this->get_field_name('showlayout'); ?>"><option value="advanced" <?php selected($showlayout, "advanced"); ?>>Advanced Search</option><option value="condensed" <?php selected($showlayout, "condensed"); ?>>Condensed Search</option></select></label></p>
				<?php 
			}
		
		} // class




// *************************************************************************************************** //
// Add Short Codes

	add_shortcode("category_list","bb_agency_shortcode_categorylist");
		function bb_agency_shortcode_categorylist($atts, $content = null){
			ob_start();
			bb_agency_categorylist($atts);
			$output_string=ob_get_contents();;
			ob_end_clean();
			return $output_string;
		}

	add_shortcode("profile_list","bb_agency_shortcode_profilelist");
		function bb_agency_shortcode_profilelist($atts, $content = null){
			ob_start();
			bb_agency_profilelist($atts);
			$output_string=ob_get_contents();;
			ob_end_clean();
			return $output_string;
		}
	
	add_shortcode("profile_search","bb_agency_shortcode_profilesearch");
		function bb_agency_shortcode_profilesearch($atts, $content = null){
			ob_start();
			bb_agency_profilesearch($atts);
			$output_string=ob_get_contents();;
			ob_end_clean();
			return $output_string;
		}
  /*/
   * ======================== RB Agency Tool Tip===============
   * 
  /*/
  
  
		
	if(is_admin()){
		/*
		 * just not to get the tooltip error
		 */
		 $bb_agency_options_arr = get_option('bb_agency_options');
		 if($bb_agency_options_arr == ""){
				 $bb_agency_options_arr["bb_agency_options_showtooltip"] = 1;
				 update_option('bb_agency_options',$bb_agency_options_arr);
		 }
    if( $bb_agency_options_arr != "" || is_array($bb_agency_options_arr)){    	
		 $bb_agency_options_showtooltip = $bb_agency_options_arr["bb_agency_options_showtooltip"];
		 
		if(!@in_array("bb_agency_options_showtooltip",$bb_agency_options_arr) && $bb_agency_options_showtooltip == 0){	 
			$bb_agency_options_arr["bb_agency_options_showtooltip"] = 1;
			update_option('bb_agency_options',$bb_agency_options_arr);
			wp_enqueue_style('wp-pointer');
			wp_enqueue_script('wp-pointer');
			function  add_js_code(){
				?>
				<script type="text/javascript">
				jQuery(document).ready( function($) {
					
				var options = {"content":"<h3>BB Agency Plugin</h3><p>Thanks for installing the BB Plugin. We hope you find it useful.  Lets <a href=\'<?php echo admin_url("admin.php?page=bb_agency_settings&ConfigID=1"); ?>\'>check your settings</a> before we get started.</p>","position":{"edge":"left","align":"center"}};
				if ( ! options )
					return;
					options = $.extend( options, {
						close: function() {
						//to do
						}
					});
					<?php if(isset($_GET["page"])!="bb_agency_menu" && isset($_GET["page"]) !="bb_agency_settings") { ?>
					$('#toplevel_page_bb_agency_menu').pointer( options ).pointer("open");
					<?php } elseif(isset($_GET["page"])=="bb_agency_menu" && isset($_GET["page"]) !="bb_agency_settings") { ?>
					$('#toplevel_page_bb_agency_menu li a').each(function(){
						if($(this).text() == "Settings"){
						   $(this).fadeOut().pointer( options ).pointer("open").fadeIn();	
						   $(this).css("background","#EAF2FA");
						}
					});
					<?php } ?>
				});
				</script>
				';
				<?php
			}
			add_action("admin_footer","add_js_code");
		}
	 }
	}




 /*/



   *================ Notify Admin installation report ==============================
  /*/ 


$running = true;
function bb_agency_notify_installation(){

    include_once(ABSPATH . 'wp-includes/pluggable.php');		
	$json_url = 'http://agency.rbplugin.com/rb-license-checklist/';
	
	$client_domain = network_site_url('/');
    $client_sitename = get_bloginfo( 'name' );
	$client_admin_email = get_bloginfo('admin_email');
    $client_plugin_version = get_option('bb_agency_version');
	
	 
	$data = array(
	"client_domain" => $client_domain,
	"client_admin_email"  => $client_admin_email,
	"client_sitename" =>$client_sitename,
	"client_plugin_version" => $client_plugin_version,
	"client_plugin_name" =>"BB Plugin");                                                                    
	$data_string = json_encode($data);
		if(function_exists("bb_agencyinteract_install")){
			$client_interact_exist = get_option('bb_agency_version');	
			array_push($data,array("client_interact_exist" => $client_interact_exist)); 
		}
		
	// Initializing curl
	$ch = curl_init( $json_url );
	 
	// Configuring curl options
	$options = array(
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
	CURLOPT_POSTFIELDS => $data_string
	);
	 
	// Setting curl options
	curl_setopt_array( $ch, $options );
	 
	// Getting results
	$result =  curl_exec($ch); // Getting jSON result string
	$isReported = get_option("bb_agency_notify");	
         
	if($result){
	
		$message .= "RB Plugin was installed in the server that is not a member of or registered to the list of clients.". "\r\n\r\n";
		$message .= sprintf('Domain: %s',$client_domain). "\r\n\r\n";  
		$message .= sprintf('Date: %s',date('l jS \of F Y h:i:s A')) . "\r\n\r\n";  
		$message .= sprintf('Admin Email: %s', get_option('admin_email')) . "\r\n";  
  		
		$headers = array();
		$headers[] = 'Cc: Rob <rob@clearlym.com>';
		$headers[] = 'Cc: Operations <operations@clearlym.com>'; // note you can just use a simple email address           
		
	//	wp_mail("champ.kazban25@gmail.com", sprintf('RB Plugin Installed - Unknown Server/Domain[%s]', get_option('blogname')), $message,$headers);
	}			
}
register_activation_hook(__FILE__,"bb_agency_notify_installation");



/****************************************************************/
//Uninstall
	function bb_agency_uninstall() {
		
		register_uninstall_hook(__FILE__, 'bb_agency_uninstall_action');
		function bb_agency_uninstall_action() {
			//delete_option('create_my_taxonomies');
		}
	
		// Drop the tables
		global $wpdb;	// Required for all WordPress database manipulations
	
		$wpdb->query("DROP TABLE " . table_agency_casting);
		$wpdb->query("DROP TABLE " . table_agency_profile);
		$wpdb->query("DROP TABLE " . table_agency_profile_media);
		$wpdb->query("DROP TABLE " . table_agency_data_gender);
		$wpdb->query("DROP TABLE " . table_agency_rel_taxonomy);
		$wpdb->query("DROP TABLE " . table_agency_data_type);
		$wpdb->query("DROP TABLE " . table_agency_customfields);
		$wpdb->query("DROP TABLE " . table_agency_customfield_mux);
		$wpdb->query("DROP TABLE " . table_agency_searchsaved);
		$wpdb->query("DROP TABLE " . table_agency_searchsaved_mux);

		// Final Cleanup
		delete_option('bb_agency_options');
		
		$thepluginfile = "bb-agency/bb-agency.php";
		$current = get_settings('active_plugins');
		array_splice($current, array_search( $thepluginfile, $current), 1 );
		update_option('active_plugins', $current);
		do_action('deactivate_' . $thepluginfile );
	
		echo "<div style=\"padding:50px;font-weight:bold;\"><p>". __("Almost done...", bb_agency_TEXTDOMAIN) ."</p><h1>". __("One More Step", bb_agency_TEXTDOMAIN) ."</h1><a href=\"plugins.php?deactivate=true\">". __("Please click here to complete the uninstallation process", bb_agency_TEXTDOMAIN) ."</a></h1></div>";
		die;
	}
?>