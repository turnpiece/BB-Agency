<table cellspacing="0" class="wp-list-table widefat fixed">
  <thead>
    <tr class="thead">
      <th><?php _e('Model', bb_agency_TEXTDOMAIN) ?></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=BookedTo&dir=". $sortDirection) ?>"><?php _e('From', bb_agency_TEXTDOMAIN) ?></a></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=BookedTo&dir=". $sortDirection) ?>"><?php _e('To', bb_agency_TEXTDOMAIN) ?></a></th>
    </tr>
  </thead>
  <tbody>

  <?php foreach ($results as $data) : $id = $data->JobID; ?>
    <tr class="<?php echo $data->IdBooked == 1 ? 'booked' : 'available' ?>">
      <td><a href="<?php echo admin_url('admin.php?page=bb_agency_profiles&amp;action=editRecord&amp;ProfileID='.$data->ProfileID) ?>" title="Edit profile"><?php echo $data->ModelName ?></a>
        <div class="row-actions">
            <span class="edit">
                <a href="<?php echo admin_url('admin.php?page=bb_agency_bookings&amp;action=edit&amp;BookedID='. $id) ?>" title="Edit this holiday"><?php _e('Edit', bb_agency_TEXTDOMAIN) ?></a> | 
            </span>
            <span class="delete">
                <a class="submitdelete" title="Remove this holiday" href="<?php echo admin_url('admin.php?page=bb_agency_bookings&amp;action=delete&amp;BookedID='. $id) ?>" onclick="if ( confirm('You are about to delete a holiday.') ) { return true; } return false;"><?php _e('Delete', bb_agency_TEXTDOMAIN) ?></a>
            </span>
        </div>
      </td> 
      <td><?php echo $data->BookedFrom ?></td>
      <td><?php echo $data->BookedTo ?></td>
    </tr>
  <?php endforeach; ?>
     
  </tbody>
  <tfoot>
    <tr class="thead">
      <th><?php _e('Model', bb_agency_TEXTDOMAIN) ?></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=BookedTo&dir=". $sortDirection) ?>"><?php _e('From', bb_agency_TEXTDOMAIN) ?></a></th>
      <th><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&sort=BookedTo&dir=". $sortDirection) ?>"><?php _e('To', bb_agency_TEXTDOMAIN) ?></a></th>
    </tr>
  </tfoot>
</table>