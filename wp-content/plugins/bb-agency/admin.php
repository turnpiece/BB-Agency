<?php 

/**
 * admin message
 *
 * @param string $message
 * @param string $class
 *
 */
function bb_agency_admin_message($message, $class = 'updated') {
    ?>
    <div id="message" class="<?php echo $class ?>">
        <?php echo $message ?>
    </div>
    <?php
}

/****************  Add Options Page Settings Group ***************/

add_action('admin_init', 'bb_agency_register_settings');

// Register our Array of settings
function bb_agency_register_settings() {
    register_setting( 'bb-agency-settings-group', 'bb_agency_options' ); //, 'bb_agency_options_validate'
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

            age_from=$bbagency('#bb_agency_age_from').val();
            if(age_from!=''&& age_from!='')
            str+=' age_from="'+age_from+'"';

            age_to=$bbagency('#bb_agency_age_to').val();
            if(age_to!=''&& age_to!='')
            str+=' age_to="'+age_to+'"';

            type=$bbagency('#bb_agency_type').val();
            if(type!='')
            str+=' type="'+type+'"';        

            send_to_editor('[profile_list'+str+']');return;
        }

        function create_profile_search(){
            send_to_editor('[profile_search]');return;
        }
    </script>
    <table>
    <?php    
    // get data types
    $profileDataTypes = bb_agency_get_datatypes(false);
    if (!empty($profileDataTypes)) : 
    if (count($profileDataTypes) > 1) : ?>
        <tr>
            <td>Type:</td>
            <td>
                <select id="bb_agency_type" name="bb_agency_type">
                    <option value=""><?php _e("Any Profile Type", bb_agency_TEXTDOMAIN) ?></option>
                    <?php foreach ($profileDataTypes as $type) : ?>
                    <option value="<?php echo $type->DataTypeID ?>" <?php selected($type->DataTypeID, $ProfileType) ?>><?php echo $type->DataTypeTitle ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    <?php else : $type = $profileDataTypes[0]; ?>
    <input type="hidden" name="bb_agency_type" value="<?php echo $type->DataTypeID ?>" />
    <?php endif; endif;
    echo "  <tr><td>". __("Starting Age", bb_agency_TEXTDOMAIN) .":</td><td><input type=\"text\" id=\"bb_agency_age_from\" name=\"bb_agency_age_from\" value=\"18\" /></td></tr>\n";
    echo "  <tr><td>". __("Ending Age", bb_agency_TEXTDOMAIN) .":</td><td><input type=\"text\" id=\"bb_agency_age_to\" name=\"bb_agency_age_to\" value=\"99\" /></td></tr>\n";
    echo "  <tr><td>". __("Gender", bb_agency_TEXTDOMAIN) .":</td><td>";
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
    if (function_exists('bb_agencyinteract_approvemembers')) {
        add_submenu_page("bb_agency_menu", __("Approve Pending Profiles", bb_agency_TEXTDOMAIN), __("Approve Profiles", bb_agency_TEXTDOMAIN), 7,"bb_agencyinteract_approvemembers","bb_agencyinteract_approvemembers");
    }
    add_submenu_page("bb_agency_menu", __("Search &amp; Send Profiles", bb_agency_TEXTDOMAIN), __("Search Profiles", bb_agency_TEXTDOMAIN), 7,"bb_agency_search","bb_agency_search");
    add_submenu_page("bb_agency_menu", __("Saved Searches", bb_agency_TEXTDOMAIN), __("Saved Searches", bb_agency_TEXTDOMAIN), 7,"bb_agency_searchsaved","bb_agency_searchsaved");
    add_submenu_page("bb_agency_menu", __("Manage Jobs", bb_agency_TEXTDOMAIN), __("Manage Jobs", bb_agency_TEXTDOMAIN), 7,"bb_agency_jobs","bb_agency_jobs");
    add_submenu_page("bb_agency_menu", __("Search Jobs", bb_agency_TEXTDOMAIN), __("Search Jobs", bb_agency_TEXTDOMAIN), 7,"bb_agency_jobsearch","bb_agency_jobsearch");
    add_submenu_page("bb_agency_menu", __("Tools &amp; Reports", bb_agency_TEXTDOMAIN), __("Tools &amp; Reports", bb_agency_TEXTDOMAIN), 7,"bb_agency_reports","bb_agency_reports");
    add_submenu_page("bb_agency_menu", __("Edit Settings", bb_agency_TEXTDOMAIN), __("Settings", bb_agency_TEXTDOMAIN), 7,"bb_agency_settings","bb_agency_settings");
}

