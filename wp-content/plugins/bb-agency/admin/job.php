<?php

global $wpdb;
define("LabelPlural", "jobs");
define("LabelSingular", "job");

$bb_options = bb_agency_get_option();
$bb_agency_option_persearch = (int)bb_agency_get_option('bb_agency_option_persearch');

// database tables
$t_job = table_agency_job;
$t_profile = table_agency_profile;

// settings
$job = LabelSingular;

//start page display
?>
<div class="wrap">
    <?php include ("admin-menu.php"); ?>

<?php

// *************************************************************************************************** //
// Handle Post Actions

// get action
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? $_REQUEST['action'] : 'list';

// Get Post State
if ($_POST) {
    // deal with posted forms
    // add & edit

    // set error array
    $error = array();
   
    switch ($action) {

        // *************************************************************************************************** //
        // Add/Edit Record
        case 'edit' :
            if (!isset($_POST['id']) || !$_POST['id']) {
                $error[] = 'No job id was passed.';
            } else {
                $id = $_POST['id'];
            }

        case 'add' :

            // Check for required fields
            $required = array(
                'JobTitle' => 'title',
                'JobClient' => 'client',
                'JobLocation' => 'location',
            );
            foreach ($required as $field => $label) {
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
                    'JobDate',
                    'JobStatus'
                );

                // Create insert query
                foreach ($fields as $field) {
                    $value = $wpdb->escape($_POST[$field]);
                    $sqlData[] = "`$field` = '$value'";
                }

                $sqlData[] = '`JobDateUpdated` = NOW()';

                // get latitude and longitude
                if (!empty($_POST['JobLocation'])) {

                    if ($location = bb_agency_geocode($_POST['JobLocation'])) {
                        // geocode address
                        $sqlData[] = '`JobLocationLatitude` = "'.$location['lat'].'"';
                        $sqlData[] = '`JobLocationLongitude` = "'.$location['lng'].'"';
                    } else {
                        echo '<div id="message" class="error"><p>' . sprintf(__("Failed to get location of '%s'", bb_agencyinteract_TEXTDOMAIN), $_POST['JobLocation']) . '</p></div>';
                    }
                }

                // get model booked and models called for casting
                if (!empty($_POST['JobModelBooked'])) {
                    // array from multiselect
                    $booked = implode(',', $_POST['JobModelBooked']);
                    $sqlData[] = '`JobModelBooked` = "'.$wpdb->escape($booked).'"';
                }

                if (!empty($_POST['JobModelCasted'])) {
                    // array from multiselect
                    $casted = implode(',', $_POST['JobModelCasted']);
                    $sqlData[] = '`JobModelCasted` = "'.$wpdb->escape($casted).'"';
                }

                if ($action == 'add') {
                    $sql = "INSERT INTO $t_job SET ". implode(', ', $sqlData);
                } else {
                    $sql = "UPDATE $t_job SET ". implode(', ', $sqlData) ." WHERE `JobID` = $id";
                }
                
                $results = $wpdb->query($sql) or die(mysql_error());

                // display success messsage
                if ($action == 'add') {
                    $id = $wpdb->insert_id;
                    $message = __("New job added successfully.", bb_agency_TEXTDOMAIN);
                } else {
                    $message = __("Job successfully updated.", bb_agency_TEXTDOMAIN);
                }
                bb_agency_admin_message("<p>$message</p>");

                // display list page
                $action = 'list';
                
            } else {
                // display error message
                bb_agency_admin_message('<p>' . __("Error. Please ensure you have filled out all required fields.", bb_agency_TEXTDOMAIN) . '</p><ul><li>'.implode('</li><li>', $error).'</li></ul>', 'error');
            }            
            
            break;
    } 
}

// display page

switch ($action) {
    case 'delete' :
        if ($_GET['id']) {
            $wpdb->query("DELETE FROM $t_job WHERE `JobID` = ".$_GET['id']);
            bb_agency_admin_message('<p>'. __("That job has been deleted.", bb_agency_TEXTDOMAIN) . '</p>');
        }
        elseif (!empty($_GET['JobID'])) {
            $i = 0;
            foreach ($_GET['JobID'] as $id) {
                $wpdb->query("DELETE FROM $t_job WHERE `JobID` = $id");
                $i++;
            }
            // display success messsage
            bb_agency_admin_message('<p>'. sprintf(__("Those %d jobs have been deleted.", bb_agency_TEXTDOMAIN), $i) . '</p>');
        }
        else {
            bb_agency_admin_message('<p>Unable to delete as no job id was received.</p>');
        }
        $action = 'list';

    case 'list' :
    case 'search' :

        // Sort By
        $sort = "";
        if (isset($_REQUEST['sort']) && !empty($_REQUEST['sort'])) {
            $sort = $_REQUEST['sort'];
        } else {
            $sort = "`JobDate`";
        }

        // Limit
        if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])){
            $limit = "";
        } else {
            if ($bb_agency_option_persearch > 1) {
                $limit = " LIMIT 0,". $bb_agency_option_persearch;
            }
        }

        // Sort Order
        $dir = "";
        if (isset($_REQUEST['dir']) && !empty($_REQUEST['dir'])){
            $dir = $_REQUEST['dir'];
            if ($dir == "desc" || !isset($dir) || empty($dir)){
                $sortDirection = "asc";
            } else {
                $sortDirection = "desc";
            } 
        } else {
            $sortDirection = "desc";
            $dir = "asc";
        }

        // generate SQL 
        $sql = "SELECT j.*, p.`ProfileContactDisplay` AS ClientName, IF(j.`JobDate` < NOW(), 1, 0) AS JobPassed FROM $t_job j LEFT JOIN $t_profile p ON j.`JobClient` = p.`ProfileID`";

        if (isset($_REQUEST['state'])) {
            // quick filter search
            $value = $_REQUEST['state'];

            // Standard fields
            $fields = array(
                'JobTitle',
                'JobRate',
                'JobPONumber'
            );

            foreach ($fields as $field) {
                $where[] = "`$field` LIKE '%$value%'"; 
            }

            if (!empty($where)) {
                $sql .= ' WHERE '.implode(' OR ', $where);
            }
            
        }
        $sql .= " ORDER BY $sort $dir $limit";

        $results = $wpdb->get_results($sql);

        if (count($results)) : ?>
        <form action="<?php echo admin_url('admin.php?page=' . $_REQUEST['page']) ?>" method="GET">
        <?php
            if (count($results) > 0) {
                // display filter form
                include('job/filter.php');
            }
            include('job/dashboard_widgets.php');

            include('job/list_full.php'); 
        ?>
        </form>
        <?php
            break;

        else : ?>
            <p>There are no jobs in the database at the moment. Use this form to set one up.</p>
            <?php
            $action = 'add';
        endif;

    case 'edit' :
        // get job from database
        $sql = "SELECT * FROM $t_job WHERE `JobID` = ".(int)$_REQUEST['id'];
        $Job = $wpdb->get_row($sql, ARRAY_A);

    case 'add' :
        $fields = array(
            'JobTitle'  => __('Job Title', bb_agency_TEXTDOMAIN),
            'JobClient' => __('Client', bb_agency_TEXTDOMAIN),
            'JobRate'   => __('Rate', bb_agency_TEXTDOMAIN),
        );
        include('job/form.php');
        break;

    default :
        wp_die("Unknown action '$action'");
        break;

}