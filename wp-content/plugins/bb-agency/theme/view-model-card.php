<?php

// check login
if (!is_user_logged_in())
    exit;

// Get Profile
$profileURL = get_query_var('target');

global $wpdb;
$t_profile = table_agency_profile;
$t_datatype = table_agency_data_type;
$t_media = table_agency_profile_media;
$query = <<<EOF
SELECT p.*, dt.`DataTypePrivacy` AS ProfilePrivacy, m.`ProfileMediaURL` 
FROM $t_profile AS p
LEFT JOIN $t_datatype AS dt ON dt.`DataTypeID` = p.`ProfileType`
LEFT JOIN $t_media AS m ON m.`ProfileID` = p.`ProfileID` AND m.`ProfileMediaPrimary` = 1 AND m.`ProfileMediaType` = "Image"
WHERE p.`ProfileGallery` = '$profileURL'
LIMIT 1
EOF;

$profile = $wpdb->get_row($query);

// Create a blank image and add some text
$canvas = imagecreatetruecolor(800, 600);

// white background
$colour = imagecolorallocate($canvas, 255, 255, 255);
imagefill($canvas, 0, 0, $colour);

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


imagecopy($canvas, $tmpimg, 50, 50, 0, 0, $newwidth, $newheight);

$text_color = imagecolorallocate($canvas, 0, 0, 0);

$name = $profile->ProfileContactNameFirst;
/*
if ($profile->ProfileContactNameLast) {
    $name .= ' '.$profile->ProfileContactNameLast;
}
*/

$x = 550;
$y = 50;

imagestring($canvas, 5, $x, $y, $name, $text_color);

$y += 50;

/*
$address = array(
    $profile->ProfileLocationStreet,
    $profile->ProfileLocationCity,
    $profile->ProfileLocationState,
    $profile->ProfileLocationZip
);
foreach ($address as $row) {
    if ($row) {
        imagestring($canvas, 5, 550, $y, $row, $text_color);
        $y += 30;
    }
}
*/
imagestring($canvas, 5, $x, $y, 'Date of birth: '.$profile->ProfileDateBirth, $text_color);

// Set the content type header - in this case image/jpeg
header('Content-Type: image/jpeg');

// Output the image
imagejpeg($canvas);

// Free up memory
imagedestroy($canvas);
