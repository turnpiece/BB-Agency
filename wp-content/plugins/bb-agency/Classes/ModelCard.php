<?php

class ModelCard {

    protected $model;
    protected $canvas;
    protected $quality = 90;
    protected $fontfile;
    protected $profile = array();
    protected $text_colour;
    protected $text_x = 500;
    protected $text_y = 70;
    protected $text_size = 13;
    protected $line_height = 30;
    protected $error = 'Unknown error';
    protected $debugging = true;
    
    function __construct($model) {
        $this->model = $model;
        $this->debugging = $this->debugging && bb_agency_DEBUGGING;
        $this->set_profile();
    }

    /**
     *
     * save image
     *
     * @param bool $force
     * @return bool
     */
    function save($force = false) {

        $this->debug( __FUNCTION__ );

        if (!$force && !$this->debugging && @file_exists($this->filepath()))
            return true;

        // Create a blank image and add some text
        $this->canvas = imagecreatetruecolor(800, 600);

        // white background
        $colour = imagecolorallocate($this->canvas, 255, 255, 255);
        imagefill($this->canvas, 0, 0, $colour);

        // black text
        $this->text_colour = imagecolorallocate($this->canvas, 0, 0, 0);

        $filepath = bb_agency_UPLOADPATH . $this->profile['ProfileGallery'] . '/' . $this->profile['ProfileMediaURL'];

        if (file_exists($filepath)) {
            $headshot = $this->image_resize( $filepath, 350, 500, true );

            if (!empty($headshot))
                imagecopy($this->canvas, $headshot, 50, 50, 0, 0, imagesx($headshot), imagesy($headshot));

            else
                return $this->set_error("Failed to copy profile image to card: ".$this->error);
        }
        
        // set font
        $this->fontfile = dirname(dirname(__FILE__)).'/fonts/Raleway-Regular.ttf';

        // print model details
        $this->print_model_details();

        // print logo and company details
        $this->text_size = 11;
        $this->print_company_details();

        // Write to file image
        $success = false;

        if (is_writable(dirname($this->filepath())))
            $success = @imagejpeg($this->canvas, $this->filepath(), $this->quality);
        
        if (!$success)
            return $this->fatal("Unable to write to ".$this->filepath());

        // Free up memory
        imagedestroy($this->canvas);

        return $success;
    }

    /**
     *
     * delete image
     *
     */
    public function delete() {
        $path = $this->filepath();

        if (@file_exists($path))
            unlink($path);
    }

    /**
     *
     * display image
     *
     */
    public function display() {

        $path = $this->filepath();

        if (!@file_exists($path) || $this->debugging) {
            $this->debug( __FUNCTION__ . " card not found at $path so creating it..." );

            if (!$this->save(true))
                return $this->fatal('Failed to save image to '.$path);
        }

        // Set the content type header - in this case image/jpeg
        header('Content-Type: image/jpeg');

        // write image to browser
        echo file_get_contents($path);
    }

    public function filepath() {
        return bb_agency_UPLOADPATH .$this->profile['ProfileGallery'].'/'.$this->filename();
    }

    protected function print_model_details() {

        $this->debug(__FUNCTION__ . ' in parent class');

        if ($this->get_profile_field('ProfileType') == 2) {
            // families
            $names = array();

            if ($mum = $this->get_profile_field('mum_name'))
                $names[] = $mum;

            if ($dad = $this->get_profile_field('dad_name'))
                $names[] = $dad;
           
            $this->print_text( implode( ' & ', $names ) );

            $this->text_y += $this->line_height;

            for ($i = 1; $i <= 4; $i++) {
                if ($name = $this->get_profile_field('child'.$i.'_name')) {
                    if ($age = $this->get_age( $this->get_profile_field('child'.$i.'_dob') )) {
                        $name .= " ($age)";
                    }
                    $this->print_text( $name );
                    $this->text_y += $this->line_height;
                }
            }

        } else {
            // print first name
            $name = $this->profile['ProfileContactNameFirst'];
            $this->print_text( $name );
        }

        $this->text_y += $this->line_height;

        if (bb_agency_SITETYPE == 'children' && $this->get_profile_field('ProfileType') != 2) {
            // children
            if ($age = $this->get_age( $this->profile['ProfileDateBirth'] )) {
                $this->print_text( 'Age: ' . $age );
                $this->text_y += $this->line_height;
            }

            if ($this->profile['height']) {
                $this->print_text( 'Height: ' . $this->get_height() );
                $this->text_y += $this->line_height;
            }

            if ($shoe_size = $this->get_shoe_size()) {
                $this->print_text( 'Shoe size: ' . $shoe_size );
                $this->text_y += $this->line_height;
            }

        } elseif (bb_agency_SITETYPE == 'bumps') {
            // pregnant women
            $this->print_text( 'Due date: ' . $this->get_date( $this->profile['ProfileDateDue'] ) );
            $this->text_y += $this->line_height;

            if ($this->profile['height']) {
                $this->print_text( 'Height: ' . $this->get_height() );
                $this->text_y += $this->line_height;
            }

            if ($dress_size = $this->get_dress_size()) {
                $this->print_text( 'Dress size: ' . $dress_size );
                $this->text_y += $this->line_height;
            } 
        }
    }

