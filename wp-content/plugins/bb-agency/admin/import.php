<div class="wrap">        
    <?php 
    // Include Admin Menu
    include ("admin-menu.php"); 

    global $wpdb;
 
    $number = 100;
    $start = isset($_REQUEST['profile_start']) ? $_REQUEST['profile_start'] : 0;

    ?>
    <h2><?php _e('Import from another site', bb_agency_TEXTDOMAIN) ?></h2>
    <?php 
        if ($_POST) {
            // process form
            if ($_POST && $_POST['import_type'] && $_POST['import_type'] == 'users') {
                $profiles = bb_agency_import_users();

                bb_log_message( "Linked $profiles profiles to user accounts." );

            } else {

                $profiles = bb_agency_import_profiles( $start, $number );
                
                if ($profiles) {
                    bb_log_message( "Transferred $profiles profiles." );
                    $start += $profiles;
                }
                else
                    bb_log_message( "Did not transfer any profiles." );
                
            }
        } 
    ?>
    <form id="import_form" action="" method="post">
        <input type="hidden" name="profile_start" value="<?php echo $start ?>" />
        <table class="form-table">
            <tr>
                <th><label for="import_type"><?php _e('Import type') ?></label></th>
                <td>
                    <select name="import_type" id="import_type">
                        <option value="profiles" <?php selected($_post['user_type'], 'profiles') ?>><?php _e('Profiles', bb_agency_TEXTDOMAIN) ?></option>
                        <option value="users" <?php selected($_post['user_type'], 'users') ?>><?php _e('Users', bb_agency_TEXTDOMAIN) ?></option>
                    </select>
                    <script>
                        jQuery(document).ready(function($) {

                            var type = $('#import_form').find('select#import_type');

                            type.on('change', function() {
                                toggleForm();
                            });

                            function toggleForm() {
                                var profile_fields = $('#import_form').find('.profiles-only');

                                if (type.val() == 'users')
                                    profile_fields.hide();
                                else
                                    profile_fields.show();        
                            }

                            toggleForm();
                        });
                    </script>
                </td>
            </tr>
            <tr>
                <th><label for="db_name"><?php _e( "Database name", bb_agency_TEXTDOMAIN ) ?></label></th>
                <td><input type="text" name="db_name" value="<?php echo $_POST ? $_POST['db_name'] : '' ?>" /></td>
            </tr>
            <tr>
                <th><label for="db_user"><?php _e( "Database user", bb_agency_TEXTDOMAIN ) ?></label></th>
                <td><input type="text" name="db_user" value="<?php echo $_POST ? $_POST['db_user'] : '' ?>" /></td>
            </tr>
            <tr>
                <th><label for="db_pass"><?php _e( "Database password", bb_agency_TEXTDOMAIN ) ?></label></th>
                <td><input type="password" name="db_pass" value="<?php echo $_POST ? $_POST['db_pass'] : '' ?>" /></td>
            </tr>
            <tr>
                <th><label for="db_host"><?php _e( "Database host", bb_agency_TEXTDOMAIN ) ?></label></th>
                <td><input type="text" name="db_host" value="<?php echo $_POST ? $_POST['db_host'] : '' ?>" /></td>
            </tr>
            <tr class="profiles-only">
                <th><label for="ExtProfileType"><?php _e( "Profile Type ID on source site", bb_agency_TEXTDOMAIN ) ?></label></th>
                <td><input type="text" name="ExtProfileType" value="<?php echo $_POST ? $_POST['ExtProfileType'] : '' ?>" /></td>
            </tr>
            <?php $dataTypes = bb_agency_get_datatypes(false); if (!empty($dataTypes)) : ?>
            <tr class="profiles-only">
                <th><label for="ProfileType"><?php _e( 'Profile type to import into on this site', bb_agency_TEXTDOMAIN ) ?></label></th>
                <td>
                    <select name="ProfileType" id="ProfileType">               
                        <?php foreach ($dataTypes as $type) : ?>
                        <option value="<?php echo $type->DataTypeID ?>" <?php echo selected($type->DataTypeID, $_POST['ProfileType']) ?>><?php echo $type->DataTypeTitle ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <?php endif; ?>
            <tr class="profiles-only">
                <th><label for="media_dir"><?php _e( "External media directory", bb_agency_TEXTDOMAIN ) ?></label></th>
                <td><input type="text" name="media_dir" value="<?php echo $_POST ? $_POST['media_dir'] : '' ?>" /></td>
            </tr>
        </table>
        <p>
            <input type="submit" value="Import Now" class="button-primary">
        </p>
    </form>
</div>
<?php



/******************************************************************************************/

