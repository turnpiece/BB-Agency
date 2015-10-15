<div class="wrap">        
    <?php 
    // Include Admin Menu
    include ("admin-menu.php"); 

    global $wpdb;
 
    if (!isset($_SESSION['profile_start']))
        $_SESSION['profile_start'] = 0;

    ?>
    <h2><?php _e('Import from another site', bb_agency_TEXTDOMAIN) ?></h2>
    <?php 
        if ($_POST) {
            $profiles = bb_import_from_database();

            if ($profiles) {
                bb_log_message( "Transferred $profiles profiles." );
                $_SESSION['profile_start'] += $profiles;
            }
            else
                bb_log_message( "Did not transfer any profiles." );
        } 
    ?>
    <form action="" method="post">
        <table>
            <tr>
                <td><label for="db_name"><?php _e( "Database name", bb_agency_TEXTDOMAIN ) ?></label></td>
                <td><input type="text" name="db_name" value="<?php echo $_POST ? $_POST['db_name'] : '' ?>" /></td>
            </tr>
            <tr>
                <td><label for="db_user"><?php _e( "Database user", bb_agency_TEXTDOMAIN ) ?></label></td>
                <td><input type="text" name="db_user" value="<?php echo $_POST ? $_POST['db_user'] : '' ?>" /></td>
            </tr>
            <tr>
                <td><label for="db_pass"><?php _e( "Database password", bb_agency_TEXTDOMAIN ) ?></label></td>
                <td><input type="password" name="db_pass" value="<?php echo $_POST ? $_POST['db_pass'] : '' ?>" /></td>
            </tr>
            <tr>
                <td><label for="db_host"><?php _e( "Database host", bb_agency_TEXTDOMAIN ) ?></label></td>
                <td><input type="text" name="db_host" value="<?php echo $_POST ? $_POST['db_host'] : '' ?>" /></td>
            </tr>
            <tr>
                <td><label for="ExtProfileType"><?php _e( "Profile Type ID on source site", bb_agency_TEXTDOMAIN ) ?></label></td>
                <td><input type="text" name="ExtProfileType" value="<?php echo $_POST ? $_POST['ExtProfileType'] : '' ?>" /></td>
            </tr>
            <?php $dataTypes = bb_agency_get_datatypes(false); if (!empty($dataTypes)) : ?>
            <tr>
                <td><label for="ProfileType"><?php _e( 'Profile type to import into on this site', bb_agency_TEXTDOMAIN ) ?></label></td>
                <td>
                    <select name="ProfileType" id="ProfileType">               
                        <?php foreach ($dataTypes as $type) : ?>
                        <option value="<?php echo $dataType->DataTypeID ?>" <?php selected($type->DataTypeID, $_POST['ProfileType']) ?>><?php echo $type->DataTypeTitle ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <td><label for="media_url"><?php _e( "Media URL on source site", bb_agency_TEXTDOMAIN ) ?></label></td>
                <td><input type="text" name="media_url" value="" /></td>
            </tr>
        </table>
        <input type="submit" value="Import Now" class="button-primary">
    </form>
</div>
<?php



/******************************************************************************************/


/**
 *
 * import profiles from another database
 * ie. from another site
 *
 */
