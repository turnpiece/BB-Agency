<h2 class="title"><?php _e("Edit", bb_agency_TEXTDOMAIN) ?> job 
    <a class="button-secondary" href="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>"><?php _e("Back to job list", bb_agency_TEXTDOMAIN) ?></a> 
</h2>
<p><?php _e("Make changes in the form below to edit a job", bb_agency_TEXTDOMAIN) ?> <strong><?php _e("Required fields are marked", bb_agency_TEXTDOMAIN) ?>*</strong></p>
<form method="post" action="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>&action=<?php echo $action ?>">
    <div>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><?php _e('Job Title', bb_agency_TEXTDOMAIN) ?>*</th>
                    <td>
                        <input class="regular-text" type="text" id="JobTitle" name="JobTitle" value="<?php bbagency_posted_value('JobTitle', isset($Job) ? $Job : null) ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Client', bb_agency_TEXTDOMAIN) ?>*</th>
                    <td>
                        <input class="regular-text" type="text" id="JobClient" name="JobClient" value="<?php bbagency_posted_value('JobClient', isset($Job) ? $Job : null) ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Rate', bb_agency_TEXTDOMAIN) ?>*</th>
                    <td>
                        <input class="regular-text" type="text" id="JobRate" name="JobRate" value="<?php bbagency_posted_value('JobRate', isset($Job) ? $Job : null) ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Location', bb_agency_TEXTDOMAIN) ?>*</th>
                    <td>
                        <input class="regular-text" type="text" id="JobLocation" name="JobLocation" value="<?php bbagency_posted_value('JobLocation', isset($Job) ? $Job : null) ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Date', bb_agency_TEXTDOMAIN) ?>*</th>
                    <td>
                        <input type="text" class="bbdatepicker" id="JobDate" name="Jobdate" value="<?php bbagency_posted_value('JobDate', isset($Job) ? $Job : null) ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e("Status", bb_agency_TEXTDOMAIN) ?>*</th>
                    <td>
                        <select id="ProfileIsActive" name="JobStatus">
                            <?php
                                $status = bbagency_get_posted_value('JobStatus', isset($Job) ? $Job : null);
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
                        <input class="regular-text" type="text" id="JobPONumber" name="JobPONumber" value="<?php bbagency_posted_value('JobPONumber', isset($Job) ? $Job : null) ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Notes', bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <textarea class="large-text" id="JobNotes" name="JobNotes"><?php bbagency_posted_value('JobNotes', isset($Job) ? $Job : null) ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e("Model Booked", bb_agency_TEXTDOMAIN) ?></th>
                    <td>
                        <select id="ProfileIsActive" name="JobStatus">
                            <?php
                                // list models
                                $t_profiles = table_agency_profile;
                                $models = $wpdb->get_results("SELECT `ProfileID`, `ProfileContactDisplay` FROM $t_profiles ORDER BY `ProfileContactDisplay` ASC");
                                $booked = bbagency_get_posted_value('JobModelBooked', isset($Job) ? $Job : null);
                                foreach ($models as $model) : ?>
                            <option value="<?php echo $model->ProfileID ?>" <?php selected($model->ProfileID, $booked) ?>><?php echo $model->ProfileContactDisplay ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php if ($action == 'edit') : ?>
        <?php _e("Last updated on", bb_agency_TEXTDOMAIN) ?> <?php echo $Job['JobDateUpdated'] ?>

        <p class="submit">
             <input type="hidden" name="ProfileID" value="<?php echo $Job['JobID'] ?>" />
             <input type="hidden" name="action" value="edit" />
             <input type="submit" name="submit" value="<?php _e("Update Job", bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
        </p>
        <?php else : ?>
        <p class="submit">
             <input type="hidden" name="action" value="addRecord" />
             <input type="submit" name="submit" value="<?php _e("Create Job", bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
        </p>
        <?php endif; ?>
    </div>    
</form>