function bb_agency_import_profiles( $start, $number ) {

    set_time_limit(0); // avoid time outs

    global $wpdb;

    if (isset($_POST['db_host']) && 
        isset($_POST['db_name']) && 
        isset($_POST['db_user']) && 
        isset($_POST['db_pass'])) {

        $conn = bb_agency_connect_db();

        // we're connected
        bb_log_message( 'Connected to external database '. $_POST['db_name'] );
        
        // get profile type
        $ExtProfileType = isset($_POST['ExtProfileType']) ? $_POST['ExtProfileType'] : 0;
        $ProfileType = isset($_POST['ProfileType']) ? $_POST['ProfileType'] : 0;

        $count_rs = mysqli_query( $conn, "SELECT COUNT(`ProfileID`) AS profiles FROM `".table_agency_profile."`" );
        $count = mysqli_fetch_object($count_rs);

        bb_log_message( "Found $count->profiles profiles." );

        $i = 0; //set counter

        $sql = "SELECT * FROM `".table_agency_profile."`";

        if ($ProfileType > 0)
            $sql .= " WHERE `ProfileType` = $ExtProfileType";

        $sql .= " LIMIT $start, $number";

        // get the profile table columns
        $p_cols = $wpdb->get_results("SHOW COLUMNS FROM `".table_agency_profile."`");

        // get media table columns
        $m_cols = $wpdb->get_results("SHOW COLUMNS FROM `".table_agency_profile_media."`");

        // custom fields
        $custom_fields = $wpdb->get_results("SELECT `ProfileCustomID`, `ProfileCustomTitle` FROM ". table_agency_customfields ." ORDER BY `ProfileCustomOrder`");

        $profiles = mysqli_query($conn, $sql);

        // get uploads dir
        $uploads = wp_upload_dir();

        // set external media directory
        $media_dir = $_POST['media_dir'] ? $uploads['basedir'] . '/' . trailingslashit($_POST['media_dir']) : false;

        if (!mysqli_num_rows($profiles))
            die( "Failed to get any profiles starting from profile $start: '$sql'" );

        while ( $profile = mysqli_fetch_array($profiles) ) {

            bb_log_message( $profile['ProfileContactDisplay'] );

            $insert_data = array();
            
            foreach ( $p_cols as $col ) {

                if ($col->Field == 'ProfileID') {
                    $oldID = $profile[$col->Field];
                    continue;
                } elseif ($col->Field == 'ProfileType') {
                    $insert_data[$col->Field] = $ProfileType;
                } elseif ($col->Field == 'ProfileGallery') {
                    // create gallery directory
                    $insert_data['ProfileGallery'] = bb_agency_createdir( $profile['ProfileGallery'] );
                } else {
                    $insert_data[$col->Field] = $profile[$col->Field];
                }
            }

            $wpdb->insert( table_agency_profile, $insert_data );

            $ProfileID = $wpdb->insert_id;

            // do media
            $media_sql = "SELECT * FROM `".table_agency_profile_media."` WHERE `ProfileID` = $oldID";

            $medias = mysqli_query($conn, $media_sql);

            if (mysqli_num_rows($medias) > 0) {

                while( $media = mysqli_fetch_array($medias) ) {

                    $media_data = array( 'ProfileID' => $ProfileID );

                    foreach( $m_cols as $col ) {

                        if ($col->Field == 'ProfileMediaID' || $col->Field == 'ProfileID')
                            continue;

                        $media_data[$col->Field] = $media[$col->Field];
                    }

                    if ($media_dir && preg_match( "/Image|Headshot|Private/", $media['ProfileMediaType'] ) ) {
                        // get media and import it
                        $file = $media['ProfileMediaURL'];

                        // save the file
                        if (bb_agency_save_media( $media_dir . $profile['ProfileGallery'] . '/' . $file, bb_agency_UPLOADPATH . $insert_data['ProfileGallery'] . '/' . $file ) )
                            bb_log_message( "Saved media file $file" );
                        else
                            bb_log_message( "Failed to copy media file {$media_dir}/".$profile['ProfileGallery']."/{$file} => ".bb_agency_UPLOADPATH . $insert_data['ProfileGallery'] . "/{$file}" );
                    }

                    $wpdb->insert( table_agency_profile_media, $media_data );
                }
            }

            // now do custom fields
            if (!empty($custom_fields)) {
                foreach( $custom_fields as $field ) {

                    $ext_cf_sql = "SELECT cf.`ProfileCustomID`, cf.`ProfileCustomTitle`, cfm.`ProfileCustomValue` FROM ". table_agency_customfields ." cf, ".table_agency_customfield_mux." cfm WHERE cf.`ProfileCustomID` = cfm.`ProfileCustomID` AND cfm.`ProfileID` = $oldID";

                    $ext_cf = mysqli_query($conn, $ext_cf_sql);

                    if (mysqli_num_rows($ext_cf) == 0)
                        continue;

                    while( $ext_field = mysqli_fetch_array($ext_cf) ) {

                        if ($ext_field['ProfileCustomTitle'] == $field->ProfileCustomTitle) {

                            $wpdb->insert( 
                                table_agency_customfield_mux, 
                                array(
                                    'ProfileCustomID' => $field->ProfileCustomID,
                                    'ProfileID' => $ProfileID,
                                    'ProfileCustomValue' => $ext_field['ProfileCustomValue']
                                )
                            );
                        }
                    }  
                }         
            }
            /*
            // now delete the profile from the external site
            mysqli_query( $conn, "DELETE FROM `".table_agency_profile_media."` WHERE `ProfileID` = $oldID" );
            mysqli_query( $conn, "DELETE FROM `".table_agency_customfield_mux."` WHERE `ProfileID` = $oldID" );
            mysqli_query( $conn, "DELETE FROM `".table_agency_profile."` WHERE `ProfileID` = $oldID" );
            */
            // update counter
            $i++;

            // flush output
            flush();
        }

        return $i;
    
    } else {
        die( "Did not receive database connection details." );
    }
}

