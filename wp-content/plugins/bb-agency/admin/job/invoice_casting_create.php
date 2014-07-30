<h2 class="title"><?php echo sprintf(__("Casting invoice for %s", bb_agency_TEXTDOMAIN), $Invoice['ProfileContactDisplay']) ?></h2>
<?php if ($Invoice['JobCastingInvoiceNumber'] && $Invoice['JobCastingInvoiceSent']) : ?>
<p class="warning"><strong>WARNING: You have already sent a casting invoice for this job. <a href="<?php echo bb_agency_get_invoice_url($Invoice['InvoiceNumber']) ?>">Invoice <?php echo $Invoice['JobCastingInvoiceNumber'] ?></a> was sent on <?php echo $Invoice['JobCastingInvoiceSent'] ?>.</strong></p> 
<?php endif; ?>
<form method="post" action="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>">
    <input type="hidden" name="action" value="<?php echo $_REQUEST['action'] ?>" />
    <input type="hidden" name="JobID" value="<?php echo $_REQUEST['JobID'] ?>" />
    <div class="form-container">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><?php _e('Email', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <?php echo $Invoice['ProfileContactEmail'] ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Invoice Date', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <input class="stubby bbdatepicker" type="text" id="InvoiceDate" name="InvoiceDate" value="<?php echo isset($_POST['InvoiceDate']) ? $_POST['InvoiceDate'] : date('Y-m-d') ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Invoice Number', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <input class="stubby" type="text" id="InvoiceNumber" name="InvoiceNumber" value="<?php echo isset($_POST['InvoiceNumber']) ? $_POST['InvoiceNumber'] : $Invoice['JobPONumber'] ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Job Description', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <textarea id="JobDescription" name="JobDescription"><?php echo isset($_POST['JobDescription']) ? $_POST['JobDescription'] : 
'For '.$Invoice['ModelsCasted'].' attending a casting for '.$Invoice['ProfileContactDisplay'].' on '.bb_agency_human_date($Invoice['JobDate']).'.' ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Job Price', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        &pound;<input class="stubby" type="text" id="JobPrice" name="JobPrice" value="<?php echo isset($_POST['JobPrice']) ? $_POST['JobPrice'] : filter_var($Invoice['JobRate'], FILTER_SANITIZE_NUMBER_FLOAT) ?>" />
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input class="button-primary" type="submit" name="save" value="Save" />
            <a href="<?php echo admin_url('admin.php?page='.$_GET['page'].'&action=edit&JobID='.$_REQUEST['JobID']) ?>" title="Back to the job editing page"><?php _e('Cancel', bb_agency_TEXTDOMAIN) ?></a>
        </p>
    </div>
</form>
