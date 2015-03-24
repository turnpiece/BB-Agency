<?php

class ModelCard {

    private $model;
    private $canvas;
    private $quality = 90;
    private $fontfile;
    private $profile = array();
    private $text_colour;
    private $text_x = 500;
    private $text_y = 50;
    private $text_size = 11;
    
    function __construct($model) {
        $this->model = $model;

        $this->set_profile();
        $this->save();
    }

    function save() {

        // Create a blank image and add some text
        $this->canvas = imagecreatetruecolor(800, 600);

        // white background
        $colour = imagecolorallocate($this->canvas, 255, 255, 255);
        imagefill($this->canvas, 0, 0, $colour);

        $filepath = bb_agency_UPLOADPATH . '/' . $this->profile->ProfileGallery . '/' . $this->profile->ProfileMediaURL;

        if (file_exists($filepath)) {
            $headshot = $this->image_resize( $filepath, 350, 500, true );

            if (!empty($headshot))
                imagecopy($this->canvas, $headshot, 50, 50, 0, 0, imagesx($headshot), imagesy($headshot));

            else
                die("Failed to copy profile image $this->profile->ProfileMediaURL to card: ".$headshot);
        }
        
        // get logo
        $logo_option = get_option('cmsms_options_newgate_logo_image');
        $logo_url = $logo_option['newgate_logo_url'];
        $logo_path = str_replace(get_bloginfo('wpurl'), ABSPATH, $logo_url);

        // add logo to canvas
        if (file_exists($logo_path)) {
            $logo_img = $this->image_resize( $logo_path, 250, 90 );

            if (!empty($logo_img))
                imagecopy($this->canvas, $logo_img, $this->text_x, $this->text_y, 0, 0, imagesx($logo_img), imagesy($logo_img));
        }

        $this->text_y += 120;

        $this->text_colour = imagecolorallocate($this->canvas, 0, 0, 0);

        $name = $this->profile->ProfileContactNameFirst;

        $this->fontfile = dirname(dirname(__FILE__)).'/fonts/Raleway-Regular.ttf';

        // get site url
        $url = preg_replace('#^http(s)?://#', '', trim(get_bloginfo('wpurl'), '/'));

        $this->print_text( $url );

        $this->text_y += 30;

        $this->print_text( get_bloginfo('admin_email') );

        $this->text_y += 30;

        $this->print_text( bb_agency_PHONE );

        $this->text_y += 150;

        $this->text_size = 14;

        $this->print_text( $name );

        $this->text_y += 50;

        if (bb_agency_SITETYPE == 'children') {
            $this->print_text( 'Age: ' . $this->get_age( $this->profile->ProfileDateBirth ) );
        } else {
            $this->print_text( 'Due date: ' . $this->get_date( $this->profile->ProfileDateDue ) );
        }

        if ($profile->height) {
            $this->text_y += 50;
            $this->print_text( 'Height: ' . $this->get_age( $this->profile->ProfileDateBirth ) );
        }

        // Write to file image
        $success = imagejpeg($this->canvas, $this->filepath(), $this->quality);

        // Free up memory
        imagedestroy($this->canvas);

        return $success;
    }

    public function display() {

        $path = $this->filepath();

        if (!@file_exists($path)) {
            if (!$this->save())
                die ('failed to save image to '.$path);
        }

        // Set the content type header - in this case image/jpeg
        header('Content-Type: image/jpeg');

        // write image to browser
        echo file_get_contents($path);
    }

    public function filepath() {
        return bb_agency_UPLOADPATH .$this->profile->ProfileGallery.'/'.$this->filename();
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
            SELECT p.*, dt.`DataTypePrivacy` AS ProfilePrivacy, m.`ProfileMediaURL`, h.`ProfileCustomValue` AS height
            FROM $t_profile AS p
            LEFT JOIN $t_datatype AS dt ON dt.`DataTypeID` = p.`ProfileType`
            LEFT JOIN $t_media AS m ON m.`ProfileID` = p.`ProfileID` AND m.`ProfileMediaPrimary` = 1 AND m.`ProfileMediaType` = 'Image'
            LEFT JOIN $t_custom AS h ON h.`ProfileID` = p.`ProfileID` AND h.`ProfileCustomID` = 5
            WHERE p.`ProfileGallery` = '$this->model'
            LIMIT 1";

        $this->profile = $wpdb->get_row($query);
    }

    private function print_text( $string ) {
        imagettftext($this->canvas, $this->text_size, 0, $this->text_x, $this->text_y, $this->text_colour, $this->fontfile, $string);
    }

    private function get_age( $dob ) {
        return bb_agency_get_age($dob);
    }

    private function get_date( $date ) {
        return bb_agency_displaydate($date);
    }

    private function get_height( $height ) {

            $feet = floor($height / 12);
            $inch = $height - floor($feet * 12);

            return $feet.'ft '.$inch.'in';
    }

    private function imagecreatefromfile( $filename ) {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException('File "'.$filename.'" not found.');
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
                throw new InvalidArgumentException('File "'.$filename.'" is not valid jpg, png or gif image.');
            break;
        }
    }

    private function image_resize($src, $width, $height, $crop=0){

        if (!list($w, $h) = getimagesize($src))
            $this->fatal("Unsupported picture type");

        $type = strtolower(substr(strrchr($src,"."),1));
        
        if ($type == 'jpeg') 
            $type = 'jpg';

        switch($type){
            case 'bmp': 
                $img = imagecreatefromwbmp($src); 
                break;
            case 'gif': 
                $img = imagecreatefromgif($src); 
                break;
            case 'jpg': 
                $img = imagecreatefromjpeg($src); 
                break;
            case 'png': 
                $img = imagecreatefrompng($src); 
                break;
            default : 
                $this->fatal("Unsupported picture type");
        }

        // resize
        if($crop){
            if ($w < $width or $h < $height)
                $this->fatal("Picture is too small");
            $ratio = max($width/$w, $height/$h);
            $h = $height / $ratio;
            $x = ($w - $width / $ratio) / 2;
            $w = $width / $ratio;
        }
        else{
            if ($w < $width and $h < $height) 
                $this->fatal("Picture is too small");
            $ratio = min($width/$w, $height/$h);
            $width = $w * $ratio;
            $height = $h * $ratio;
            $x = 0;
        }

        $new = imagecreatetruecolor($width, $height);

        // preserve transparency
        if ($type == "gif" or $type == "png"){
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

        return $new;
    }

    private function fatal($message) {
        die($message);
    }
}