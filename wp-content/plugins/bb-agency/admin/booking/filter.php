<p id="bb_filter" class="search-box">
    <input type="hidden" name="action" value="search" />
    <label class="screen-reader-text" for="s">Search Bookings:</label>
    <input type="text" name="s" value="<?php echo !empty($_REQUEST['s']) ? $_REQUEST['s'] : '' ?>" />
    <input type="submit" name="search" value="<?php _e('Search', bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
</p>