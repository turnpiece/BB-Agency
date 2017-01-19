<div class="wrap">        
<?php 
    // Include Admin Menu
    include ("admin-menu.php");

	get_currentuserinfo(); 
	global $user_level, $wpdb;

	// get data types
	$dataTypes = bb_agency_get_datatypes(false);
?>
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder columns-2">
			<div id="postbox-container-1" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div id="dashboard_right_now" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo __("Quick Search", bb_agency_TEXTDOMAIN ) ?></span></h3>
						<div class="inside">
						<?php if ($user_level >= 7) : ?>
							<form method="GET" action="<?php echo admin_url("admin.php?page=bb_agency_search") ?>">
								<input type="hidden" name="page" id="page" value="bb_agency_search" />
								<input type="hidden" name="action" value="search" />
								<table class="form-table">
									<tbody>
									  	<tr valign="top">
											<th scope="row"><label for="blogname"><?php _e("Name", bb_agency_TEXTDOMAIN); ?></label></th>
											<td><input type="text" name="ProfileContactName" value="<?php echo $_SESSION['ProfileContactName']; ?>" class="regular-text" /></td>
									  	</tr>
									  	<?php if (!empty($dataTypes)) : ?>	
									    <tr>
									        <th scope="row"><?php _e("Classification", bb_agency_TEXTDOMAIN) ?>:</th>
									        <td><select name="ProfileType" id="ProfileType">               
												<option value=""><?php _e("Any Profile Type", bb_agency_TEXTDOMAIN) ?></option>
												<?php foreach ($dataTypes as $type) : ?>
												<option value="<?php echo $type->DataTypeID ?>" <?php selected($type->DataTypeID, $_SESSION['ProfileType']) ?>><?php echo $type->DataTypeTitle ?></option>
												<?php endforeach; ?>
									        	</select></td>
									        </td>
									    </tr>
										<?php else : $type = $dataTypes[0]; ?>
										<input type="hidden" name="ProfileType" value="<?php echo $type->DataTypeID ?>" />
										<?php endif; ?>
										<?php
											// do genders dropdown
											$genders = $wpdb->get_results("SELECT `GenderID`, `GenderTitle` FROM ". table_agency_data_gender, ARRAY_A);

											if (!empty($genders) && count($genders) > 0) : 
										?>
									    <tr>
									        <th scope="row"><?php _e("Gender", bb_agency_TEXTDOMAIN) ?>:</th>
									        <td><select name="ProfileGender" id="ProfileGender">  
													<option value=""><?php _e('All Genders', bb_agency_TEXTDOMAIN) ?></option>
													<?php foreach ($genders as $gender) : ?>
													<option value="<?php echo $gender["GenderID"] ?>" <?php echo selected( $_SESSION['ProfileGender'], $gender["GenderID"]) ?>><?php echo $gender["GenderTitle"] ?></option>
													<?php endforeach; ?>
												</select>
									        </td>
									    </tr>
										<?php endif; ?>
									    <tr>
									        <th scope="row"><?php _e("Age", bb_agency_TEXTDOMAIN) ?>:</th>
									        <td>
									        <fieldset>
			                                    <div>
			                                        <?php echo bb_agency_age_dropdown('ProfileAge_min') ?>
			                                    </div>
			                                    <div>
			                                        <?php echo bb_agency_age_dropdown('ProfileAge_max') ?>
			                                    </div>
									        </fieldset>
									        </td>
									    </tr>
									    <?php if (bb_agency_SITETYPE == 'bumps') : ?>
									    <tr>
									        <th scope="row"><?php _e("Due date", bb_agency_TEXTDOMAIN) ?>:</th>
									        <td>
									        <fieldset>
									        	<div><label><?php _e('From', bb_agency_TEXTDOMAIN) ?></label>
									        	<input type="text" class="stubby bbdatepicker" id="ProfileDateDue_min" name="ProfileDateDue_min" value="". $_SESSION['ProfileDateDue_min'] ."" /><br /></div>
									        	<div><label><?php _e('To', bb_agency_TEXTDOMAIN) ?></label>
									        	<input type="text" class="stubby bbdatepicker" id="ProfileDateDue_max" name="ProfileDateDue_max" value="". $_SESSION['ProfileDateDue_max'] ."" /></div>
									        </fieldset>
									        </td>
									    </tr>
										<?php endif; ?>
								  </thead>
								</table>
								<p class="submit">
								<input type="submit" value="<?php _e("Quick Search", bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
								<a href="?page=bb_agency_search" class="button-secondary"><?php _e("Advanced Search", bb_agency_TEXTDOMAIN) ?></a></p>
								</p>
			        	<form>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<div id="postbox-container-2" class="postbox-container">
			<div id="side-sortables" class="meta-box-sortables ui-sortable">

				<div id="dashboard_recent_drafts" class="postbox" style="display: block;">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><span><?php echo __("Recently Updated Profiles", bb_agency_TEXTDOMAIN ) ?></span></h3>
					<div class="inside">
						<ul>
						<?php
						if ($user_level >= 7) {
							// Recently Updated
							$query = "SELECT `ProfileID`, `ProfileContactDisplay`, `ProfileDateUpdated` FROM ". table_agency_profile ." ORDER BY `ProfileDateUpdated` DESC LIMIT 0,20";

							$results = $wpdb->get_results($query);

							$count = count($results);

							foreach ($results as $result) : ?>
								<li>
									<a href="?page=bb_agency_profiles&action=editRecord&ProfileID=<?php echo $data['ProfileID']; ?>"><?php echo stripslashes($result->ProfileContactDisplay) ?></a>
							    	<span class="add-new-h2">Updated <?php echo bb_agency_makeago(bb_agency_convertdatetime($result->ProfileDateUpdated)); ?></span>
								</li><?php
							endforeach;

							if ($count < 1) {
								_e("There are currently no profiles", bb_agency_TEXTDOMAIN);
							}
						}
						?>
						</ul>
					</div>
				</div>

				<div id="dashboard_recent_drafts" class="postbox" style="display: block;">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><span><?php echo __("Recently Viewed Profiles", bb_agency_TEXTDOMAIN ) ?></span></h3>
					<div class="inside">
						<ul>
						<?php
						if ($user_level >= 7) {
							// Recently Viewed
							$query = "SELECT `ProfileID`, `ProfileContactDisplay`, `ProfileDateViewLast`, `ProfileStatHits` FROM ". table_agency_profile ." ORDER BY `ProfileDateViewLast` DESC LIMIT 0,10";
							$results = $wpdb->get_results($query);

							$count = count($results);
							foreach ($results as $result) { 
								?>
								<li>
									<a href="?page=bb_agency_profiles&action=editRecord&ProfileID=<?php echo $data['ProfileID']; ?>"><?php echo stripslashes($result->ProfileContactDisplay) ?></a>
							    	<span class="add-new-h2"><?php echo $data['ProfileStatHits']; ?> <?php echo __("Views", bb_agency_TEXTDOMAIN ) ?></span>
							    	<span class="add-new-h2">Last viewed <?php echo bb_agency_makeago(bb_agency_convertdatetime($result->ProfileDateViewLast)); ?></span>
								</li><?php
							}

							if ($count < 1) {
								_e("There are currently no profiles", bb_agency_TEXTDOMAIN);
							}
						}
						?>
						</ul>
					</div>
				</div>

			</div>
		</div>
		<div class="clear"></div>

	</div>
</div>