function bb_agency_send_email() {
    $bb_agency_value_agencyname = bb_agency_get_option('bb_agency_option_agencyname');
    $bb_agency_value_agencyemail = bb_agency_get_option('bb_agency_option_agencyemail');
    $correspondenceEmail = $bb_agency_value_agencyemail;
    
    $MassEmailSubject = $_POST["MassEmailSubject"];
    $MassEmailMessage = $_POST["MassEmailMessage"];
    $MassEmailRecipient = $_POST["MassEmailRecipient"];
    $MassEmailBccRecipient = $_POST["MassEmailBccRecipient"];

    $SearchID               = time(U);
    $SearchMuxHash          = bb_agency_random(8);
    $SearchMuxToName        = $_POST["MassEmailRecipient"];
    $SearchMuxToEmail       = $_POST["MassEmailRecipient"];
    
    $SearchMuxEmailToBcc    = $_POST['MassEmailBccRecipient'];
    $SearchMuxSubject       = $_POST['MassEmailSubject'];
    $SearchMuxMessage       = $_POST['MassEmailMessage'];
    $SearchMuxCustomValue   = '';                    

    $cartString = bb_agency_get_cart_string();

    global $wpdb;
    // set tables
    $t_ss = table_agency_searchsaved;
    $t_ss_mux = table_agency_searchsaved_mux;

    $wpdb->query("INSERT INTO $t_ss (SearchProfileID, SearchTitle) VALUES('".$cartString."','".$SearchMuxSubject."')") or die(mysql_error());
    
    $lastid = $wpdb->insert_id;
    
    // Create Record
    $insert = <<<EOF
INSERT INTO %s  
(SearchID, SearchMuxHash, SearchMuxToName, SearchMuxToEmail, SearchMuxSubject, SearchMuxMessage, SearchMuxCustomValue)
VALUES
("%d", "%s", "%s", "%s", "%s", "%s", "%s")
EOF;

    $results = $wpdb->query(
        sprintf(
            $insert,
            $t_ss_mux,
            $SearchID,
            $wpdb->escape($SearchMuxHash), 
            $wpdb->escape($SearchMuxToName),
            $wpdb->escape($SearchMuxToEmail),
            $wpdb->escape($SearchMuxSubject),
            $wpdb->escape($SearchMuxMessage),
            $wpdb->escape($SearchMuxCustomValue)
        )
    );                 
            
    if (!empty($MassEmailBccRecipient)){
        $bccMails = explode(',', $MassEmailBccRecipient);
        foreach($bccMails as $bccEmail){
            $headers[] = 'Bcc: '.$bccEmail;
        }
    }
    
    // Mail it
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
    $headers[] = 'From: '.$bb_agency_value_agencyname.' <'. $correspondenceEmail .'>';
    
    if (!empty($expMail)){
        $expMail = explode(",",$MassEmailRecipient);
        foreach($expMail as $bccEmail){
            $headers[] = 'Bcc: '.$bccEmail;
        }
    }
    $MassEmailMessage = str_replace("[link-place-holder]", site_url()."/client-view/".$SearchMuxHash, $MassEmailMessage);
    if (bb_agency_SEND_EMAILS)
        return wp_mail($MassEmailRecipient, $MassEmailSubject, $MassEmailMessage, $headers);
    else {
        echo "Emailing disabled. The following would have been sent to $MassEmailRecipient:<br /><br />$MassEmailMessage";
        exit;
    }
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
function bb_agency_jobs(){
    include_once('admin/job.php');
}
function bb_agency_jobsearch(){
    include_once('admin/job/search.php');
}
function bb_agency_job_invoice(){
    include_once('admin/job_invoice.php');
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

// Casting Cart

// do we have something in the cart?
function bb_agency_have_cart() {
    return 
        isset($_SESSION['cartArray']) && 
        is_array($_SESSION['cartArray']) && 
        !empty($_SESSION['cartArray']);
}

// return casting cart
function bb_agency_get_cart() {
    if (bb_agency_have_cart())
        return $_SESSION['cartArray'];
}

function bb_agency_the_cart_string() {
    echo bb_agency_get_cart_string();
}

function bb_agency_get_cart_string() {
    if (bb_agency_have_cart())
        return bb_agency_cleanString(implode(',', array_unique($_SESSION['cartArray'])));
}

function bb_agency_add_to_cart($profiles) {
    if (count($profiles) > 0) {
        return bb_agency_set_cart(bb_agency_have_cart() ? array_merge(bb_agency_get_cart(), $profiles) : $profiles);
    }
}

function bb_agency_set_cart($profiles) {
    return $_SESSION['cartArray'] = $profiles;
}

function bb_agency_empty_cart() {
    unset($_SESSION['cartArray']);
}

/**
 * Age dropdown
 *
 * @param string $name
 * @param string $value
 * @return string
 *
 */
function bb_agency_age_dropdown($name, $value = null) {
    $ages = array(
        '0' => '0',
        '6m' => '6 months',
        '12m' => '12 months',
        '18m' => '18 months'
    );
    for ($i = 2; $i <= 20; $i++) {
        $ages[$i] = $i;
    }
    for ($i = 30; $i <= 100; $i += 10) {
        $ages[$i] = $i;
    }
    $label = strpos($name, '_min') === false ? __('To', bb_agency_TEXTDOMAIN)  : __('From', bb_agency_TEXTDOMAIN); 
    $option = array('<option value="">'.$label.'</option>');
    foreach ($ages as $k => $v) {
        $option[] = '<option value="'.$k.'" '.selected($k, $value, false).'>'.$v.'</option>';
    }
    return '<select name="'.$name.'" size="1">'.implode("\n", $option).'</select>';
}

/**
 * Client dropdown
 *
 * @param string $name
 * @param int $value
 * @return string
 *
 */
function bb_agency_client_dropdown($name, $value = null) {
    global $wpdb;
    $t_profile = table_agency_profile;
    $client_id = bb_agency_CLIENTS_ID;
    $sql = <<<EOF
SELECT `ProfileID` AS id, `ProfileContactDisplay` AS name 
FROM $t_profile 
WHERE `ProfileType` = '$client_id' 
AND `ProfileIsActive` = '1' 
ORDER BY `ProfileContactDisplay` ASC 
EOF;

    $clients = $wpdb->get_results($sql);

    $option = array('<option value="">--</option>');
    foreach ($clients as $client) {
        $option[] = '<option value="'.$client->id.'" '.selected($client->id, $value, false).'>'.$client->name.'</option>';
    }
    return '<select name="'.$name.'" size="1">'.implode("\n", $option).'</select>';
}

function bb_agency_get_job($id) {
    global $wpdb;
    $t_job = table_agency_job;
    $t_profile = table_agency_profile;
    $sql = "SELECT * FROM `$t_job` j LEFT JOIN `$t_profile` p ON j.`JobClient` = p.`ProfileID` WHERE j.`JobID` = $id";

    $Job = $wpdb->get_row($sql, ARRAY_A);

    if (!is_null($Job['JobModelBooked'])) {
        $Job['ModelsBooked'] = bb_agency_get_models_list($Job['JobModelBooked']);
    }

    if (!is_null($Job['JobModelCasted'])) {
        $Job['ModelsCasted'] = bb_agency_get_models_list($Job['JobModelCasted']);
    }

    return $Job;
}

function bb_agency_get_models_list($ids) {
    global $wpdb;
    $t_profile = table_agency_profile;
    $models = $wpdb->get_var("SELECT GROUP_CONCAT(`ProfileContactDisplay`) FROM `$t_profile` WHERE `ProfileID` IN($ids)");
    if ($models) {
        $models = str_replace(',', ', ', $models);
        // replace last comma with and
        $pos = strrpos($models, ',');

        if ($pos !== false) {
            $models = substr_replace($models, ' and', $pos, 1);
        }
        return $models;
    }
}

function bb_agency_get_invoice_url($file) {
    return bb_agency_BASEDIR.'invoices/'.$file.'.pdf';
}

// convert to human readable date
function bb_agency_human_date($date) {
    return date('jS F Y', strtotime($date));
}