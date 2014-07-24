<h2 class="title"><?php _e("Send Invoice", bb_agency_TEXTDOMAIN) ?></h2>

<form method="post" action="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>">
    <input type="hidden" name="action" value="invoice_send" />
    <input type="hidden" name="id" value="<?php echo $_REQUEST['id'] ?>" />
    <input type="hidden" name="JobID" value="<?php echo $Invoice['JobID'] ?>" />
    <input type="hidden" name="ClientID" value="<?php echo $Invoice['ProfileID'] ?>" />
    <input type="hidden" name="EmailTo" value="<?php echo $Invoice['ProfileContactEmail'] ?>" />
    <input type="hidden" name="EmailAttachment" value="<?php echo $InvoicePath ?>" />  
    <div class="form-container">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><?php _e('To', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <?php echo $invoice['ProfileContactEmail'] ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Subject', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <input class="regular-text" type="text" id="EmailSubject" name="EmailSubject" value="<?php 
                            echo isset($_POST['EmailSubject']) ? $_POST['EmailSubject'] : get_bloginfo('name').' invoice '.$invoice['InvoiceNumber'] ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Message', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <textarea id="EmailMessage" name="EmailMessage"><?php echo isset($_POST['EmailMessage']) ? $_POST['EmailMessage'] : "Dear ".$invoice['ProfileContactDisplay'].",\r\n\r\nPlease find attached your invoice ".$invoice['InvoiceNumber'].". If you have any questions please reply to this email.\r\n\r\nRegards,\r\n".get_bloginfo('name')."\r\n".get_bloginfo('url') ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Invoice', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <a href="<?php echo $InvoicePath ?>"><?php echo basename($InvoicePath) ?></a>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="send" value="Send" />
            <a href="<?php echo admin_url('admin.php?page=bb_agency_jobs&action=invoice&id='.$_REQUEST['id']) ?>" title="Edit the invoice"><?php _e('Cancel', bb_agency_TEXTDOMAIN) ?></a>
        </p>
    </div>
</form>
