<div id="dashboard-widgets-wrap">
    <div id="dashboard-widgets" class="metabox-holder columns-2">
        <div id="postbox-container-1" class="postbox-container" style="width: 29%;">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable" style="margin: 0px;">
                <div id="dashboard_right_now" class="postbox">
                    <div class="handlediv" title="Click to toggle"><br /></div>
                    <h3 class="hndle"><span><?php echo __("Create New Job", bb_agency_TEXTDOMAIN); ?></span></h3>
                    <div class="inside-x" style="padding: 10px 10px 0px 10px; ">
                        <?php echo __("Currently " . count($results) . " jobs", bb_agency_TEXTDOMAIN); ?><br />
                        <p><a class="button-primary" href="<?php echo admin_url("admin.php?page=" . $_GET['page']) ?>&action=add"><?php _e("Create New Job", bb_agency_TEXTDOMAIN) ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>