function bb_import_from_database() {

    global $wpdb;

    if (isset($_POST['db_host']) && 
        isset($_POST['db_name']) && 
        isset($_POST['db_user']) && 
        isset($_POST['db_pass'])) {

        $conn = mysqli_connect( $_POST['db_host'], $_POST['db_user'], $_POST['db_pass'] );

        if (!$conn)
            die( "Failed to connect to database as ".$_POST['db_user'] );

        mysqli_select_db( $conn, $_POST['db_name'] ) || die( "Unable to connect to database ". $_POST['db_name'] );

        // we're connected
        bb_log_message( 'Connected to external database '. $_POST['db_name'] );
        
        // get profile type
        $ExtProfileType = isset($_POST['ExtProfileType']) ? $_POST['ExtProfileType'] : 0;
        $ProfileType = isset($_POST['ProfileType']) ? $_POST['ProfileType'] : 0;

        $count_rs = mysqli_query( $conn, "SELECT COUNT(`ProfileID`) AS profiles FROM `".table_agency_profile."`" );
        $count = mysqli_fetch_object($count_rs);

        bb_log_message( "Found $count->profiles profiles." );

        $i = 0; //set counter

        $start = $_SESSION['profile_start'] ? $_SESSION['profile_start'] : 0; // set start point

        $sql = "SELECT * FROM `".table_agency_profile."` LIMIT $start, 50";

        if ($ProfileType > 0)
            $sql .= " WHERE `ProfileType` = $ExtProfileType";

        // get the profile table columns
        $p_cols = $wpdb->get_results("SHOW COLUMNS FROM `".table_agency_profile."`");

        // get media table columns
        $m_cols = $wpdb->get_results("SHOW COLUMNS FROM `".table_agency_profile_media."`");

        // custom fields
        $custom_fields = $wpdb->get_results("SELECT `ProfileCustomID`, `ProfileCustomTitle` FROM ". table_agency_customfields ." ORDER BY `ProfileCustomOrder`");

        $profiles = mysqli_query($conn, $sql);

        if (!mysqli_num_rows($profiles))
            die( "Failed to get any profiles." );

        while ( $profile = mysqli_fetch_array($profiles) ) {

            bb_log_message( $profile['ProfileContactDisplay'] );

            $insert_data = array();
            
            foreach ( $p_cols as $col ) {

                if ($col->Field == 'ProfileID') {
                    $oldID = $profile[$col->Field];
                    continue;
                } elseif ($col->Field == 'ProfileType') {
                    $insert_data[$col->Field] = $ProfileType;
                } else {
                    $insert_data[$col->Field] = $profile[$col->Field];
                }
            }

            $wpdb->insert( table_agency_profile, $insert_data );

            $ProfileID = $wpdb->insert_id;

            // do media
            $media_sql = "SELECT * FROM `".table_agency_profile_media."` WHERE `ProfileID` = $oldID";

            $medias = mysqli_query($conn, $media_sql);

            $media_url = $_POST['media_url'] ? trailingslashit($_POST['media_url']) : false;

            if (mysqli_num_rows($medias) > 0) {

                while( $media = mysqli_fetch_array($medias) ) {

                    $media_data = array( 'ProfileID' => $ProfileID );

                    foreach( $m_cols as $col ) {

                        if ($col->Field == 'ProfileMediaID' || $col->Field == 'ProfileID')
                            continue;

                        $media_data[$col->Field] = $media[$col->Field];
                    }

                    $wpdb->insert( table_agency_profile_media, $media_data );

                    if ($media_url && preg_match( "/Image|Headshot|Private/", $media['ProfileMediaType'] ) ) {
                        // get media and import it
                        $file = $profile['ProfileGallery'] . '/' . $media['ProfileMediaURL'];

                        // create gallery directory
                        bb_agency_createdir( $profile['ProfileGallery'] );

                        // save the file
                        if (bb_save_media( $media_url . $file, bb_agency_UPLOADPATH . $file ))
                            bb_log_message( "Saved media file $file" );
                        else
                            bb_log_message( "Failed to save media file $file" );
                    }
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

function bb_save_media($url, $saveto){
    bb_agency_debug( __FUNCTION__ . " $url => $saveto" );

    $ch = curl_init($url);

    if (!$ch) {
        bb_agency_debug( "failed to open cURL handle to $url" );
        return false;
    }

    if (curl_errno($ch)) {
        bb_log_message( 'ERROR:' . curl_error($ch) );
        return false;
    }

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $raw = curl_exec($ch);
    curl_close ($ch);

    if (file_exists($saveto)){
        bb_agency_debug( "deleting existing media file $saveto" );
        if (!unlink($saveto)) {
            bb_agency_debug( "failed to delete existing media file $saveto" );
            return false;  
        }
    }
    $fp = fopen($saveto,'x');
    fwrite($fp, $raw);
    fclose($fp);

    return true;
}

function bb_log_message($message) {
    bb_agency_debug( $message );

    echo '<p>' . $message . '</p>';
}