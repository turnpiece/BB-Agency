<h3><?php _e("Settings", bb_agency_TEXTDOMAIN) ?></h3>
    <form method="post" action="">
    <input type="hidden" name="action" value="settings" />
    <?php
    settings_fields( 'bb-agency-settings-group' ); 
    //do_settings_fields( 'bb-agency-settings-group' );
    //$bb_options = bb_agency_get_option();
    
    $bb_agency_value_agencyname = bb_agency_get_option('bb_agency_option_agencyname');
    if (empty($bb_agency_value_agencyname)) { 
      $bb_agency_value_agencyname = get_bloginfo('name'); 
    }
    $bb_agency_value_agencyemail = bb_agency_get_option('bb_agency_option_agencyemail');
        if (empty($bb_agency_value_agencyemail)) { $bb_agency_value_agencyemail = get_bloginfo('admin_email'); }
    $bb_agency_value_maxwidth = bb_agency_get_option('bb_agency_option_agencyimagemaxwidth');
        if (empty($bb_agency_value_maxwidth)) { $bb_agency_value_maxwidth = "1000"; }
    $bb_agency_value_maxheight = bb_agency_get_option('bb_agency_option_agencyimagemaxheight');
        if (empty($bb_agency_value_maxheight)) { $bb_agency_value_maxheight = "800"; }
    $bb_agency_option_locationcountry = bb_agency_get_option('bb_agency_option_locationcountry');
        if (empty($bb_agency_option_locationcountry)) { $bb_agency_option_locationcountry = "UK"; }
    $bb_agency_option_profilelist_perpage = bb_agency_get_option('bb_agency_option_profilelist_perpage');
        if (empty($bb_agency_option_profilelist_perpage)) { $bb_agency_option_profilelist_perpage = "20"; }
    $bb_agency_option_persearch = bb_agency_get_option('bb_agency_option_persearch');
        if (empty($bb_agency_option_persearch)) { $bb_agency_option_persearch = "100"; }
    $bb_agency_option_showcontactpage = bb_agency_get_option('bb_agency_option_showcontactpage');
        if (empty($bb_agency_option_showcontactpage)) { $bb_agency_option_showcontactpage = "0"; }
    
    $bb_agency_option_profilelist_favorite = bb_agency_get_option('bb_agency_option_profilelist_favorite');
        if (empty($bb_agency_option_profilelist_favorite)) { $bb_agency_option_profilelist_favorite = "1"; }
    $bb_agency_option_profilelist_castingcart = bb_agency_get_option('bb_agency_option_profilelist_castingcart');
        if (empty($bb_agency_option_profilelist_castingcart)) { $bb_agency_option_profilelist_castingcart = "1"; }

    $bb_agency_option_privacy = bb_agency_get_option('bb_agency_option_privacy');
        if (empty($bb_agency_option_privacy)) { $bb_agency_option_privacy = "0"; }

    $bb_agency_option_pregnant = bb_agency_get_option('bb_agency_option_pregnant');
    ?>

   <input type="hidden" name="bb_agency_options[bb_agency_option_layoutprofile]" value="0" />
   <table class="form-table">
   <tr valign="top">
     <th scope="row"><?php _e('Pregnant Women', bb_agency_TEXTDOMAIN) ?></th>
     <td>
       <input type="checkbox" name="bb_agency_options[bb_agency_option_pregnant]" value="1" <?php checked($bb_agency_option_pregnant, '1') ?> /> This site includes pregnant women and will deal with due dates as well as birth dates<br />
     </td>
   </tr>
    <tr valign="top">
      <th scope="row" colspan="2"><h3><?php _e('Agency Details', bb_agency_TEXTDOMAIN) ?></h3></th>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Agency Name', bb_agency_TEXTDOMAIN) ?></th>
      <td><input name="bb_agency_options[bb_agency_option_agencyname]" value="<?php echo $bb_agency_value_agencyname ?>" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Agency Email', bb_agency_TEXTDOMAIN) ?></th>
      <td><input name="bb_agency_options[bb_agency_option_agencyemail]" value="<?php echo $bb_agency_value_agencyemail ?>" /></td>
    </tr>
   
    <tr valign="top">
      <th scope="row" colspan="2"><h3><?php _e('Location Options', bb_agency_TEXTDOMAIN) ?></h3></th>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Default Country', bb_agency_TEXTDOMAIN) ?></th>
      <td><input name="bb_agency_options[bb_agency_option_locationcountry]" value="<?php echo $bb_agency_option_locationcountry ?>" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Server Timezone', bb_agency_TEXTDOMAIN) ?></th>
      <td>
        <select name="bb_agency_options[bb_agency_option_locationtimezone]">
          <option value="+12" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "+12") ?>> UTC+12</option>
          <option value="+11" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "+11") ?>> UTC+11</option>
          <option value="+10" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "+10") ?>> UTC+10</option>
          <option value="+9" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "+9") ?>> UTC+9</option>
          <option value="+8" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "+8") ?>> UTC+8</option>
          <option value="+7" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "+7") ?>> UTC+7</option>
          <option value="+6" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "+6") ?>> UTC+6</option>
          <option value="+5" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "+5") ?>> UTC+5</option>
          <option value="+4" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "+4") ?>> UTC+4</option>
          <option value="+3" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "+3") ?>> UTC+3</option>
          <option value="+2" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "+2") ?>> UTC+2</option>
          <option value="+1" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "+1") ?>> UTC+1</option>
          <option value="0" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "0") ?>> UTC 0</option>
          <option value="-1" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "-1") ?>> UTC-1</option>
          <option value="-2" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "-2") ?>> UTC-2</option>
          <option value="-3" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "-3") ?>> UTC-3</option>
          <option value="-4" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "-4") ?>> UTC-4</option>
          <option value="-5" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "-5") ?>> UTC-5</option>
          <option value="-6" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "-6") ?>> UTC-6</option>
          <option value="-7" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "-7") ?>> UTC-7</option>
          <option value="-8" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "-8") ?>> UTC-8</option>
          <option value="-9" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "-9") ?>> UTC-9</option>
          <option value="-10" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "-10") ?>> UTC-10</option>
          <option value="-11" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "-11") ?>> UTC-11</option>
          <option value="-12" <?php selected(bb_agency_get_option('bb_agency_option_locationtimezone'), "-12") ?>> UTC-12</option>
        </select> (<a href="http://www.worldtimezone.com/index24.php" target="_blank">Find</a>)
      </td>
    </tr>
   
   
    <tr valign="top">
      <th scope="row"><?php _e('Unit Type', bb_agency_TEXTDOMAIN) ?></th>
      <td>
        <select name="bb_agency_options[bb_agency_option_unittype]">
          <option value="1" <?php selected(bb_agency_get_option('bb_agency_option_unittype'), 1) ?>> <?php _e("Imperial", bb_agency_TEXTDOMAIN) ?> (ft/in/lb)</option>
          <option value="0" <?php selected(bb_agency_get_option('bb_agency_option_unittype'), 0) ?>> <?php _e("Metric", bb_agency_TEXTDOMAIN) ?> (cm/kg)</option>
        </select>
      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php _e('Profiles Per Page', bb_agency_TEXTDOMAIN) ?></th>
      <td><input name="bb_agency_options[bb_agency_option_profilelist_perpage]" value="<?php echo $bb_agency_option_profilelist_perpage ?>" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Profiles Max Per Search', bb_agency_TEXTDOMAIN) ?></th>
      <td><input name="bb_agency_options[bb_agency_option_persearch]" value="<?php echo $bb_agency_option_persearch ?>" /></td>
    </tr>
           
    <tr valign="top">
      <th scope="row" colspan="2"><h3><?php _e('Allow Profile Deletion', bb_agency_TEXTDOMAIN) ?></h3></th>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Delete Options', bb_agency_TEXTDOMAIN) ?></th>
      <td>
        <input type="radio" name="bb_agency_options[bb_agency_option_profiledeletion]" value="1" ".checked(bb_agency_get_option('bb_agency_option_profiledeletion'), 1,false)."/> <?php _e("No", bb_agency_TEXTDOMAIN) ?><br />
        <input type="radio" name="bb_agency_options[bb_agency_option_profiledeletion]" value="2" ".checked(bb_agency_get_option('bb_agency_option_profiledeletion'), 2,false)."/> <?php _e("Yes (Allow USers to delete)", bb_agency_TEXTDOMAIN) ?><br />
        <input type="radio" name="bb_agency_options[bb_agency_option_profiledeletion]" value="3" ".checked(bb_agency_get_option('bb_agency_option_profiledeletion'), 3,false)."/> <?php _e("Archive Only (Users can remove themselves as active but profile remains)", bb_agency_TEXTDOMAIN) ?><br />
              </td>
    </tr>      
                   
    <tr valign="top">
      <th scope="row" colspan="2"><h3><?php _e('Profile View Options', bb_agency_TEXTDOMAIN) ?></h3></th>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Path to Logo', bb_agency_TEXTDOMAIN) ?></th>
      <td><input name="bb_agency_options[bb_agency_option_agencylogo]" value="". bb_agency_get_option('bb_agency_option_agencylogo') ."" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Email Header', bb_agency_TEXTDOMAIN) ?></th>
      <td><input name="bb_agency_options[bb_agency_option_agencyheader]" value="". bb_agency_get_option('bb_agency_option_agencyheader') ."" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Profile List Style', bb_agency_TEXTDOMAIN) ?></th>
      <td>
        <select name="bb_agency_options[bb_agency_option_layoutprofilelist]">
          <option value="0" <?php selected(bb_agency_get_option('bb_agency_option_layoutprofilelist'), 0) ?>> <?php _e("Name Over Image", bb_agency_TEXTDOMAIN) ?></option>
          <option value="1" <?php selected(bb_agency_get_option('bb_agency_option_layoutprofilelist'), 1) ?>> <?php _e("Name Under Image with Color", bb_agency_TEXTDOMAIN) ?></option>
          <option value="2" <?php selected(bb_agency_get_option('bb_agency_option_layoutprofilelist'), 2) ?>> <?php _e("Name Under Image", bb_agency_TEXTDOMAIN) ?></option>
        </select>
      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php _e('Privacy Settings', bb_agency_TEXTDOMAIN) ?></th>
      <td>
        <select name="bb_agency_options[bb_agency_option_privacy]">
          <option value="2" <?php selected($bb_agency_option_privacy, 2) ?>> <?php _e("Must be logged to view model list and profile information", bb_agency_TEXTDOMAIN) ?></option>
          <option value="1" <?php selected($bb_agency_option_privacy, 1) ?>> <?php _e("Model list public. Must be logged to view profile information", bb_agency_TEXTDOMAIN) ?></option>
          <option value="0" <?php selected($bb_agency_option_privacy, 0) ?>> <?php _e("Model list and profile information public", bb_agency_TEXTDOMAIN) ?></option>
        </select>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Show Fields', bb_agency_TEXTDOMAIN) ?></th>
      <td>
        <input type="checkbox" name="bb_agency_options[bb_agency_option_showsocial]" value="1" ".checked(bb_agency_get_option('bb_agency_option_showsocial'), 1,false)."/> Extended Social Profiles<br />
        <input type="checkbox" name="bb_agency_options[bb_agency_option_advertise]" value="1" ".checked(bb_agency_get_option('bb_agency_option_advertise'), 1,false)."/> Remove Updates on Dashboard<br />
      </td>
    </tr>
  </table>
  <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
  <input type="hidden" name="bb_agency_options[bb_agency_options_showtooltip]" value="1" />
</form>