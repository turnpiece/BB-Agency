<a class="button-secondary" id="bulk_generate" href="javascript:void(0)" style="margin-bottom: 5px" title="Generate" disabled="disabled">Generate</a>
<a class="button-primary"  id="bulk_send_email" href="javascript:void(0)" style="margin-left: 5px" title="Send Email" disabled="disabled">Send Email</a>
<a class="button-primary"  id="open_popup" href="javascript:void(0)" style="margin-left: 5px;float: right" title="Send Email">Edit Email Content</a>
<div id="ch_bulk" style="float: none !important;margin-left: 10px;width: 34px;display: inline-block;position: relative;top: 7px;margin-top: -15px;"></div>
<table cellspacing="0" class="widefat fixed">
  <thead>
    <tr class="thead">
      <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
      <th style="width:50px;"><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&ConfigID=99&sort=JobID&dir=". $sortDirection) ?>">ID</a></th>
      <th style="width:250px;"><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&ConfigID=99&sort=JobContactNameFirst&dir=". $sortDirection) ?>">Job Title</a></th>
      <th style="width:250px;"><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&ConfigID=99&sort=JobContactNameLast&dir=". $sortDirection) ?>">Client</a></th>
      <th style="width:250px;"><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&ConfigID=99&sort=JobGender&dir=". $sortDirection) ?>">Locationes</a></th>
    </tr>
  </thead>
 <tbody>

<?php
$t_job = table_agency_job
$query = "SELECT * FROM $job ORDER BY $sort $dir $limit";
$results2 = mysql_query($query);
$count = mysql_num_rows($results2);
$i = 0;
while ($data = mysql_fetch_array($results2)) {
  $JobID = $data['JobID'];
  $JobTitle = stripslashes($data['JobContactNameFirst']);
  $JobContactNameLast = stripslashes($data['JobContactNameLast']);
  $JobContactEmail = bb_agency_strtoproper(stripslashes($data['JobContactEmail']));

  $i++;
  if ($i % 2 == 0) {
          $rowColor = " style='background: #fcfcfc'"; 
  } else {
          $rowColor = " "; 
  } ?>
  <tr <?php echo $rowColor ?>>
    <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $JobID ?>" id="<?php echo $JobID ?>" data-firstname="<?php echo $JobContactNameFirst ?>" data-lastname="<?php echo $JobContactNameLast ?>" data-email="<?php echo $JobContactEmail ?>" class="administrator"  name="<?php echo $JobID ?>"/></th>
    <td><?php echo $data['JobID'] ?></td>
    <td><?php echo $data['JobTitle'] ?></td>
    <td><?php echo $data['JobClient'] ?></td>
    <td><?php echo $data['JobLocation'] ?></td>
  </tr>
  <?php
}

mysql_free_result($results2);
if ($count < 1) {
  if (isset($filter)) { 
?>
    <tr>
      <th class="check-column" scope="row"></th>
      <td class="name column-name" colspan="5">
        <p>No jobs found with this criteria.</p>
      </td>
    </tr>
<?php
  } else {
?>

    <tr>
      <th class="check-column" scope="row"></th>
      <td class="name column-name" colspan="5">
        <p>There aren't any jobs loaded yet!</p>
      </td>
    </tr>
<?php
  }
} 
?>
     
 </tbody>
  <tfoot>
    <tr class="thead">
      <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox" /></th>
      <th class="column" scope="col">ID</th>
      <th class="column" scope="col">Job Title</th>
      <th class="column" scope="col">Client</th>
      <th class="column" scope="col">Location</th>
    </tr>
  </tfoot>
</table>