<p id="bb_filter" class="search-box">
    <input type="hidden" name="action" value="search" />
    <label class="screen-reader-text" for="s">Search Jobs:</label>
    <input type="text" name="s" value="<?php echo !empty($_REQUEST['s']) ? $_REQUEST['s'] : '' ?>" />
    <select id="JobStatus" name="JobStatus">
        <?php
            foreach (array(
                ''  => __('All', bb_agency_TEXTDOMAIN),
                1   => __("Active", bb_agency_TEXTDOMAIN),
                2   => __("Invoiced", bb_agency_TEXTDOMAIN),
                0   => __("Inactive", bb_agency_TEXTDOMAIN),
            ) as $key => $label) : ?>
        <option value="<?php echo $key ?>" <?php selected($key, $_REQUEST['JobStatus']) ?>><?php echo $label ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="search" value="<?php _e('Search', bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
</p>