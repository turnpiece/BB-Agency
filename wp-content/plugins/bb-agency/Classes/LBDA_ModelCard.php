<?php

require_once(bb_agency_BASEPATH.'/Classes/ModelCard.php');

class LBDA_ModelCard extends ModelCard {
    
    function __construct() {

        parent::__construct();
    }

    protected function print_company_details() {
        $this->text_y = 360;

        // print LBDA logo
        $this->print_lbda_logo();

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

    /**
     *
     * print LBDA logo
     *
     */
    private function print_lbda_logo() {
        // get logo
        $logo_path = dirname( dirname(__FILE__) ) . '/style/logos/LBDA-logo-large.jpg';

        // add logo to canvas
        if (file_exists($logo_path)) {
            $logo_img = $this->image_resize( $logo_path, 250, 70 );

            if (!empty($logo_img))
                imagecopy($this->canvas, $logo_img, $this->text_x, $this->text_y, 0, 0, imagesx($logo_img), imagesy($logo_img));
        }   
    }

    /**
     * filename
     *
     */
    protected function filename() {
        return 'LBDA-' . str_replace(' ', '-', get_bloginfo('name').'-'.$this->profile->ProfileContactNameFirst.'.jpg';
    }
}