<div class="wrap">        
    <?php 
    // Include Admin Menu
    include (dirname(dirname(__FILE__)).'/admin-menu.php');

// *************************************************************************************************** //
// Get Actions 

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'search') :


// *************************************************************************************************** //
// Get Search Results

    global $wpdb;   

    // Sort By
    $sort = isset($_REQUEST['sort']) && !empty($_REQUEST['sort']) ? $_REQUEST['sort'] : "`JobDate`";

    // Sort Order
    $dir = "";
    if (isset($_REQUEST['dir']) && !empty($_REQUEST['dir'])){
        $dir = $_REQUEST['dir'];
        if ($dir == "desc" || !isset($dir) || empty($dir)){
            $sortDirection = "asc";
        } else {
            $sortDirection = "desc";
        } 
    } else {
        $sortDirection = "desc";
        $dir = "asc";
    }

    // Filter
    $where = array();

    // Standard fields
    $fields = array(
        'JobTitle',
        'JobRate',
        'JobPONumber'
    );

    if (isset($_REQUEST['state'])) {
        // quick filter search
        $value = $_REQUEST['state'];
        foreach ($fields as $field) {
            $where[] = "`$field` LIKE '%$value%'"; 
        }

        // generate SQL 
        $sql = "SELECT j.*, p.`ProfileContactDisplay` AS ClientName FROM $t_job j LEFT JOIN $t_profile p ON j.`JobClient` = p.'`ProfileID`'";

        if (!empty($where)) {
            $sql .= ' WHERE '.implode(' OR ', $where);
        }
        
        $sql .= "ORDER BY $sort $dir";
        
    } else {
        // advanced search
        foreach ($fields AS $field) {
            if (isset($_REQUEST[$field]) && !empty($_REQUEST[$field])) {
                $value = $_REQUEST[$field];
                $where[] = "`$field` LIKE '%$value%'"; 
            }        
        }   

        // Location range search
        if (isset($_REQUEST['JobLocation']) && !empty($_REQUEST['JobLocation'])) {
            $JobLocation = $_REQUEST['JobLocation'];

            if ($location = bb_agency_geocode($JobLocation)) {
                $lat = $location['lat'];
                $lng = $location['lng'];
                $distance = "((ACOS(SIN($lat * PI() / 180) * SIN(`JobLocationLatitude` * PI() / 180) + COS($lat * PI() / 180) * COS(`JobLocationLatitude` * PI() / 180) * COS(($lng - `JobLocationLongitude`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)";
                $select[] = "$distance AS `distance`";
                $where[] = "`distance` <= 25";
                $sort = '`distance`';
                $sortDirection = "desc";
                $dir = "asc";
            }
        }

        // generate SQL 
        $sql = 'SELECT j.*, p.`ProfileContactDisplay` AS ClientName';

        if (!empty($select)) {
            $sql .= ', '. implode(', ', $select);
        }

        $sql .= " FROM $t_job j LEFT JOIN $t_profile p ON j.`JobClient` = p.'`ProfileID`'";

        if (!empty($where)) {
            $sql .= ' WHERE '.implode(' AND ', $where);
        }
        
        $sql .= "ORDER BY $sort $dir";
    }


    ?>
    <div class="boxblock-holder">
    <?php

    // Search Results
    $results = $wpdb->get_results($sql);
    $count = count($results);
    ?>
    <h2 class="title">Job Search Results: <?php echo $count ?></h2>

    <form method="POST" action="<?php echo admin_url('admin.php?page='. $_REQUEST['page']) ?>">
        <input type="hidden" name="page" id="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php
        if ($count > 0) :
            include('list_full.php');
        // check for no results
        else : ?>
        <p>
            <?php 
            if (!empty($where))
                _e("No jobs found.", bb_agency_TEXTDOMAIN);
            else
                _e("There aren't any jobs in the system yet.", bb_agency_TEXTDOMAIN);
            ?>
        </p>
        <?php endif; ?>
    </form>
<?php endif; // end of search action ?>

<div class="boxblock-container left-half">
    <div class="boxblock">
        <h3><?php _e("Job Search", bb_agency_TEXTDOMAIN) ?></h3>
        <div class="inner">
            <form method="GET" action="<?php echo admin_url('admin.php?page='. $_REQUEST['page']) ?>">
                <input type="hidden" name="page" id="page" value="<?php echo $_REQUEST['page'] ?>" />
                <input type="hidden" name="action" value="search" />
                <table cellspacing="0" class="widefat fixed">
                    <thead>
                        <tr>
                            <th scope="row"><?php _e("Title", bb_agency_TEXTDOMAIN) ?></th>
                            <td>
                                <input type="text" id="JobTitle" name="JobTitle" value="<?php bb_agency_posted_value('JobTitle') ?>" />               
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Client', bb_agency_TEXTDOMAIN) ?></th>
                            <td>
                                <?php echo bb_agency_client_dropdown('JobClient', bb_agency_get_posted_value('JobClient', isset($Job) ? $Job : null)) ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Rate', bb_agency_TEXTDOMAIN) ?></th>
                            <td>
                                <input type="text" id="JobRate" name="JobRate" value="<?php bb_agency_posted_value('JobRate', isset($Job) ? $Job : null) ?>" />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Location', bb_agency_TEXTDOMAIN) ?></th>
                            <td>
                                <input type="text" id="JobLocation" name="JobLocation" value="<?php bb_agency_posted_value('JobLocation', isset($Job) ? $Job : null) ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Status', bb_agency_TEXTDOMAIN) ?></th>
                            <td>
                                <select name="JobStatus" id="JobStatus">               
                                    <option value="">--</option>
                                    <?php
                                    $value = bb_agency_get_posted_value('JobStatus');
                                    $options = array(
                                        1 => __("Active", bb_agency_TEXTDOMAIN),
                                        0 => __("Inactive", bb_agency_TEXTDOMAIN),
                                        2 => __("Archived", bb_agency_TEXTDOMAIN)
                                    );
                                    foreach ($options as $id => $label) : ?>
                                    <option value="<?php echo $id ?>" <?php selected($value, $id) ?>><?php echo $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    </thead>
                </table>
                <p class="submit">
                    <input type="submit" value="<?php _e("Search Jobs", bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
                    <input type="reset" onclick="redirectSearch();" name="reset" value="<?php _e("Reset Form", bb_agency_TEXTDOMAIN) ?>" class="button-secondary" />
                </p>
             <form>
        <div>
    </div><!-- .container -->
</div>
 </div>
</div>