    protected function print_company_details() {
        $this->text_y = 360;

        // print company logo
        $this->print_logo();

        $this->text_y += 120;

        // get site url
        $url = preg_replace('#^http(s)?://#', '', trim(get_bloginfo('wpurl'), '/'));

        $this->print_text( $url );

        $this->text_y += 30;

        if ($email = bb_agency_get_option('bb_agency_option_agencyemail')) {
            $this->print_text( $email );
            $this->text_y += 30;           
        }

        $this->print_text( bb_agency_PHONE );

        $this->text_y += 150;
    }

    protected function print_logo() {
        // get logo
        if (defined('bb_agency_LOGOPATH')) {
            $logo_path = bb_agency_LOGOPATH;
        } else {
            $logo_option = get_option('cmsms_options_newgate_logo_image');
            $logo_url = $logo_option['newgate_logo_url'];
            $logo_path = str_replace(get_bloginfo('wpurl'), ABSPATH, $logo_url);
        }

        // add logo to canvas
        if (file_exists($logo_path)) {
            $logo_img = $this->image_resize( $logo_path, 250, 90 );

            if (!empty($logo_img))
                imagecopy($this->canvas, $logo_img, $this->text_x, $this->text_y, 0, 0, imagesx($logo_img), imagesy($logo_img));
        }        
    }

    /**
     * filename
     *
     */
    protected function filename() {
        return str_replace(' ', '-', get_bloginfo('name')).'-'.$this->profile['ProfileContactNameFirst'].'.jpg';
    }

    protected function set_profile() {
        global $wpdb;
        $t_profile = table_agency_profile;
        $t_datatype = table_agency_data_type;
        $t_media = table_agency_profile_media;
        $t_custom = table_agency_customfield_mux;
        $query = "
            SELECT p.*, 
                dt.`DataTypePrivacy` AS ProfilePrivacy, 
                m.`ProfileMediaURL`, 
                h.`ProfileCustomValue` AS height, 
                ss.`ProfileCustomValue` AS shoe_size, 
                ds.`ProfileCustomValue` AS dress_size,
                mum.`ProfileCustomValue` AS mum_name, 
                dad.`ProfileCustomValue` AS dad_name,
                child1.`ProfileCustomValue` AS child1_name,
                child2.`ProfileCustomValue` AS child2_name,
                child3.`ProfileCustomValue` AS child3_name,
                child4.`ProfileCustomValue` AS child4_name,
                child1_dob.`ProfileCustomValue` AS child1_dob,
                child2_dob.`ProfileCustomValue` AS child2_dob,
                child3_dob.`ProfileCustomValue` AS child3_dob,
                child4_dob.`ProfileCustomValue` AS child4_dob
            FROM $t_profile AS p
            LEFT JOIN $t_datatype AS dt ON dt.`DataTypeID` = p.`ProfileType`
            LEFT JOIN $t_media AS m ON m.`ProfileID` = p.`ProfileID` AND m.`ProfileMediaPrimary` = 1 AND m.`ProfileMediaType` = 'Image'
            LEFT JOIN $t_custom AS h ON h.`ProfileID` = p.`ProfileID` AND h.`ProfileCustomID` = 5
            LEFT JOIN $t_custom AS ss ON ss.`ProfileID` = p.`ProfileID` AND ss.`ProfileCustomID` = 10
            LEFT JOIN $t_custom AS ds ON ds.`ProfileID` = p.`ProfileID` AND ds.`ProfileCustomID` = 13
            LEFT JOIN $t_custom AS mum ON mum.`ProfileID` = p.`ProfileID` AND mum.`ProfileCustomID` = 42
            LEFT JOIN $t_custom AS dad ON dad.`ProfileID` = p.`ProfileID` AND dad.`ProfileCustomID` = 43
            LEFT JOIN $t_custom AS child1 ON child1.`ProfileID` = p.`ProfileID` AND child1.`ProfileCustomID` = 44
            LEFT JOIN $t_custom AS child2 ON child2.`ProfileID` = p.`ProfileID` AND child2.`ProfileCustomID` = 45            
            LEFT JOIN $t_custom AS child3 ON child3.`ProfileID` = p.`ProfileID` AND child3.`ProfileCustomID` = 46
            LEFT JOIN $t_custom AS child4 ON child4.`ProfileID` = p.`ProfileID` AND child4.`ProfileCustomID` = 47
            LEFT JOIN $t_custom AS child1_dob ON child1_dob.`ProfileID` = p.`ProfileID` AND child1_dob.`ProfileCustomID` = 48
            LEFT JOIN $t_custom AS child2_dob ON child2_dob.`ProfileID` = p.`ProfileID` AND child2_dob.`ProfileCustomID` = 49            
            LEFT JOIN $t_custom AS child3_dob ON child3_dob.`ProfileID` = p.`ProfileID` AND child3_dob.`ProfileCustomID` = 50
            LEFT JOIN $t_custom AS child4_dob ON child4_dob.`ProfileID` = p.`ProfileID` AND child4_dob.`ProfileCustomID` = 51
            WHERE p.`ProfileGallery` = '%s'
            LIMIT 1";

        $this->debug( __FUNCTION__ . ' => ' . $query );

        $profile = $wpdb->get_row( $wpdb->prepare( $query, $this->model ), ARRAY_A );

        if (empty($profile))
            return $this->fatal( "Failed to get profile for model '$this->model' - " . mysql_error() );

        $this->debug( print_r($profile, true) );

        $this->profile = $profile;
    }

