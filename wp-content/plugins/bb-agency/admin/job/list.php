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

  <?php foreach ($results as $data) : $id = $data->JobID; ?>
    <tr>
      <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $id ?>" id="<?php echo $id ?>" class="administrator" /></th>
      <td><?php echo $data->JobID ?></td>
      <td><a href="<?php echo admin_url('admin.php?page='.$_GET['page'].'&action=edit&id='.$id) ?>"><?php echo $data->JobTitle ?></a></td>
      <td><?php echo $data->JobClient ?></td>
      <td><?php echo $data->JobLocation ?></td>
    </tr>
  <?php endforeach; ?>
     
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

<a href="<?php echo admin_url('admin.php?page='.$_GET['page'].'&action=add') ?>">Add a new job</a>