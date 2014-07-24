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

        const V_SPACE = 5;

        const BB_URL = 'www.beautifulbumpsagency.co.uk';
        const KW_URL = 'www.kiddiwinksagency.co.uk';

        const PAGE_HEIGHT = 279.4;
        const BOTTOM_MARGIN = 20;

        const CURRENCY = '£';

        const DEBUGGING = true;

        private $invoice = array();
        private $cur_y;

        function setData($invoice) {
            $this->invoice = $invoice;
        }

        function Header() {
            $this->debug(__FUNCTION__);
            $this->debug('x = '.$this->GetX().', y = '.$this->GetY());

            // images
            $bb = bb_agency_BASEDIR.'style/logos/bbumps.jpg';
            $kw = bb_agency_BASEDIR.'style/logos/kiddiwinks.png';

            $this->Image($bb, self::LEFT, 10, 35, null, null, 'http://'.self::BB_URL);
            $this->Image($kw, self::RIGHT, 15, 50, null, null, 'http://'.self::KW_URL);

            //$this->SetY(50);

            $this->Ln(30);

            $this->SetX(self::RIGHT);
            $this->SetTextColor(204, 102, 102);
            $this->standard_font(true);
            $this->MultiCell(
                self::HALF_W, 
                8, 
                'INVOICE NO: ' . $this->ficonv('InvoiceNumber') . 'Please quote in all payments'
            );
            $this->SetTextColor(0, 0, 0);

            $this->v_space(3);

            $this->small_font(true);

            $cur_y = $this->GetY();

            $this->SetX(self::LEFT);
            $this->Cell(30, self::V_SPACE, 'Accounts e-mail');
            $this->SetX(self::LEFT + 30);
            $this->Cell(30, self::V_SPACE, 'zandra@beautifulbumpsagency.co.uk');

            $this->v_space();

            $this->SetX(self::LEFT);
            $this->Cell(30, self::V_SPACE, 'Accounts e-mail');
            $this->SetX(self::LEFT + 30);
            $this->Cell(30, self::V_SPACE, 'zandra@kiddiwinksagency.co.uk');

            $this->v_space(2);
            
            $this->small_font();
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

            $address = $this->ficonv('ProfileContactDisplay')."\r\n";
            foreach (array(
                'ProfileLocationStreet', 
                'ProfileLocationCity', 
                'ProfileLocationState', 
                'ProfileLocationZip', 
                'ProfileContactPhoneWork', 
                'ProfileContactEmail') as $field)
                $address .= $this->ficonv($field);

            $this->SetXY(self::LEFT, $cur_y);
            $this->MultiCell(self::HALF_W, self::V_SPACE, "Invoice to:-\r\n\r\n$address");

            $this->v_space();

            $this->SetXY(self::RIGHT, $cur_y);
            $this->MultiCell(50, self::V_SPACE, $this->ficonv('InvoiceDate'));

            $col_width = self::HALF_W;
            $this->debug("column width = $col_width");

            $this->v_space(10);

            $this->SetX(self::LEFT);
            $this->standard_font(true);
            $this->Cell(50, self::V_SPACE, $this->iconv('Services'));

            $this->v_space(3);
            $this->standard_font();  
            
            $r = 0;
            foreach ($this->invoice['rows'] as $row) :
                
                $row_height = 0;

                $total_string_width = $this->GetStringWidth($row[0]);
                $number_of_lines = ceil($total_string_width / ($col_width + 5));

                $line_height = self::V_SPACE;
                $height_of_cell = ceil($number_of_lines * $line_height) + ($line_height * 2); 

                if ($height_of_cell > $row_height)
                    $row_height = $height_of_cell;

                $space_left = self::PAGE_HEIGHT - $this->GetY() - self::BOTTOM_MARGIN;

                if ($row_height >= $space_left) {   
                    // start a new page
                    $this->AddPage();
                    $this->SetXY(self::LEFT, $cur_y + self::V_SPACE);
                }

                // description
                $this->SetX(self::LEFT);
                $this->MultiCell($col_width, self::V_SPACE, $row[0]);

                // price
                $this->SetX(self::RIGHT);
                $this->Cell(30, self::V_SPACE, $this->price($row[1]), 0, 0, 'R');
                
                $this->Ln($row_height);
                $cur_y = $this->GetY(); 

            endforeach;
            
            $this->SetY($cur_y + self::V_SPACE);

            $this->standard_font(true);
            $this->SetX(self::LEFT);
            $this->Cell($col_width, self::V_SPACE, 'TOTAL INVOICE INCLUDING AGENCY FEES');

            $this->SetX(self::RIGHT);
            $this->Cell(30, self::V_SPACE, $this->price($this->invoice['InvoiceTotal']), 0, 0, 'R');

            $this->v_space(8);
            $this->SetX(self::LEFT);
            $this->SetY($cur_y + self::V_SPACE * 8);    
        }

        function Footer() {
            $this->debug(__FUNCTION__);
            $this->debug('x = '.$this->GetX().', y = '.$this->GetY());

            $this->small_font();

            $this->SetX(self::LEFT);
            $this->Cell(self::HALF_W, 5, $this->iconv('Payment terms are 30 days from invoice date'));
            $this->Ln(8);
            $this->SetX(self::LEFT);
            $this->Cell(self::HALF_W, 5, $this->iconv('Kiddiwinks is a Beautiful Bumps Ltd company'));
            $this->v_space();
            $this->SetX(self::LEFT);
            $this->Cell(self::HALF_W, 5, $this->iconv('134 Ridge Langley, South Croydon, Surrey, CR2 0AS.'));

            $this->SetX(self::HALF_RIGHT);
            $this->Cell(self::HALF_W, 5, $this->iconv('Beautiful Bumps Ltd Registration No. 06320457'));
        }

        function price($amount) {
            return $this->iconv(self::CURRENCY.$amount);
        }

        function iconv($text, $append = "\r\n") {
            return iconv('UTF-8', 'windows-1252', $text).$append;
        }

        function ficonv($field, $append = "\r\n") {
            if (!empty($this->invoice[$field]) && !is_null($this->invoice[$field]) && $this->invoice[$field] != '')
                return $this->iconv($this->invoice[$field], $append);
        }

        function standard_font($bold = false) {
            $this->SetFont(self::FONT, $bold ? 'B' : '', self::FONT_SIZE_MAIN);
        }

        function small_font($bold = false) {
            $this->SetFont(self::FONT2, $bold ? 'B' : '', self::FONT_SIZE_SMALL);
        }

        function v_space($number = 1) {
            $this->Ln(self::V_SPACE * $number);
        }

        function save() {
            if (!$this->invoice['FileName'])
                die ('no file name given');

            $pdf_path = bb_agency_BASEPATH.'invoices/'.$this->invoice['FileName'].'.pdf';
            $this->Output($pdf_path, "F");
        }

        function debug($message) {
            if (self::DEBUGGING)
                error_log($message);
        }
    }

    $pdf = new LocalPDF('P','mm','A4');
    $pdf->setData($invoice);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(1, 20);
    $pdf->AddPage();
    $pdf->InvoiceBody();
    $pdf->save();
?>