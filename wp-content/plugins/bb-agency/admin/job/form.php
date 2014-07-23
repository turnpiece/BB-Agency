<h2 class="title"><?php $action == 'edit' ? _e("Edit", bb_agency_TEXTDOMAIN) : _e("Add", bb_agency_TEXTDOMAIN) ?> job 
    <a class="button-secondary" href="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>"><?php _e("Back to job list", bb_agency_TEXTDOMAIN) ?></a> 
</h2>
<p><?php _e("Make changes in the form below to edit a job", bb_agency_TEXTDOMAIN) ?> <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?>*</strong></p>
<form method="post" action="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>">
    <div class="form-container">
        <div class="boxblock-container left-half">
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e('Job Title', bb_agency_TEXTDOMAIN) ?>*</th>
                        <td>
                            <input class="regular-text" type="text" id="JobTitle" name="JobTitle" value="<?php bb_agency_posted_value('JobTitle', isset($Job) ? $Job : null) ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Client', bb_agency_TEXTDOMAIN) ?>*</th>
                        <td>
                            <?php echo bb_agency_client_dropdown('JobClient', bb_agency_get_posted_value('JobClient', isset($Job) ? $Job : null)) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Rate', bb_agency_TEXTDOMAIN) ?>*</th>
                        <td>
                            <input class="regular-text" type="text" id="JobRate" name="JobRate" value="<?php bb_agency_posted_value('JobRate', isset($Job) ? $Job : null) ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Location', bb_agency_TEXTDOMAIN) ?>*</th>
                        <td>
                            <?php
                            // display location map
                            $loc = bb_agency_get_posted_value('JobLocation', isset($Job) ? $Job : null);
                            $lat = bb_agency_get_posted_value('JobLocationLatitude', isset($Job) ? $Job : null);
                            $lng = bb_agency_get_posted_value('JobLocationLongitude', isset($Job) ? $Job : null);
                            if ($loc != '' && $lat != '' && $lng != '') : ?>
                                <?php bb_agency_map($lat, $lng, $loc) ?>
                            <?php endif; ?>
                            <input class="regular-text" type="text" id="JobLocation" name="JobLocation" value="<?php echo $loc ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Date', bb_agency_TEXTDOMAIN) ?>*</th>
                        <td>
                            <input type="text" class="bbdatepicker" id="JobDate" name="JobDate" value="<?php bb_agency_posted_value('JobDate', isset($Job) ? $Job : null) ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e("Status", bb_agency_TEXTDOMAIN) ?>*</th>
                        <td>
                            <select id="ProfileIsActive" name="JobStatus">
                                <?php
                                    $status = bb_agency_get_posted_value('JobStatus', isset($Job) ? $Job : null);
                                    foreach (array( 
                                        1 => __("Active", bb_agency_TEXTDOMAIN),
                                        0 => __("Inactive", bb_agency_TEXTDOMAIN),
                                        2 => __("Archived", bb_agency_TEXTDOMAIN)
                                    ) as $key => $label) : ?>
                                <option value="<?php echo $key ?>" <?php selected($key, $status) ?>><?php echo $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('PO Number', bb_agency_TEXTDOMAIN) ?></th>
                        <td>
                            <input class="regular-text" type="text" id="JobPONumber" name="JobPONumber" value="<?php bb_agency_posted_value('JobPONumber', isset($Job) ? $Job : null) ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Notes', bb_agency_TEXTDOMAIN) ?></th>
                        <td>
                            <textarea class="large-text" id="JobNotes" name="JobNotes"><?php bb_agency_posted_value('JobNotes', isset($Job) ? $Job : null) ?></textarea>
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
                        <th scope="row"><?php _e("Models booked", bb_agency_TEXTDOMAIN) ?></th>
                        <td>
                            <select multiple id="JobModelBooked" name="JobModelBooked[]" size="5">
                                <option value="">--</option>
                                <?php
                                    $booked = bb_agency_get_posted_value('JobModelBooked', isset($Job) ? $Job : null, true);
                                    foreach ($models as $model) : ?>
                                <option value="<?php echo $model->ID ?>" <?php selected(!empty($booked) && in_array($model->ID, $booked)) ?>><?php echo $model->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e("Models called for casting", bb_agency_TEXTDOMAIN) ?></th>
                        <td>
                            <select multiple id="JobModelCasted" name="JobModelCasted[]" size="15">
                                <?php 
                                    $casted = bb_agency_get_posted_value('JobModelCasted', isset($Job) ? $Job : null, true);
                                    foreach ($models as $model) : ?>
                                <option value="<?php echo $model->ID ?>" <?php selected(!empty($casted) && in_array($model->ID, $casted)) ?>><?php echo $model->name ?></option>    
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($action == 'edit') : ?>
    <?php _e("Last updated on", bb_agency_TEXTDOMAIN) ?> <?php echo $Job['JobDateUpdated'] ?>

    <p class="submit">
         <input type="hidden" name="id" value="<?php echo $_REQUEST['id'] ?>" />
         <input type="hidden" name="action" value="edit" />
         <input type="submit" name="submit" value="<?php _e("Update Job", bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
         <a href="<?php echo admin_url('admin.php?page=bb_agency_jobs&action=invoice&id='.$_REQUEST['id']) ?>" title="Generate an invoice for this job"><?php _e('Invoice', bb_agency_TEXTDOMAIN) ?></a>
    </p>
    <?php else : ?>
    <p class="submit">
         <input type="hidden" name="action" value="add" />
         <input type="submit" name="submit" value="<?php _e("Create Job", bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
    </p>
    <?php endif; ?> 
</form>
