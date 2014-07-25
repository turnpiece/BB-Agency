<table cellspacing="0" class="wp-list-table widefat fixed">
  <thead>
    <tr class="thead">
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobTitle&dir=". $sortDirection) ?>">Job</a></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobLocation&dir=". $sortDirection) ?>">Location</a></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobPONumber&dir=". $sortDirection) ?>">PO Number</a></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobDate&dir=". $sortDirection) ?>">Date</a></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobInvoiceSent&dir=". $sortDirection) ?>">Invoiced</a></th>
    </tr>
  </thead>
  <tbody>

  <?php foreach ($results as $data) : $id = $data->JobID; ?>
    <tr class="<?php echo $data->JobPassed != 1 && $data->JobStatus > 0 ? 'active' : 'inactive' ?>">
      <td><a href="<?php echo admin_url('admin.php?page=bb_agency_jobs&amp;action=edit&amp;JobID='.$id) ?>"><?php echo $data->JobTitle ?></a></td>
      <td><?php echo $data->JobLocation ?></td>
      <td><?php echo $data->JobPONumber ?></td>
      <td><?php echo $data->JobDate ?></td>
      <td><?php if ($data->JobInvoiceSent && $data->JobInvoiceNumber) : ?><a href="<?php echo bb_agency_get_invoice_url($data->JobInvoiceNumber) ?>"><?php echo $data->JobInvoiceSent ?><?php endif; ?></td>
    </tr>
  <?php endforeach; ?>
     
  </tbody>
  <tfoot>
    <tr class="thead">
      <th class="column" scope="col">Job</th>
      <th class="column" scope="col">Location</th>
      <th class="column" scope="col">PO Number</th>
      <th class="column" scope="col">Date</th>
      <th class="column" scope="col">Invoiced</th>
    </tr>
  </tfoot>
</table>