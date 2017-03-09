<?php

global $wpdb;
define("LabelPlural", "bookings");
define("LabelSingular", "booking");

$bb_agency_option_persearch = (int)bb_agency_get_option('bb_agency_option_persearch');

// database tables
$t_booking = table_agency_booking;
$t_profile = table_agency_profile;

// settings
$booking = LabelSingular;

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
            if (!isset($_POST['BookedID']) || !$_POST['BookedID']) {
                $error[] = 'No holiday id was passed.';
            } else {
                $id = $_POST['BookedID'];
            }

        case 'add' :

            if (empty($error)) {

                $fields = array(
                    'BookedFrom',
                    'BookedTo',
                    'ProfileID'
                );

                // Create insert query
                foreach ($fields as $field) {
                    $value = $wpdb->escape($_POST[$field]);
                    $sqlData[] = "`$field` = '$value'";
                }

                if ($action == 'add') {
                    $sql = "INSERT INTO $t_booking SET ". implode(', ', $sqlData);
                } else {
                    $sql = "UPDATE $t_booking SET ". implode(', ', $sqlData) ." WHERE `BookedID` = $id";
                }
                
                $results = $wpdb->query($sql) or die(mysql_error());

                // display success messsage
                if ($action == 'add') {
                    $id = $wpdb->insert_id;
                    $message = __("New holiday added successfully.", bb_agency_TEXTDOMAIN);
                } else {
                    $message = __("holiday successfully updated.", bb_agency_TEXTDOMAIN);
                }
                bb_agency_admin_message("<p>$message</p>");

                // display list page
                $action = 'list';
                
            } else {
                // display error message
                bb_agency_admin_message('<p>' . __("Error. Please ensure you have filled out all required fields.", bb_agency_TEXTDOMAIN) . '</p><ul><li>'.implode('</li><li>', $error).'</li></ul>', 'error');
            }            
            
            break;

        default :
            wp_die('Unknown action '.$action);
            break;
    } 
}

// display page

switch ($action) {
    case 'delete' :
        if ($_GET['BookedID']) {
            $wpdb->query("DELETE FROM $t_booking WHERE `BookedID` = ".$_GET['BookedID']);
            bb_agency_admin_message('<p>'. __("That holiday has been deleted.", bb_agency_TEXTDOMAIN) . '</p>');
        }
        elseif (!empty($_GET['BookedIDs'])) {
            $i = 0;
            foreach ($_GET['BookedIDs'] as $id) {
                $wpdb->query("DELETE FROM $t_booking WHERE `BookedID` = $id");
                $i++;
            }
            // display success messsage
            bb_agency_admin_message('<p>'. sprintf(__("Those %d holidays have been deleted.", bb_agency_TEXTDOMAIN), $i) . '</p>');
        }
        else {
            bb_agency_admin_message('<p>Unable to delete as no holiday id was received.</p>', 'error');
        }
        $action = 'list';

    case 'list' :
    case 'search' :

        // Sort By
        $sort = '';
        $dir = !empty($_REQUEST['dir']) ? $_REQUEST['dir'] : 'asc';
        $sort = !empty($_REQUEST['sort']) ? $_REQUEST['sort'] : "`BookedTo`";
        $sortDirection = $dir == 'desc' ? 'asc' : 'desc';

        $name = !empty($_REQUEST['s']) ? $_REQUEST['s'] : '';

        // generate SQL 
        $sql = "SELECT b.*, IF(p.`ProfileContactDisplay`, p.`ProfileContactDisplay`, CONCAT(p.`ProfileContactNameFirst`, ' ', p.`ProfileContactNameLast`)) AS ModelName, IF(b.`BookedFrom` < NOW() AND b.`BookedTo` > NOW(), 1, 0) AS IsBooked FROM $t_booking b LEFT JOIN $t_profile p ON b.`ProfileID` = p.`ProfileID` WHERE b.`BookedTo` > NOW()";

        if ($action == 'search') {
            
            $sql .= " AND (p.`ProfileContactDisplay` LIKE '%$name%' OR p.`ProfileContactNameLast` LIKE '%$name%' OR `ProfileContactNameFirst` LIKE '%$name%')";
        }
            
        $sql .= " ORDER BY $sort $dir $limit";

        bb_agency_debug($sql);

        $results = $wpdb->get_results($sql);

        if (count($results) || !empty($where)) : ?>
        <form action="<?php echo admin_url('admin.php') ?>" method="GET">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php
            // display filter form
            include('booking/filter.php');
            
            include('booking/dashboard_widgets.php');

            if (count($results))
                include('booking/list.php');
            else
                bb_agency_admin_message('<p>No holidays found.</p>', 'error');
        ?>
        </form>
        <?php
            break;

        elseif ($action == 'search') : ?>
            <p><?php printf( __( 'No holidays found for "%s"', bb_agency_TEXTDOMAIN ), $name ) ?></p>

            <?php
            $action = 'add';

        else : ?>

            <p><?php _e( 'There are no holidays in the database at the moment. Use this form to set one up.', bb_agency_TEXTDOMAIN ) ?></p>
            <?php
            $action = 'add';
        endif;

    case 'edit' :
        // get holiday from database
        $sql = "SELECT * FROM $t_booking WHERE `BookedID` = ".(int)$_REQUEST['BookedID'];
        $Booking = $wpdb->get_row($sql, ARRAY_A);

    case 'add' :

        include('booking/form.php');
        break;

    default :
        wp_die("Unknown action '$action'");
        break;

}

?>
    <div class="clear"></div>
</div>