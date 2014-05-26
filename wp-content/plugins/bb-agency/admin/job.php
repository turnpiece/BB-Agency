<?php

global $wpdb;
define("LabelPlural", "jobs");
define("LabelSingular", "job");

$bb_agency_options_arr = get_option('bb_agency_options');
$bb_agency_option_unittype = $bb_agency_options_arr['bb_agency_option_unittype'];
$bb_agency_option_showsocial = $bb_agency_options_arr['bb_agency_option_showsocial'];
$bb_agency_option_agencyimagemaxheight = $bb_agency_options_arr['bb_agency_option_agencyimagemaxheight'];
if (empty($bb_agency_option_agencyimagemaxheight) || $bb_agency_option_agencyimagemaxheight < 500) {
    $bb_agency_option_agencyimagemaxheight = 800;
}
$bb_agency_option_profilenaming = (int) $bb_agency_options_arr['bb_agency_option_profilenaming'];
$bb_agency_option_locationtimezone = (int) $bb_agency_options_arr['bb_agency_option_locationtimezone'];

$t_job = table_agency_job;

// *************************************************************************************************** //
// Handle Post Actions

// get action
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? $_REQUEST['action'] : 'list';

// set error array
$error = array();

// Get Post State

switch ($action) {

    case 'list' :
        // list all jobs
        $sql = "SELECT * FROM $job ORDER BY `JobDate` DESC LIMIT 100";
        $results = $wpdb->get_results($query);
        if (count($results)) {
            include('job/list.php');
            break;
        } else {
            echo '<p>There are no jobs in the database at the moment. Use this form to set one up.</p>';
            $action = 'addJob';
        }

    // *************************************************************************************************** //
    // Add/Edit Record
    case 'editJob' :
        if (!isset($_POST['JobID']) || !$_POST['JobID']) {
            $error[] = 'No job id was passed.';
        } else {
            $JobID = $_POST['JobID'];
        }

    case 'addJob' :

        // Check for required fields
        $required = array(
            'JobTitle' => 'title',
            'JobClient' => 'client',
            'JobLocation' => 'location',
        );
        foreach ($required as $field => $label) {
            $job = LabelSingular;
            if ($_POST[$field] == '') {
                $error[] = "The $job must have a $label.";
            }
        }

        if (empty($error)) {

            $fields = array(
                'JobTitle',
                'JobClient',
                'JobRate',
                'JobPONumber',
                'JobNotes',
                'JobLocation',
                'JobDate'
            );

            // Create insert query
            foreach ($fields as $field) {
                $value = $wpdb->escape($_POST[$field]);
                $sqlData[] = "`$field` = '$value'";
            }

            $sqlData[] = '`JobDateUpdated` = NOW()';

            // get latitude and longitude
            if (!empty($_POST['JobLocation'])) {

                if ($location = bbagency_geocode($_POST['JobLocation'])) {
                    // geocode address
                    $sqlData[] = '`JobLocationLatitude` = "'.$location['lat'].'"';
                    $sqlData[] = '`JobLocationLongitude` = "'.$location['lng'].'"';
                } else {
                    echo '<div id="message" class="error"><p>' . sprintf(__("Failed to get location of '%s'", bb_agencyinteract_TEXTDOMAIN), $_POST['JobLocation']) . '</p></div>';
                }
            }

            if ($action == 'addJob') {
                $sql = "INSERT INTO $t_job SET ". implode(', ', $sqlData);
            } else {
                $sql = "UPDATE $t_job SET ". implode(', ', $sqlData) ." WHERE `JobID` = $JobID";
            }
            
            $results = $wpdb->query($sql) or die(mysql_error());

            // display success messsage
            echo '<div id="message" class="updated"><p>';
            if ($action == 'addJob') {
                $JobID = $wpdb->insert_id;
                _e("New job added successfully.", bb_agency_TEXTDOMAIN);
            } else {
                _e("Job successfully updated.", bb_agency_TEXTDOMAIN);
            }
            echo '</p></div>';
            
        } else {
            // display error message
            echo ('<div id="message" class="error"><p>' . __("Error. Please ensure you have filled out all required fields.", bb_agency_TEXTDOMAIN) . '</p><ul><li>'.implode('</li><li>', $error).'</li></ul></div>');
        }

        break;

}