function bb_agency_import_users() {

    global $wpdb;

    // get profiles without an attached user
    $t_profiles = table_agency_profile;

    $profiles = $wpdb->get_results( "SELECT * FROM `$t_profiles` WHERE `ProfileUserLinked` IS NULL OR `ProfileUserLinked` = 0" );

    echo "<p>Found ".count($profiles)." unlinked profiles.</p>";

    if (isset($_POST['db_host']) && 
        isset($_POST['db_name']) && 
        isset($_POST['db_user']) && 
        isset($_POST['db_pass'])) {

        $conn = bb_agency_connect_db();

        echo '<ul>';

        // run through unlined profiles
        foreach ($profiles as $profile) {

            $message = array( $profile->ProfileContactDisplay.' has no linked user account...' );

            // look for external profile with same gallery name
            $sql = "SELECT * FROM `$t_profiles` WHERE `ProfileGallery` = '$profile->ProfileGallery'";

            $rs = mysqli_query($conn, $sql);

            if (mysqli_num_rows( $rs )) {
                while ($Profile = mysqli_fetch_array( $rs )) {

                    $ext_user_id = $Profile['ProfileUserLinked'];

                    // get user data
                    $user_sql = "SELECT * FROM `$wpdb->users` WHERE `ID` = $ext_user_id";

                    $user_rs = mysqli_query( $conn, $user_sql );

                    if (mysqli_num_rows( $user_rs )) {

                        $User = mysqli_fetch_array( $user_rs, MYSQLI_ASSOC );

                        // check if this user already exists
                        // by doing an email search
                        if ( ( $user_id = email_exists( $User['user_email'] ) ) || 
                            ( $user_id = username_exists( $User['user_login'] ) ) ) {
                            // we already have a user with this email address
                            // so let's use them instead
                            $message[] = "Linked profile to existing user account $user_id";

                        } else {

                            // transfer user from external site
                            $new_user = $User;

                            unset( $new_user['ID'] );

                            $message[] = '<pre>' . print_r($new_user) . '</pre>';

                            $user_id = wp_insert_user( $new_user );

                            if (is_wp_error( $user_id )) {
                                // assume it's already been done
                                continue;
                            }

                            // now put in usermeta
                            $usermeta_sql = "SELECT * FROM `$wpdb->usermeta` WHERE `user_id` = $ext_user_id";

                            $usermeta_rs = mysqli_query( $usermeta_sql );

                            if (mysqli_num_rows( $usermeta_rs )) {

                                while( $item = mysqli_fetch_array( $usermeta_rs ) ) {
                                    add_user_meta( $user_id, $item['meta_key'], $item['meta_value'], !is_serialized($item['meta_value']) );
                                }
                            }

                            $message[] = "Linked profile to newly created user account $user_id";
                        }

                        // link the profile to the user account
                        if ($wpdb->update( 
                            $t_profile, 
                            array( 'ProfileUserLinked' => $user_id ), 
                            array( 'ProfileID' => $profile->ProfileID ), 
                            array( '%d' ), 
                            array( '%d' ) 
                        ) !== false)
                            $linked++;
                        else
                            wp_die( "ERROR: Failed to update profile with linked account $user_id" );

                    } else {

                        $message[] = "ERROR: Failed to get user account $ext_user_id from external site database with query '$user_sql' : " . mysqli_error();
                    }
                }
            } 

            echo '<li>' . implode( '<br />', $message ) . '</li>'; 
        }

        echo '</ul>';

        return count($linked);
    }
}

function bb_agency_connect_db() {
    $conn = mysqli_connect( $_POST['db_host'], $_POST['db_user'], $_POST['db_pass'] );

    if (!$conn)
        die( "Failed to connect to database as ".$_POST['db_user'] );

    mysqli_select_db( $conn, $_POST['db_name'] ) || die( "Unable to connect to database ". $_POST['db_name'] );

    return $conn;
}

function bb_agency_save_media($path, $saveto){

    if (!file_exists($path))
        return false;

    if (file_exists($saveto)){
        bb_agency_debug( "deleting existing media file $saveto" );
        if (!unlink($saveto)) {
            bb_agency_debug( "failed to delete existing media file $saveto" );
            return false;  
        }
    }

    if (!is_dir(dirname($saveto))) {
        bb_agency_debug( "trying to create directory " . dirname($saveto) );
        if (!mkdir( dirname($saveto) )) {
            bb_agency_debug( "failed to create directory " . dirname($saveto) );
            return false;
        }
    }

    return copy( $path, $saveto );
}

function bb_log_message($message) {
    bb_agency_debug( $message );

    echo '<p>' . $message . '</p>';
}