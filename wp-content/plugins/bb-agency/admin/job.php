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
            if (!isset($_POST['JobID']) || !$_POST['JobID']) {
                $error[] = 'No job id was passed.';
            } else {
                $id = $_POST['JobID'];
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

        case 'invoice' :
            $Invoice = bb_agency_get_job($_REQUEST['JobID']);

            if (empty($Invoice))
                wp_die('Failed to get job id '.$_REQUEST['JobID']);

            if (isset($_POST['send'])) {
                $email = bb_agency_TESTING ? get_bloginfo('admin_email') : $Invoice['ProfileContactEmail'];
                $to = $invoice['ProfileContactDisplay'].' <'.$email.'>';
                $headers = 'From: '.get_bloginfo('name').' <'.get_bloginfo('admin_email').'>' . "\r\n";

                if ($_POST['EmailSubject'] && $_POST['EmailMessage'] && $_POST['EmailAttachment']) {
                    $success = wp_mail(
                        $to, 
                        $_POST['EmailSubject'],
                        nl2br($_POST['EmailMessage']),
                        $headers,
                        $_POST['EmailAttachment']
                    );

                    if ($success) {
                        bb_agency_admin_message('<p>' . sprintf(__('Invoice sent to %s', bb_agency_TEXTDOMAIN), htmlspecialchars($to)) . '</p>');
                        // update the job database record
                        $wpdb->update(
                            $t_job, 
                            array(
                                'JobInvoiceNumber' => $_POST['InvoiceNumber'], 
                                'JobInvoiceSent' => date('Y-m-d')
                            ), 
                            array('JobID' => $_REQUEST['JobID'])
                        );
                        // go to job edit page
                        $action = 'edit';
                    } else {
                        bb_agency_admin_message('<p>' . sprintf(__('ERROR: Failed to send invoice to %s', bb_agency_TEXTDOMAIN), htmlspecialchars($to)) . '</p>', 'error');
                    }
                } else {
                    bb_agency_admin_message('<p>' . sprintf(__('ERROR: Failed to send invoice to %s. Please check the invoice and check you filled in the subject and message fields.', bb_agency_TEXTDOMAIN), htmlspecialchars($to)) . '</p>', 'error');
                }
            } else {
                if (!isset($_POST['InvoiceNumber']) || !$_POST['InvoiceNumber'] ||
                    !isset($_POST['JobDescription']) || !$_POST['JobDescription'] ||
                    !isset($_POST['JobPrice']) || !$_POST['JobPrice']) {
                    bb_agency_admin_message('<p>' . __('ERROR: Something was missing. All fields in this form are required.', bb_agency_TEXTDOMAIN) . '</p>', 'error');
                } else {
                    // invoice generation
                    // get job and client details
                    $Invoice['rows'] = array(
                        array(
                            $_POST['JobDescription'],
                            $_POST['JobPrice']
                        )
                    );
                    $Invoice['InvoiceNumber'] = trim($_POST['InvoiceNumber']);
                    $Invoice['InvoiceDate'] = date('jS F Y', strtotime($_POST['InvoiceDate']));
                    $Invoice['InvoiceTotal'] = $_POST['JobPrice'];
                    $Invoice['FileName'] = $Invoice['InvoiceNumber'];
                    $Invoice['InvoicePayment'] = array(
                        'Bankers' => 'Santander',
                        'Account' => 'Beautiful Bumps',
                        'Sort Code' => '09 01 50',
                        'Account No.' => '05677807'
                    );

                    include bb_agency_BASEPATH.'Classes/fpdf/fpdf.php';
                    include bb_agency_BASEPATH.'Classes/invoice.pdf.php';

                    // get invoice url
                    $Invoice['FileUrl'] = bb_agency_get_invoice_url($Invoice['FileName']);
                    bb_agency_admin_message('<p>' . sprintf(__('Remember to <a href="%s">view the generated invoice</a> before sending it.', bb_agency_TEXTDOMAIN), $Invoice['FileUrl']) . '</p>');

                    // set invoice path
                    $Invoice['FilePath'] = bb_agency_BASEPATH.'invoices/'.$Invoice['FileName'].'.pdf';
                }
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
        if ($_GET['JobID']) {
            $wpdb->query("DELETE FROM $t_job WHERE `JobID` = ".$_GET['JobID']);
            bb_agency_admin_message('<p>'. __("That job has been deleted.", bb_agency_TEXTDOMAIN) . '</p>');
        }
        elseif (!empty($_GET['JobIDs'])) {
            $i = 0;
            foreach ($_GET['JobIDs'] as $id) {
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
        $sql = "SELECT * FROM $t_job WHERE `JobID` = ".(int)$_REQUEST['JobID'];
        $Job = $wpdb->get_row($sql, ARRAY_A);

    case 'add' :
        $fields = array(
            'JobTitle'  => __('Job Title', bb_agency_TEXTDOMAIN),
            'JobClient' => __('Client', bb_agency_TEXTDOMAIN),
            'JobRate'   => __('Rate', bb_agency_TEXTDOMAIN),
        );
        include('job/form.php');
        break;

    case 'invoice' :

        if (bb_agency_TESTING) : ?>
        <p class="warning">WARNING: The site is currently in testing mode so any invoice emails will be sent to <?php echo get_bloginfo('admin_email') ?> rather than to the client.</p>
        <?php endif;

        if (!empty($Invoice) && 
            isset($Invoice['FilePath']) && 
            file_exists($Invoice['FilePath'])) {
            // load send invoice form
            include('job/invoice_send.php');
        } else {
            // invoice generation
            // get job and client from database
            $Invoice = bb_agency_get_job($_REQUEST['JobID']);

            if (empty($Invoice))
                wp_die('Failed to get job id '.$_REQUEST['JobID']);
            
            // load invoice creation template
            include('job/invoice_create.php');           
        }

        break;

    default :
        wp_die("Unknown action '$action'");
        break;

}