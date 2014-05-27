<div id="postbox-container-2" class="postbox-container" style="width: 70%">
    <div id="side-sortables" class="meta-box-sortables ui-sortable" style="margin: 0px;">
        <div id="dashboard_recent_drafts" class="postbox" style="display: block;">
            <div class="handlediv" title="Click to toggle"><br></div>
            <h3 class="hndle"><span><?php echo __("Filter Jobs", bb_agency_TEXTDOMAIN ) ?></span></h3>
            <div class="inside">
                <form style="display: inline;" method="GET" action="<?php echo admin_url('admin.php?page=' . $_GET['page']) ?>">
                    <input type="hidden" name="page_index" id="page_index" value="<?php echo $_GET['page_index'] ?>" />
                    <input type="hidden" name="page" id="page" value="<?php echo $_GET['page'] ?>" />
                    <input type="hidden" name="type" value="name" />
                    <p id="filter-profiles">
                        <span><?php _e("<label>Job Title</label>", bb_agency_TEXTDOMAIN) ?><input type="text" name="JobTitle" value="<?php bbagency_posted_value('JobTitle') ?>" /></span>
                        <span><?php _e("<label>Client</label>", bb_agency_TEXTDOMAIN) ?><input type="text" name="JobClient" value="<?php bbagency_posted_value('JobClient') ?>" /></span> 
                        <span class="submit"><input type="submit" value="<?php _e("Filter", bb_agency_TEXTDOMAIN) ?>" class="button-primary" /></span>
                    </p>
                </form>
                <form style="display: inline; float: left; margin: 17px 5px 0px 0px;" method="GET" action="<?php echo admin_url("admin.php?page=" . $_GET['page']) ?>">
                     <input type="hidden" name="page_index" id="page_index" value="<?php echo $_GET['page_index'] ?>" />  
                     <input type="hidden" name="page" id="page" value="<?php echo $_GET['page'] ?>" />
                     <input type="submit" value="<?php _e("Clear Filters", bb_agency_TEXTDOMAIN) ?>" class="button-secondary" />
                </form>
                <a  style="display: inline; float: left; margin: 17px 5px 0px 0px;" href="<?php echo admin_url('admin.php?page=bb_agency_jobsearch') ?>" class="button-secondary"><?php _e("Advanced Search", bb_agency_TEXTDOMAIN) ?></a>
            </div>
        </div>
    </div>
</div>