<h2 class="title"><?php _e("Invoice", bb_agency_TEXTDOMAIN) ?></h2>

<form method="post" action="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>">
    <input type="hidden" name="action" value="invoice" />
    <input type="hidden" name="id" value="<?php echo $_REQUEST['id'] ?>" />
    <input type="hidden" name="JobID" value="<?php echo $Invoice['JobID'] ?>" />
    <input type="hidden" name="ClientID" value="<?php echo $Invoice['ProfileID'] ?>" /> 
    <div class="form-container">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><?php _e('Invoice Date', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <input class="stubby bbdatepicker" type="text" id="InvoiceDate" name="InvoiceDate" value="<?php bb_agency_posted_value('InvoiceDate', isset($Invoice) ? $Invoice : null) ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Invoice Number', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <input class="regular-text" type="text" id="InvoiceNumber" name="InvoiceNumber" value="<?php bb_agency_posted_value('InvoiceNumber', isset($Invoice) ? $Invoice : null) ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Job Description', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <input class="regular-text" type="text" id="JobDescription" name="JobDescription" value="<?php bb_agency_posted_value('JobDescription', isset($Invoice) ? $Invoice : null) ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Job Price', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <input class="regular-text" type="text" id="JobPrice" name="JobPrice" value="<?php bb_agency_posted_value('JobPrice', isset($Invoice) ? $Invoice : null) ?>" />
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="save" value="Save" />
        </p>
    </div>
</form>
