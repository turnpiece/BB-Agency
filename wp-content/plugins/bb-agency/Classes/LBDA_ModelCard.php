<?php

require_once(bb_agency_BASEPATH.'/Classes/ModelCard.php');

class LBDA_ModelCard extends ModelCard {
    
    function __construct($model) {

        parent::__construct($model);
    }

    protected function print_model_details() {

        if ($this->profile->ProfileType == 2) {

            // families
            $names = array();

            if ($this->profile->mum_name)
                $names[] = $this->profile->mum_name;

            if ($this->profile->dad_name)
                $names[] = $this->profile->dad_name;
           
            $this->print_text( implode( ' & ', $names ) );
            
        } else {

            // print first name
            $name = $this->profile->ProfileContactNameFirst;
            $this->print_text( $name );

            $this->text_y += 50;

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
        }

    }

    protected function print_company_details() {
        $this->text_y = 420;

        // print company logo
        $this->print_logo();

        $this->text_x += 125;   

        // print LBDA logo
        $this->print_lbda_logo();

        $this->text_x = 500;
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
            $logo_img = $this->image_resize( $logo_path, 125, 45 );

            if (!empty($logo_img))
                imagecopy($this->canvas, $logo_img, $this->text_x, $this->text_y, 0, 0, imagesx($logo_img), imagesy($logo_img));
        }        
    }

    /**
     *
     * print LBDA logo
     *
     */
    private function print_lbda_logo() {
        // get logo
        $logo_path = dirname( dirname(__FILE__) ) . '/style/logos/LBDA_logo_large.jpg';

        // add logo to canvas
        if (file_exists($logo_path)) {
            $logo_img = $this->image_resize( $logo_path, 125, 45 );

            if (!empty($logo_img))
                imagecopy($this->canvas, $logo_img, $this->text_x, $this->text_y, 0, 0, imagesx($logo_img), imagesy($logo_img));
        } 
        else
            $this->fatal( "Failed to find LBDA logo" );  
    }

    /**
     * filename
     *
     */
    protected function filename() {
        return 'LBDA-' . str_replace(' ', '-', get_bloginfo('name')) .'-'.$this->profile->ProfileContactNameFirst.'.jpg';
    }
}