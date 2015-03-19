<?php

class ModelCard {

    private $canvas;
    private $fontfile;
    private $text_colour;
    private $text_x = 550;
    private $text_y = 50;
    private $text_size = 15;
    
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
        $size = getimagesize($filepath);

        $headshot = imagecreatefromjpeg($filepath);

        $width = imagesx( $headshot );
        $height = imagesy( $headshot );

        $p_width = 400;
        $p_height = 500;

        if ($width > $height) {
            $newwidth = $p_width;
            $divisor = $width / $p_width;
            $newheight = floor( $height / $divisor);
        }
        else {
            $newheight = $p_height;
            $divisor = $height / $p_height;
            $newwidth = floor( $width / $divisor );
        }

        // Create a new temporary image.
        $tmpimg = imagecreatetruecolor( $newwidth, $newheight );

        // Copy and resize old image into new image.
        imagecopyresampled( $tmpimg, $headshot, 0, 0, 0, 0, $newwidth, $newheight, $width, $height );

        imagecopy($this->canvas, $tmpimg, 50, 50, 0, 0, $newwidth, $newheight);

        $this->text_colour = imagecolorallocate($this->canvas, 0, 0, 0);

        $name = $profile->ProfileContactNameFirst;

        $this->fontfile = dirname(dirname(__FILE__)).'/fonts/Raleway-Regular.ttf';

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
        return $interval->y;
    }
}