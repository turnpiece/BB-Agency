<h2 class="title"><?php echo sprintf(__("Send invoice to %s", bb_agency_TEXTDOMAIN), $Invoice['ProfileContactDisplay']) ?></h2>

<form method="post" action="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>">
    <input type="hidden" name="action" value="invoice" />
    <input type="hidden" name="JobID" value="<?php echo $_REQUEST['JobID'] ?>" />
    <input type="hidden" name="InvoiceNumber" value="<?php echo $Invoice['InvoiceNumber'] ?>" />
    <input type="hidden" name="EmailTo" value="<?php echo $Invoice['ProfileContactEmail'] ?>" />
    <input type="hidden" name="EmailAttachment" value="<?php echo $Invoice['FilePath'] ?>" />  
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
                    <th scope="row"><?php _e('Subject', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <input class="regular-text" type="text" id="EmailSubject" name="EmailSubject" value="<?php 
                            echo isset($_POST['EmailSubject']) ? $_POST['EmailSubject'] : get_bloginfo('name').' invoice '.$Invoice['InvoiceNumber'] ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Message', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <textarea id="EmailMessage" name="EmailMessage"><?php echo isset($_POST['EmailMessage']) ? $_POST['EmailMessage'] : "Dear ".trim($Invoice['ProfileContactDisplay']).",\r\n\r\nPlease find attached your invoice ".$Invoice['InvoiceNumber']." for &pound;".number_format($Invoice['InvoiceTotal'], 2).". If you have any questions please reply to this email.\r\n\r\nRegards,\r\n".get_bloginfo('name')."\r\n".get_bloginfo('url') ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Invoice', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <a href="<?php echo $Invoice['FileUrl'] ?>"><?php echo basename($Invoice['FilePath']) ?></a>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input class="button-primary" type="submit" name="send" value="Send" />
            <a href="<?php echo admin_url('admin.php?page='.$_GET['page'].'&action=invoice&JobID='.$_REQUEST['JobID']) ?>" title="Edit the invoice"><?php _e('Cancel', bb_agency_TEXTDOMAIN) ?></a>
        </p>
    </div>
</form>
