<?php

class ModelCard {

    private $canvas;
    private $fontfile;
    private $text_colour;
    private $text_x = 500;
    private $text_y = 50;
    private $text_size = 11;
    
    function __construct() {

    }

    function display( $model ) {

        global $wpdb;
        $t_profile = table_agency_profile;
        $t_datatype = table_agency_data_type;
        $t_media = table_agency_profile_media;
        $query = <<<EOF
SELECT p.*, dt.`DataTypePrivacy` AS ProfilePrivacy, m.`ProfileMediaURL` 
FROM $t_profile AS p
LEFT JOIN $t_datatype AS dt ON dt.`DataTypeID` = p.`ProfileType`
LEFT JOIN $t_media AS m ON m.`ProfileID` = p.`ProfileID` AND m.`ProfileMediaPrimary` = 1 AND m.`ProfileMediaType` = "Image"
WHERE p.`ProfileGallery` = '$model'
LIMIT 1
EOF;

        $profile = $wpdb->get_row($query);

        // Create a blank image and add some text
        $this->canvas = imagecreatetruecolor(800, 600);

        // white background
        $colour = imagecolorallocate($this->canvas, 255, 255, 255);
        imagefill($this->canvas, 0, 0, $colour);

        $filepath = bb_agency_UPLOADPATH . '/' . $profile->ProfileGallery . '/' . $profile->ProfileMediaURL;

        if (file_exists($filepath)) {
            $headshot = $this->image_resize( $filepath, 350, 500, true );

            if (!empty($headshot))
                imagecopy($this->canvas, $headshot, 50, 50, 0, 0, imagesx($headshot), imagesy($headshot));

            else
                die("Failed to copy profile image $profile->ProfileMediaURL to card: ".$headshot);
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

        $name = $profile->ProfileContactNameFirst;

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

        $this->print_text( 'Age: ' . $this->get_age( $profile->ProfileDateBirth ) );

        // Set the content type header - in this case image/jpeg
        header('Content-Type: image/jpeg');

        // Output the image
        imagejpeg($this->canvas);

        // Free up memory
        imagedestroy($this->canvas);
    }

    private function print_text( $string ) {
        imagettftext($this->canvas, $this->text_size, 0, $this->text_x, $this->text_y, $this->text_colour, $this->fontfile, $string);
    }

    private function get_age( $dob ) {
        $birthday = new DateTime($dob);
        $interval = $birthday->diff(new DateTime);
        if ($interval->y > 0)
            return $interval->y;
        else
            return $interval->m . ' months';
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