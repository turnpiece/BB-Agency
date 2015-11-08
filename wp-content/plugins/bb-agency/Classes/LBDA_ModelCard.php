<?php

require_once(bb_agency_BASEPATH.'/Classes/ModelCard.php');

class LBDA_ModelCard extends ModelCard {
    
    function __construct($model) {

        parent::__construct($model);
    }

    protected function print_model_details() {

        $this->debug(__FUNCTION__);

        if ($this->profile['ProfileType'] == 2) {

            // families
            $names = array();

            if ($this->profile['mum_name'])
                $names[] = $this->profile['mum_name'];

            if ($this->profile['dad_name'])
                $names[] = $this->profile['dad_name'];
           
            $this->print_text( implode( ' & ', $names ) );
            
        } else {

            // print first name
            $name = $this->profile['ProfileContactNameFirst'];
            $this->print_text( $name );

            $this->text_y += 50;

            // children
            if ($age = $this->get_age()) {
                $this->print_text( 'Age: ' . $age );
                $this->text_y += 50;
            }

            if ($this->profile['height']) {
                $this->print_text( 'Height: ' . $this->get_height() );
                $this->text_y += 50;
            }

            if ($talent = $this->get_talent()) {
                $this->print_text( 'Talent: ' . $talent );
                $this->text_y += 50;

                if ($genre = $this->get_genre()) {
                    $this->print_text( 'Genre: ' . $genre );
                    $this->text_y += 50;
                }

                if ($ability = $this->get_ability()) {
                    $this->print_text( 'Ability: ' . $ability );
                    $this->text_y += 50;
                }
            }

        }

    }

    protected function print_company_details() {
        $this->text_y = 380;

        // print company logo
        $this->print_logo();

        $this->text_x += 125;   

        // print LBDA logo
        $this->print_lbda_logo();

        $this->text_x = 500;
        $this->text_y += 100;

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
        return 'LBDA-' . str_replace(' ', '-', get_bloginfo('name')) .'-'.$this->profile['ProfileContactNameFirst'].'.jpg';
    }

    /**
     *
     * get talent
     *
     */
    public function get_talent() {

        $value = $this->get_profile_field('ProfileTalent');

        $this->debug(__FUNCTION__ . ' => ' . $value);

        $values = explode( ',', $value );
        $talent = bb_agency_get_talents();

        //$this->debug( print_r($talent, true) );

        $return = array();

        if (!empty($values)) {
            foreach ( $values as $v ) {
                $return[] = $talent[$v];
                $this->debug( "talent $t => " . $talent[$v] );
            }

            return implode( ', ', $return );
        }
    }

    /**
     *
     * get genre
     *
     */
    private function get_genre() {

        if ($this->profile['ProfileGenre']) {
            $genres = explode( ',', $this->profile['ProfileGenre'] );
            $genre = bb_agency_get_genres();

            $return = array();

            foreach ( $genres as $g ) {
                $return[] = $genre[$g];
                $this->debug( "genre $g => " . $genre[$g] );
            }

            return implode( ', ', $return );
        }
    }

    /**
     *
     * get ability
     *
     */
    private function get_ability() {

        if ($this->profile['ProfileAbility']) {
            $abilities = explode( ',', $this->profile['ProfileAbility'] );
            $ability = bb_agency_get_abilities();

            $return = array();

            foreach ( $abilities as $a ) {
                $return[] = $ability[$a];
                $this->debug( "ability $a => " . $ability[$a] );
            }

            return implode( ', ', $return );
        }
    }

}