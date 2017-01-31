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
            if (!isset($_POST['BookingID']) || !$_POST['BookingID']) {
                $error[] = 'No booking id was passed.';
            } else {
                $id = $_POST['BookingID'];
            }

        case 'add' :

            if (empty($error)) {

                $fields = array(
                    'BookedStartDate',
                    'BookedEndDate',
                    'BookedModel'
                );

                // Create insert query
                foreach ($fields as $field) {
                    $value = $wpdb->escape($_POST[$field]);
                    $sqlData[] = "`$field` = '$value'";
                }

                $sqlData[] = '`BookingDateUpdated` = NOW()';

                if ($action == 'add') {
                    $sql = "INSERT INTO $t_booking SET ". implode(', ', $sqlData);
                } else {
                    $sql = "UPDATE $t_booking SET ". implode(', ', $sqlData) ." WHERE `BookingID` = $id";
                }
                
                $results = $wpdb->query($sql) or die(mysql_error());

                // display success messsage
                if ($action == 'add') {
                    $id = $wpdb->insert_id;
                    $message = __("New booking added successfully.", bb_agency_TEXTDOMAIN);
                } else {
                    $message = __("Booking successfully updated.", bb_agency_TEXTDOMAIN);
                }
                bb_agency_admin_message("<p>$message</p>");

                // display list page
                $action = 'list';
                
            } else {
                // display error message
                bb_agency_admin_message('<p>' . __("Error. Please ensure you have filled out all required fields.", bb_agency_TEXTDOMAIN) . '</p><ul><li>'.implode('</li><li>', $error).'</li></ul>', 'error');
            }            
            
            break;

        case 'casting_invoice' :
        case 'shoot_invoice' :
            $Invoice = bb_agency_get_booking($_REQUEST['BookingID']);

            if (empty($Invoice))
                wp_die('Failed to get booking id '.$_REQUEST['BookingID']);

            if (isset($_POST['send'])) {
                $email = bb_agency_SEND_EMAILS ? $Invoice['AccountsEmail'] : get_bloginfo('admin_email');
                $to = $Invoice['ProfileContactDisplay'].' <'.$email.'>';
                $headers .= 'Bcc: '.bb_agency_accounts_email(). "\r\n";

                if ($_POST['EmailSubject'] && $_POST['EmailMessage'] && $_POST['EmailAttachment']) {
                    // set email to html
                    add_filter( 'wp_mail_content_type', 'bb_agency_set_content_type' );
                    add_filter( 'wp_mail_from', 'bb_agency_invoice_fromemail' );
                    add_filter( 'wp_mail_from_name', 'bb_agency_invoice_fromname' );

                    // send the email
                    $success = wp_mail(
                        $to, 
                        $_POST['EmailSubject'],
                        nl2br($_POST['EmailMessage']),
                        $headers,
                        $_POST['EmailAttachment']
                    );

                    remove_filter( 'wp_mail_content_type', 'bb_agency_set_content_type' );
                    remove_filter( 'wp_mail_from', 'bb_agency_invoice_fromemail' );
                    remove_filter( 'wp_mail_from_name', 'bb_agency_invoice_fromname' );

                    if ($success) {
                        bb_agency_admin_message('<p>' . sprintf(__('Invoice sent to %s', bb_agency_TEXTDOMAIN), htmlspecialchars($to)) . '</p>');
                        // update the booking database record
                        $prefix = $action == 'shoot_invoice' ? 'BookingShootInvoice' : 'BookingCastingInvoice';
                        $wpdb->update(
                            $t_booking, 
                            array(
                                $prefix.'Number' => $_POST['InvoiceNumber'], 
                                $prefix.'Sent' => date('Y-m-d')
                            ), 
                            array('BookingID' => $_REQUEST['BookingID'])
                        );
                        // go to booking edit page
                        $action = 'edit';
                    } else {
                        bb_agency_admin_message('<p>' . sprintf(__('ERROR: Failed to send invoice to %s', bb_agency_TEXTDOMAIN), htmlspecialchars($to)) . '</p>', 'error');
                    }
                } else {
                    bb_agency_admin_message('<p>' . sprintf(__('ERROR: Failed to send invoice to %s. Please check the invoice and check you filled in the subject and message fields.', bb_agency_TEXTDOMAIN), htmlspecialchars($to)) . '</p>', 'error');
                }
            } else {
                if (!isset($_POST['InvoiceNumber']) || !$_POST['InvoiceNumber'] ||
                    !isset($_POST['BookingDescription']) || !$_POST['BookingDescription'] ||
                    !isset($_POST['BookingPrice']) || !$_POST['BookingPrice']) {
                    bb_agency_admin_message('<p>' . __('ERROR: Something was missing. All fields in this form are required.', bb_agency_TEXTDOMAIN) . '</p>', 'error');
                } else {
                    // invoice generation
                    // get booking and client details
                    $Invoice['rows'] = array(
                        array(
                            $_POST['BookingDescription'],
                            $_POST['BookingPrice']
                        )
                    );
                    $Invoice['InvoiceNumber'] = trim($_POST['InvoiceNumber']);
                    $Invoice['InvoiceDate'] = bb_agency_human_date($_POST['InvoiceDate']);
                    $Invoice['InvoiceTotal'] = $_POST['BookingPrice'];
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
        if ($_GET['BookingID']) {
            $wpdb->query("DELETE FROM $t_booking WHERE `BookingID` = ".$_GET['BookingID']);
            bb_agency_admin_message('<p>'. __("That booking has been deleted.", bb_agency_TEXTDOMAIN) . '</p>');
        }
        elseif (!empty($_GET['BookingIDs'])) {
            $i = 0;
            foreach ($_GET['BookingIDs'] as $id) {
                $wpdb->query("DELETE FROM $t_booking WHERE `BookingID` = $id");
                $i++;
            }
            // display success messsage
            bb_agency_admin_message('<p>'. sprintf(__("Those %d bookings have been deleted.", bb_agency_TEXTDOMAIN), $i) . '</p>');
        }
        else {
            bb_agency_admin_message('<p>Unable to delete as no booking id was received.</p>', 'error');
        }
        $action = 'list';

    case 'list' :
    case 'search' :

        // Sort By
        $sort = '';
        $dir = !empty($_REQUEST['dir']) ? $_REQUEST['dir'] : 'desc';
        $sort = !empty($_REQUEST['sort']) ? $_REQUEST['sort'] : "`BookingDate`";
        $sortDirection = $dir == 'desc' ? 'asc' : 'desc';

        // generate SQL 
        $sql = "SELECT j.*, p.`ProfileContactDisplay` AS ClientName, IF(j.`BookingDate` < NOW(), 1, 0) AS BookingPassed FROM $t_booking j LEFT JOIN $t_profile p ON j.`BookingClient` = p.`ProfileID`";

        if (!empty($_REQUEST['s'])) {
            // quick filter search
            $value = $_REQUEST['s'];

            // Standard fields
            $fields = array(
                'BookingTitle',
                'BookingRate',
                'BookingPONumber'
            );

            foreach ($fields as $field) {
                $swhere[] = "`$field` LIKE '%$value%'"; 
            }

            if (!empty($swhere)) {
                $where[] = '('.implode(' OR ', $swhere).')';
            }
        }

        if (isset($_REQUEST['BookingStatus']) && is_numeric($_REQUEST['BookingStatus'])) {
            $where[] = '`BookingStatus` = '.$_REQUEST['BookingStatus'];
        }

        if (!empty($where)) {
            $sql .= ' WHERE '.implode(' AND ', $where);
        }
            
        $sql .= " ORDER BY $sort $dir $limit";

        $results = $wpdb->get_results($sql);

        if (count($results) || !empty($where)) : ?>
        <form action="<?php echo admin_url('admin.php') ?>" method="GET">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php
            // display filter form
            include('booking/filter.php');
            
            include('booking/dashboard_widgets.php');

            if (count($results))
                include('booking/list_full.php');
            else
                bb_agency_admin_message('<p>No bookings found.</p>', 'error');
        ?>
        </form>
        <?php
            break;

        else : ?>
            <p>There are no bookings in the database at the moment. Use this form to set one up.</p>
            <?php
            $action = 'add';
        endif;

    case 'edit' :
        // get booking from database
        $sql = "SELECT * FROM $t_booking WHERE `BookingID` = ".(int)$_REQUEST['BookingID'];
        $Booking = $wpdb->get_row($sql, ARRAY_A);

    case 'add' :
        $fields = array(
            'BookingTitle'  => __('Booking Title', bb_agency_TEXTDOMAIN),
            'BookingClient' => __('Client', bb_agency_TEXTDOMAIN),
            'BookingRate'   => __('Rate', bb_agency_TEXTDOMAIN),
        );
        include('booking/form.php');
        break;

    case 'casting_invoice' :
    case 'shoot_invoice' :

        if (!bb_agency_SEND_EMAILS) : ?>
        <p class="warning">WARNING: The site is currently in testing mode so any emails will be sent to <?php echo get_bloginfo('admin_email') ?> rather than to the client.</p>
        <?php endif;

        if (!empty($Invoice) && 
            isset($Invoice['FilePath']) && 
            file_exists($Invoice['FilePath'])) {
            // load send invoice form
            include('booking/invoice_send.php');
        } else {
            // invoice generation
            // get booking and client from database
            $Invoice = bb_agency_get_booking($_REQUEST['BookingID']);

            if (empty($Invoice))
                wp_die('Failed to get booking id '.$_REQUEST['BookingID']);
            
            // load invoice creation template
            if ($action == 'shoot_invoice') {
                if (!empty($Invoice['ModelsBooked']))
                    include('booking/invoice_shoot_create.php');
                else
                    wp_die("No models have been booked for this shoot.");
            }
            else {
                if (!empty($Invoice['ModelsCasted']))
                    include('booking/invoice_casting_create.php');
                else
                    wp_die("No models were called for this casting.");     
            }
        }

        break;

    default :
        wp_die("Unknown action '$action'");
        break;

}

?>
    <div class="clear"></div>
</div>