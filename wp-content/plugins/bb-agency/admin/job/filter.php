<p id="bb_filter" class="search-box">
    <input type="hidden" name="page_index" id="page_index" value="<?php echo $_GET['page_index'] ?>" />
    <input type="hidden" name="action" value="search" /><label class="screen-reader-text" for="s">Search Jobs:</label>
    <input type="text" name="s" value="<?php bbagency_posted_value('JobSearch') ?>" />
    <input type="submit" value="<?php _e('Search', bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
</p>