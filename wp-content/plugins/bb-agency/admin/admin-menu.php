<div id="bb-overview-icon" class="icon32"></div>  
<h2><?php echo bb_agency_PLUGIN_TITLE ?></h2> 
<?php settings_errors(); ?> 

<?php  
	if( isset( $_GET['page'] ) ) {  
	    $active_page = isset( $_GET['page'] ) ? $_GET['page'] : 'display_options'; 
	} // end if  
?>  
      
<h2 class="nav-tab-wrapper">  
    <a href="?page=bb_agency_menu" class="nav-tab <?php echo $active_page == 'bb_agency_menu' ? 'nav-tab-active' : ''; ?>">Overview</a>  
    <a href="?page=bb_agency_profiles" class="nav-tab <?php echo $active_page == 'bb_agency_profiles' ? 'nav-tab-active' : ''; ?>">Manage Profiles</a>  
    <a href="?page=bb_agency_search" class="nav-tab <?php echo $active_page == 'bb_agency_search' ? 'nav-tab-active' : ''; ?>">Search Profiles</a>
    <a href="?page=bb_agency_jobs" class="nav-tab <?php echo $active_page == 'bb_agency_jobs' ? 'nav-tab-active' : ''; ?>">Manage Jobs</a>  
    <a href="?page=bb_agency_reports" class="nav-tab <?php echo $active_page == 'bb_agency_reports' ? 'nav-tab-active' : ''; ?>">Tools &amp; Reports</a>  
    <a href="?page=bb_agency_settings" class="nav-tab <?php echo $active_page == 'bb_agency_settings' ? 'nav-tab-active' : ''; ?>">Settings</a>  
</h2>