<?php

class ModelCard {

    private $model;
    private $canvas;
    private $quality = 90;
    private $fontfile;
    private $profile = array();
    private $text_colour;
    private $text_x = 500;
    private $text_y = 70;
    private $text_size = 14;
    private $error = 'Unknown error';
    
    function __construct($model) {
        $this->model = $model;

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

        if (!$force && @file_exists($this->filepath()))
            return true;

        // Create a blank image and add some text
        $this->canvas = imagecreatetruecolor(800, 600);

        // white background
        $colour = imagecolorallocate($this->canvas, 255, 255, 255);
        imagefill($this->canvas, 0, 0, $colour);

        // black text
        $this->text_colour = imagecolorallocate($this->canvas, 0, 0, 0);

        $filepath = bb_agency_UPLOADPATH . '/' . $this->profile->ProfileGallery . '/' . $this->profile->ProfileMediaURL;

        if (file_exists($filepath)) {
            $headshot = $this->image_resize( $filepath, 350, 500, true );

            if (!empty($headshot))
                imagecopy($this->canvas, $headshot, 50, 50, 0, 0, imagesx($headshot), imagesy($headshot));

            else
                return $this->fatal("Failed to copy profile image  to card: ".$this->error);
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
            $this->fatal("Unable to write to ".$this->filepath());

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

        if (!@file_exists($path)) {
            if (!$this->save())
                return $this->fatal('Failed to save image to '.$path);
        }

        // Set the content type header - in this case image/jpeg
        header('Content-Type: image/jpeg');

        // write image to browser
        echo file_get_contents($path);
    }

    public function filepath() {
        return bb_agency_UPLOADPATH .$this->profile->ProfileGallery.'/'.$this->filename();
    }

    private function print_model_details() {

        // print first name
        $name = $this->profile->ProfileContactNameFirst;
        $this->print_text( $name );

        $this->text_y += 50;

        if (bb_agency_SITETYPE == 'children') {
            // children
            if ($age = $this->get_age()) {
                $this->print_text( 'Age: ' . $age );
                $this->text_y += 50;
            }

            if ($this->profile->height) {
                $this->print_text( 'Height: ' . $this->get_height() );
                $this->text_y += 50;
            }

            if ($shoe_size = $this->get_shoe_size()) {
                $this->print_text( 'Shoe size: ' . $shoe_size );
                $this->text_y += 50;
            } 
        } else {
            // pregnant women
            $this->print_text( 'Due date: ' . $this->get_date( $this->profile->ProfileDateDue ) );
            $this->text_y += 50;

            if ($this->profile->height) {
                $this->print_text( 'Height: ' . $this->get_height() );
                $this->text_y += 50;
            }

            if ($dress_size = $this->get_dress_size()) {
                $this->print_text( 'Dress size: ' . $dress_size );
                $this->text_y += 50;
            } 
        }
    }

    private function print_company_details() {
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

    private function print_logo() {
        // get logo
        /*
        $logo_option = get_option('cmsms_options_newgate_logo_image');
        $logo_url = $logo_option['newgate_logo_url'];
        $logo_path = str_replace(get_bloginfo('wpurl'), ABSPATH, $logo_url);
        */
        $logo_path = ABSPATH . '/wp-content/uploads/2014/07/Kiddiwinks-Logo.png';

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
    private function filename() {
        return str_replace(' ', '-', get_bloginfo('name', 'display')).'-'.$this->profile->ProfileContactNameFirst.'.jpg';
    }

    private function set_profile() {
        global $wpdb;
        $t_profile = table_agency_profile;
        $t_datatype = table_agency_data_type;
        $t_media = table_agency_profile_media;
        $t_custom = table_agency_customfield_mux;
        $query = "
            SELECT p.*, dt.`DataTypePrivacy` AS ProfilePrivacy, m.`ProfileMediaURL`, h.`ProfileCustomValue` AS height, ss.`ProfileCustomValue` AS shoe_size, ds.`ProfileCustomValue` AS dress_size
            FROM $t_profile AS p
            LEFT JOIN $t_datatype AS dt ON dt.`DataTypeID` = p.`ProfileType`
            LEFT JOIN $t_media AS m ON m.`ProfileID` = p.`ProfileID` AND m.`ProfileMediaPrimary` = 1 AND m.`ProfileMediaType` = 'Image'
            LEFT JOIN $t_custom AS h ON h.`ProfileID` = p.`ProfileID` AND h.`ProfileCustomID` = 5
            LEFT JOIN $t_custom AS ss ON ss.`ProfileID` = p.`ProfileID` AND ss.`ProfileCustomID` = 10
            LEFT JOIN $t_custom AS ds ON ds.`ProfileID` = p.`ProfileID` AND ds.`ProfileCustomID` = 13
            WHERE p.`ProfileGallery` = '$this->model'
            LIMIT 1";

        $this->profile = $wpdb->get_row($query);
    }

    private function print_text( $string ) {
        imagettftext($this->canvas, $this->text_size, 0, $this->text_x, $this->text_y, $this->text_colour, $this->fontfile, $string);
    }

    private function get_age() {
        if (!$this->profile->ProfileDateBirth || strpos($this->profile->ProfileDateBirth, '0') == 0)
            return false;

        $birthday = new DateTime($this->profile->ProfileDateBirth);
        $interval = $birthday->diff(new DateTime);
        if ($interval->y > 1)
            return $interval->y;
        elseif ($interval->y > 0)
            return '1 '.__('year', bb_agency_TEXTDOMAIN).' '.$interval->m.' '.__('months', bb_agency_TEXTDOMAIN);
        else
            return $interval->m . ' '.__('months', bb_agency_TEXTDOMAIN);
    }

    private function get_date( $date ) {
        return bb_agency_displaydate($date);
    }

    private function get_height() {

        $height = intval($this->profile->height);

        if (bb_agency_get_option('bb_agency_option_unittype') == 0)
            return $height.' '.__('cm', bb_agency_TEXTDOMAIN);

        else {
            $feet = floor(intval($height) / 12);
            $inch = intval($height) - floor($feet * 12);

            return $feet.__('ft', bb_agency_TEXTDOMAIN).' '.$inch.__('in', bb_agency_TEXTDOMAIN);
        }
    }

    private function get_shoe_size() {
        return $this->profile->shoe_size;
    }

    private function get_dress_size() {
        return $this->profile->dress_size;
    }

    private function imagecreatefromfile( $filename ) {
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
                return $this->fatal('File "'.$filename.'" is not valid jpg, png or gif image.');
            break;
        }
    }

    private function image_resize($src, $width, $height, $crop = false){

        if ((!list($w, $h) = getimagesize($src)) || $w == 0 || $h == 0)
            return $this->set_error("Unsupported picture type ".basename($src));

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
            return $this->set_error("Failed to read image ".basename($src));

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

        return $new;
    }

    private function set_error($message) {
        $this->error = $message;
        error_log(__CLASS__.': '.$message);
        return false;
    }

    public function get_error() {
        return $this->error;
    }

    private function fatal($message) {
        $this->set_error( '(' . $this->profile->ProfileGallery . ') ' . $message );
        return false;
    }
}