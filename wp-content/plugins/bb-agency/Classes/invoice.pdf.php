<?php
    class LocalPDF extends FPDF {

        const LEFT = 15;
        const RIGHT = 145;
        const HALF_RIGHT = 110;

        const PAGE_WIDTH = 210;
        const HALF_W = 85;

        const FONT = 'Times';
        const FONT2 = 'Arial';
        const FONT_SIZE_MAIN = 12;
        const FONT_SIZE_SMALL = 8;
        const FONT_SIZE_LARGE = 16;

        const V_SPACE = 5;

        const BB_URL = 'www.beautifulbumpsagency.co.uk';
        const KW_URL = 'www.kiddiwinksagency.co.uk';

        const PAGE_HEIGHT = 279.4;
        const BOTTOM_MARGIN = 20;

        const CURRENCY = '£';

        const DEBUGGING = true;

        private $invoice = array();
        private $cur_y;
        private $accounts_email;

        function setData($invoice) {
            $this->invoice = $invoice;
            $this->site_type = bb_agency_SITETYPE;
            $this->accounts_email = bb_agency_accounts_email();
        }

        function Header() {
            $this->debug(__FUNCTION__);
            $this->debug('x = '.$this->GetX().', y = '.$this->GetY());

            // images
            $bb = bb_agency_BASEDIR.'style/logos/bbumps.jpg';
            $kw = bb_agency_BASEDIR.'style/logos/kiddiwinks.png';

            $this->Image($bb, self::LEFT, 10, 35, null, null, 'http://'.self::BB_URL);
            $this->Image($kw, self::RIGHT, 15, 50, null, null, 'http://'.self::KW_URL);

            $this->v_space(6);

            $this->link_font();

            $this->SetX(self::LEFT);
            $this->bb_colour();
            $this->Write(5, self::BB_URL, 'http://'.self::BB_URL);

            $this->SetX(self::RIGHT + 5);
            $this->kw_colour();
            $this->Write(5, self::KW_URL, 'http://'.self::KW_URL);

            $this->v_space(2);

            $this->SetX(self::RIGHT);
            $this->SetTextColor(204, 102, 102);
            $this->large_font();
            $this->MultiCell(
                40, 
                self::V_SPACE, 
                'INVOICE NO: ' . $this->ficonv('InvoiceNumber'),
                0,
                'L'
            );
            $this->small_font(true);
            $this->SetX(self::RIGHT);
            $this->Write(self::V_SPACE, 'Please quote in all payments');
            $this->standard_colour();

            $this->v_space(3);

            $cur_y = $this->GetY();

            $this->small_font(true);
            $this->SetX(self::LEFT);
            $this->Cell(25, self::V_SPACE, 'Accounts e-mail');

            $this->link_font();
            $this->bb_colour();
            $this->SetX(self::LEFT + 26);
            $this->Write(self::V_SPACE, $this->accounts_email, 'mailto:zandra@'.$this->accounts_email.'?subject=Invoice '.$this->invoice['InvoiceNumber']);

            $this->v_space(2);
            
            $this->small_font();
            $this->standard_colour();
            $this->SetX(self::LEFT);
            $this->Cell(40, self::V_SPACE, 'Phone: 0208 651 1201');

            //$this->v_space();

            $this->small_font(true);
            
            $payment = '';
            foreach ($this->invoice['InvoicePayment'] as $key => $value) {
                $payment .= $this->iconv("$key: $value");
            }
            $this->SetXY(self::RIGHT, $cur_y);
            $this->MultiCell(40, self::V_SPACE, $payment);

            $this->v_space();

            // draw a line
            $this->SetDrawColor(0, 0, 0);
            $this->Line(self::LEFT, $this->GetY(), self::PAGE_WIDTH - self::LEFT, $this->GetY());
            $this->v_space();
            
            $this->cur_y = $this->GetY();
        }

        function InvoiceBody() {

            $this->debug(__FUNCTION__);

            if (empty($this->invoice['rows']))
                return false;

            $cur_y = $this->cur_y;

            $this->standard_font();

            $address = $this->ficonv('ProfileContactDisplay');
            foreach (array(
                'ProfileLocationStreet', 
                'ProfileLocationCity', 
                'ProfileLocationState', 
                'ProfileLocationZip') as $field)
                $address .= $this->ficonv($field);

            $this->SetXY(self::LEFT, $cur_y);
            $this->MultiCell(self::HALF_W, self::V_SPACE, "Invoice to:-\r\n\r\n$address");

            $this->v_space();

            $this->SetXY(self::RIGHT, $cur_y);
            $this->MultiCell(50, self::V_SPACE, $this->ficonv('InvoiceDate'));

            $col_width = self::HALF_W;
            $col_width_r = self::PAGE_WIDTH - self::RIGHT - self::LEFT;
            $this->debug("column width = $col_width");

            $this->v_space(9);

            $this->SetX(self::LEFT);
            $this->standard_font(true);
            $this->Cell(50, self::V_SPACE, $this->iconv('SERVICES'));

            $this->v_space(2);
            $this->standard_font();  

            foreach ($this->invoice['rows'] as $row) :

                $cur_y = $this->GetY();
                
                $row_height = $this->GetMultiCellHeight($col_width, self::V_SPACE, $row[0]);

                $space_left = self::PAGE_HEIGHT - $this->GetY() - self::BOTTOM_MARGIN;

                $this->debug("row height = $row_height");

                if ($row_height >= $space_left) {   
                    // start a new page
                    $this->AddPage();
                    $this->SetXY(self::LEFT, $cur_y + self::V_SPACE);
                }

                // description
                $this->SetXY(self::LEFT, $cur_y);
                $this->MultiCell($col_width, self::V_SPACE, $row[0]);   

                // price
                $this->SetXY(self::RIGHT, $cur_y);
                $this->Cell($col_width_r, self::V_SPACE, $this->price($row[1]), 0, 0, 'R');
                
                $this->Ln($row_height);
                $this->v_space();

            endforeach;

            $this->standard_font(true);
            $this->SetX(self::LEFT);
            $this->Cell($col_width, self::V_SPACE, 'TOTAL INVOICE VALUE INCLUDING AGENCY FEE');

            $this->SetX(self::RIGHT);
            $this->Cell($col_width_r, self::V_SPACE, $this->price($this->invoice['InvoiceTotal']), 0, 0, 'R');

            $this->v_space(8);
            $this->SetX(self::LEFT);
            $this->SetY($cur_y + self::V_SPACE * 8);    
        }

        function Footer() {
            $this->debug(__FUNCTION__);

            if ($this->GetY() > 250) {
                $this->AddPage();
            }

            $this->small_font();

            $this->SetY(250);

            $this->SetX(self::LEFT);
            $this->Cell(self::HALF_W, 5, $this->iconv('Payment terms are 30 days from invoice date'));

            $this->v_space(2);
            if ($this->site_type == 'children') {
                $this->SetX(self::LEFT);
                $this->Cell(self::HALF_W, 5, $this->iconv('Kiddiwinks is a Beautiful Bumps Ltd. company'));         
            } else {
                $this->v_space();
            }
            $this->v_space();
            $this->SetX(self::LEFT);
            $this->Cell(self::HALF_W, 5, $this->iconv('134 Ridge Langley, South Croydon, Surrey, CR2 0AS'));

            $this->SetX(self::HALF_RIGHT);
            $this->Cell(self::HALF_W, 5, $this->iconv('Beautiful Bumps Ltd. Registration No. 06320457'), 0, 0, 'R');
        }

        function GetMultiCellHeight($w, $h, $txt, $border=null, $align='J') {
            // Calculate MultiCell with automatic or explicit line breaks height
            // $border is un-used, but I kept it in the parameters to keep the call
            //   to this function consistent with MultiCell()
            $cw = &$this->CurrentFont['cw'];
            if($w==0)
                $w = $this->w-$this->rMargin-$this->x;
            $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
            $s = str_replace("\r",'',$txt);
            $nb = strlen($s);
            if($nb>0 && $s[$nb-1]=="\n")
                $nb--;
            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $ns = 0;
            $height = 0;
            while($i<$nb)
            {
                // Get next character
                $c = $s[$i];
                if($c=="\n")
                {
                    // Explicit line break
                    if($this->ws>0)
                    {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    //Increase Height
                    $height += $h;
                    $i++;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $ns = 0;
                    continue;
                }
                if($c==' ')
                {
                    $sep = $i;
                    $ls = $l;
                    $ns++;
                }
                $l += $cw[$c];
                if($l>$wmax)
                {
                    // Automatic line break
                    if($sep==-1)
                    {
                        if($i==$j)
                            $i++;
                        if($this->ws>0)
                        {
                            $this->ws = 0;
                            $this->_out('0 Tw');
                        }
                        //Increase Height
                        $height += $h;
                    }
                    else
                    {
                        if($align=='J')
                        {
                            $this->ws = ($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                            $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                        }
                        //Increase Height
                        $height += $h;
                        $i = $sep+1;
                    }
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $ns = 0;
                }
                else
                    $i++;
            }
            // Last chunk
            if($this->ws>0)
            {
                $this->ws = 0;
                $this->_out('0 Tw');
            }
            //Increase Height
            $height += $h;

            return $height;
        }

        /**
         * display a price
         *
         * @param $amount
         * @return string
         *
         */
        function price($amount) {
            return $this->iconv(self::CURRENCY . number_format($amount, 2));
        }

        function iconv($text, $append = "\r\n") {
            return iconv('UTF-8', 'windows-1252', $text).$append;
        }

        function ficonv($field, $append = "\r\n") {
            if (!empty($this->invoice[$field]) && !is_null($this->invoice[$field]) && $this->invoice[$field] != '')
                return $this->iconv($this->invoice[$field], $append);
        }

        function large_font() {
            $this->SetFont(self::FONT2, 'B', self::FONT_SIZE_LARGE);
        }

        function standard_font($bold = false) {
            $this->SetFont(self::FONT, $bold ? 'B' : '', self::FONT_SIZE_MAIN);
        }

        function small_font($bold = false) {
            $this->SetFont(self::FONT2, $bold ? 'B' : '', self::FONT_SIZE_SMALL);
        }

        function link_font() {
            $this->SetFont(self::FONT2, 'U', self::FONT_SIZE_SMALL);
        }

        function v_space($number = 1) {
            $this->Ln(self::V_SPACE * $number);
        }

        function kw_colour() {
            $this->SetTextColor(204, 204, 153);
        }

        function bb_colour() {
            $this->SetTextColor(255, 153, 153);
        }

        function standard_colour() {
            $this->SetTextColor(0, 0, 0);
        }

        function save() {
            if (!$this->invoice['FileName'])
                wp_die('ERROR: Unable to save invoice as no file name was given');

            $pdf_path = $this->invoice_path();
            $this->Output($pdf_path, "F");
        }

        function invoice_path() {
            return bb_agency_BASEPATH.'invoices/'.$this->invoice['FileName'].'.pdf';
        }

        function debug($message) {
            if (self::DEBUGGING)
                error_log($message);
        }
    }

    if (empty($Invoice))
        wp_die('ERROR: invoice array empty!');

    $pdf = new LocalPDF('P','mm','A4');
    $pdf->setData($Invoice);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(1, 20);
    $pdf->AddPage();
    $pdf->InvoiceBody();
    $pdf->save();
?>