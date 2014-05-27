<table cellspacing="0" class="widefat fixed">
  <thead>
    <tr class="thead">
      <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobTitle&dir=". $sortDirection) ?>">Job Title</a></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobClient&dir=". $sortDirection) ?>">Client</a></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=JobLocation&dir=". $sortDirection) ?>">Location</a></th>
    </tr>
  </thead>
  <tbody>

  <?php foreach ($results as $data) : $id = $data->JobID; ?>
    <tr>
      <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $id ?>" id="<?php echo $id ?>" class="administrator" /></th>
      <td><a href="<?php echo admin_url('admin.php?page=bb_agency_jobs&amp;action=edit&amp;id='.$id) ?>"><?php echo $data->JobTitle ?></a>
        <div class="row-actions">
            <span class="edit">
                <a href="<?php echo admin_url('admin.php?page=bb_agency_jobs&amp;action=edit&amp;id='. $id) ?>" title="Edit this job"><?php _e('Edit', bb_agency_TEXTDOMAIN) ?></a> | 
            </span>
            <span class="delete">
                <a class="submitdelete" title="Remove this job" href="<?php echo admin_url('admin.php?page=bb_agency_jobs&amp;action=delete&amp;id='. $id) ?>" onclick="if ( confirm('You are about to delete the job '<?php echo $data->JobTitle ?>') ) { return true; } return false;"><?php _e('Delete', bb_agency_TEXTDOMAIN) ?></a>
            </span>
        </div>
      </td>
      <td><?php echo $data->JobClient ?></td>
      <td><?php echo $data->JobLocation ?></td>
    </tr>
  <?php endforeach; ?>
     
  </tbody>
  <tfoot>
    <tr class="thead">
      <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox" /></th>
      <th class="column" scope="col">Job Title</th>
      <th class="column" scope="col">Client</th>
      <th class="column" scope="col">Location</th>
    </tr>
  </tfoot>
</table>

<a href="<?php echo admin_url('admin.php?page='.$_GET['page'].'&action=add') ?>">Add a new job</a>