    protected function print_text( $string ) {
        imagettftext($this->canvas, $this->text_size, 0, $this->text_x, $this->text_y, $this->text_colour, $this->fontfile, $string);
    }

    /**
     *
     * get age
     *
     * @param string $dob
     * @return string
     *
     */
    protected function get_age( $dob ) {

        $birthday = new DateTime($dob);
        $interval = $birthday->diff(new DateTime);
        if ($interval->y > 1)
            return $interval->y;
        elseif ($interval->y > 0)
            return '1 '.__('year', bb_agency_TEXTDOMAIN).' '.$interval->m.' '.__('months', bb_agency_TEXTDOMAIN);
        else
            return $interval->m . ' '.__('months', bb_agency_TEXTDOMAIN);
    }

    protected function get_date( $date ) {
        return bb_agency_displaydate($date);
    }

    protected function get_height() {

        $height = intval($this->profile['height']);

        if (preg_match('/cm$/', $height))
            return $height;
        
        if (bb_agency_get_option('bb_agency_option_unittype') == 0)
            return floor($height + .5).' '.__('cm', bb_agency_TEXTDOMAIN);

        else {
            $feet = floor(intval($height) / (2.54 * 12));
            $inch = intval($height) - floor($feet * 12);

            return $feet.__('ft', bb_agency_TEXTDOMAIN).' '.$inch.__('in', bb_agency_TEXTDOMAIN);
        }
    }

    protected function get_shoe_size() {
        return $this->profile['shoe_size'];
    }

    protected function get_dress_size() {
        return $this->profile['dress_size'];
    }

    protected function imagecreatefromfile( $filename ) {
        if (!file_exists($filename)) {
            return $this->fatal('File "'.$filename.'" not found.');
        }
        switch ( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ))) {
            case 'jpeg':
            case 'jpg':
                return imagecreatefromjpeg($filename);
            break;

            case 'png':
                return imagecreatefrompng($filename);
            break;

            case 'gif':
                return imagecreatefromgif($filename);
            break;

            default:
                return $this->set_error('File "'.$filename.'" is not valid jpg, png or gif image.');
            break;
        }
    }

    protected function image_resize($src, $width, $height, $crop = false){

        if ((!list($w, $h) = getimagesize($src)) || $w == 0 || $h == 0)
            return $this->set_error("$src is an unsupported picture type");

        $this->debug( __FUNCTION__ . " resizing image $src" );

        $type = strtolower(substr(strrchr($src, "."), 1));
        
        if ($type == 'jpeg') 
            $type = 'jpg';

        switch($type){
            case 'bmp': 
                $img = @imagecreatefromwbmp($src); 
                break;
            case 'gif': 
                $img = @imagecreatefromgif($src); 
                break;
            case 'jpg': 
                $img = @imagecreatefromjpeg($src); 
                break;
            case 'png': 
                $img = @imagecreatefrompng($src); 
                break;
            default : 
                return $this->set_error("Unsupported picture type ".basename($src));
        }

        if (empty($img))
            return $this->set_error("Failed to read $type image ".basename($src));

        // resize
        if ($crop){
            $ratio = max($width/$w, $height/$h);

            $h = $height / $ratio;
            $x = ($w - $width / $ratio) / 2;
            $w = $width / $ratio;
        }
        else{
            $ratio = min($width/$w, $height/$h);

            $width = $w * $ratio;
            $height = $h * $ratio;
            $x = 0;
        }

        $new = imagecreatetruecolor($width, $height);

        $colour = imagecolorallocate($new, 255, 255, 255);
        imagefill($new, 0, 0, $colour);

        imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

        $this->debug( __FUNCTION__ . " created $type image with width $width and height $height" );

        return $new;
    }

    /**
     *
     * get profile field
     *
     * @param string $key
     *
     */
    protected function get_profile_field( $key ) {
        $this->debug( __FUNCTION__ . ' ' . $key );

        if (empty($this->profile))
            return $this->set_error( "Profile was not set." );

        if (!isset($this->profile[$key]))
            return $this->set_error( "Profile field $key was not set" );

        return $this->profile[$key];
    }

    protected function debug($message) {
        if ($this->debugging)
            error_log(get_class().' DEBUG: '.$message);
    }

    protected function set_error($message) {
        $this->error = $message;
        error_log(get_class().' ERROR: '.$message);
        return false;
    }

    public function get_error() {
        return $this->error;
    }

    protected function fatal($message) {
        $this->set_error( $message );

        wp_die( $message );
    }
}