<table cellspacing="0" class="wp-list-table widefat fixed">
  <thead>
    <tr class="thead">
      <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobTitle&dir=". $sortDirection) ?>">Job</a></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobClient&dir=". $sortDirection) ?>">Client</a></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobLocation&dir=". $sortDirection) ?>">Location</a></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobPONumber&dir=". $sortDirection) ?>">PO Number</a></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobDate&dir=". $sortDirection) ?>">Date</a></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobInvoiceSent&dir=". $sortDirection) ?>">Invoiced</a></th>
    </tr>
  </thead>
  <tbody>

  <?php foreach ($results as $data) : $id = $data->JobID; ?>
    <tr class="<?php echo $data->JobPassed != 1 && $data->JobStatus > 0 ? 'active' : 'inactive' ?>">
      <th class="check-column" scope="row"><input type="checkbox" name="JobIDs[]" value="<?php echo $id ?>" id="<?php echo $id ?>" class="administrator" /></th>
      <td><a href="<?php echo admin_url('admin.php?page=bb_agency_jobs&amp;action=edit&amp;JobID='.$id) ?>"><?php echo $data->JobTitle ?></a>
        <div class="row-actions">
            <span class="edit">
                <a href="<?php echo admin_url('admin.php?page=bb_agency_jobs&amp;action=edit&amp;JobID='. $id) ?>" title="Edit this job"><?php _e('Edit', bb_agency_TEXTDOMAIN) ?></a> | 
            </span>
            <span class="delete">
                <a class="submitdelete" title="Remove this job" href="<?php echo admin_url('admin.php?page=bb_agency_jobs&amp;action=delete&amp;JobID='. $id) ?>" onclick="if ( confirm('You are about to delete the job '<?php echo $data->JobTitle ?>') ) { return true; } return false;"><?php _e('Delete', bb_agency_TEXTDOMAIN) ?></a>
            </span>
        </div>
      </td>
      <td><a href="<?php echo admin_url('admin.php?page=bb_agency_profiles&amp;action=editRecord&amp;ProfileID='.$data->JobClient) ?>" title="Edit client record"><?php echo $data->ClientName ?></a></td>
      <td><?php echo $data->JobLocation ?></td>
      <td><?php echo $data->JobPONumber ?></td>
      <td><?php echo $data->JobDate ?></td>
      <td><?php if ($data->JobInvoiceSent && $data->JobInvoiceNumber) : ?><a href="<?php echo bb_agency_get_invoice_url($data->JobInvoiceNumber) ?>"><?php echo $data->JobInvoiceSent ?><?php endif; ?></td>
    </tr>
  <?php endforeach; ?>
     
  </tbody>
  <tfoot>
    <tr class="thead">
      <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox" /></th>
      <th class="column" scope="col">Job</th>
      <th class="column" scope="col">Client</th>
      <th class="column" scope="col">Location</th>
      <th class="column" scope="col">PO Number</th>
      <th class="column" scope="col">Date</th>
      <th class="column" scope="col">Invoiced</th>
    </tr>
  </tfoot>
</table>
<p class="submit">
  <input type="hidden" value="delete" name="action" />
  <input type="submit" value="<?php _e('Delete Jobs') ?>" class="button-primary" name="submit" />
</p>
