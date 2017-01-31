<h2 class="title"><?php $action == 'edit' ? _e("Edit", bb_agency_TEXTDOMAIN) : _e("Add", bb_agency_TEXTDOMAIN) ?> job 
    <a class="button-secondary" href="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>"><?php _e("Back to bookings list", bb_agency_TEXTDOMAIN) ?></a> 
</h2>
<p><?php _e("Make changes in the form below to edit a booking", bb_agency_TEXTDOMAIN) ?> <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?>*</strong></p>
<form method="post" action="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>">
    <div class="form-container">
        <div class="boxblock-container left-half">
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e('Start Date', bb_agency_TEXTDOMAIN) ?>*</th>
                        <td>
                            <input type="text" class="bbdatepicker" id="BookedFrom" name="BookedFrom" value="<?php bb_agency_posted_value('BookedFrom', isset($Booked) ? $Booked : null) ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('End Date', bb_agency_TEXTDOMAIN) ?>*</th>
                        <td>
                            <input type="text" class="bbdatepicker" id="BookedTo" name="BookedTo" value="<?php bb_agency_posted_value('BookedTo', isset($Booked) ? $Booked : null) ?>" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="boxblock-container right-half">
            <?php
            // get models
            $models = bb_agency_get_models();
            ?>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e("Model", bb_agency_TEXTDOMAIN) ?></th>
                        <td>
                            <select id="ProfileID" name="ProfileID" size="1">
                                <option value="">--</option>
                                <?php
                                    $booked = bb_agency_get_posted_value('ProfileID', isset($Booked) ? $Booked : null, true);
                                    foreach ($models as $model) : ?>
                                <option value="<?php echo $model->ID ?>"><?php echo $model->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($action == 'edit') : ?>

    <p class="submit">
         <input type="hidden" name="BookedID" value="<?php echo $_REQUEST['BookedID'] ?>" />
         <input type="hidden" name="action" value="edit" />
         <input type="submit" name="submit" value="<?php _e("Update Record", bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
    </p>
    <?php else : ?>
    <p class="submit">
         <input type="hidden" name="action" value="add" />
         <input type="submit" name="submit" value="<?php _e("Create Record", bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
    </p>
    <?php endif; ?> 
</form>
