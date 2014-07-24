<?php
    class LocalPDF extends FPDF {

        const LEFT = 15;
        const RIGHT = 145;
        const HALF_RIGHT = 110;

        const HALF_W = 85;

        const FONT = 'Arial';
        const FONT_SIZE_BODY = 12;

        const BB_URL = 'www.beautifulbumpsagency.co.uk';
        const KW_URL = 'www.kiddiwinksagency.co.uk';

        const PAGE_HEIGHT = 279.4;
        const BOTTOM_MARGIN = 20;

        const CURRENCY = '£';

        const DEBUGGING = true;

        public $invoice = array();
        public $columns = array();
        public $rows = array();

        function setData($invoice, $columns, $rows) {
            $this->invoice = $invoice;
            $this->columns = $columns;
            $this->rows = $rows;
        }

        function Header() {
            $this->debug(__FUNCTION__);
            $this->debug('x = '.$this->GetX().', y = '.$this->GetY());

            // images
            $bb = bb_agency_BASEDIR.'style/logos/bbumps.jpg';
            $kw = bb_agency_BASEDIR.'style/logos/kiddiwinks.png';

            //$this->SetX(15);
            $this->Image($bb, self::LEFT, 10, 35, null, null, 'http://'.self::BB_URL);
            $this->Image($kw, self::RIGHT, 15, 50, null, null, 'http://'.self::KW_URL);

            $this->SetY(30);

            $this->Ln(4);

            $this->SetX(self::RIGHT);
            $this->SetTextColor(204, 102, 102);
            $this->standard_font(true);
            $this->MultiCell(
                self::HALF_W, 
                8, 
                $this->iconv('INVOICE NO.:') . $this->ficonv('InvoiceNumber') . $this->iconv('Please quote in all payments')
            );
            $this->SetTextColor(0, 0, 0);

            $this->Ln(15);

            $this->SetX(self::LEFT);
            $address = $this->ficonv('ProfileContactDisplay')."\r\n";
            foreach (array(
                'ProfileLocationStreet', 
                'ProfileLocationCity', 
                'ProfileLocationState', 
                'ProfileLocationZip', 
                'ProfileContactPhoneWork', 
                'ProfileContactEmail') as $field)
                $address .= $this->ficonv($field);

            $this->MultiCell(self::HALF_W, 8, $address);

            $this->SetX(self::RIGHT);
            $this->SetFont(self::FONT, 'B', 9);
            $payment = '';
            foreach ($this->invoice['InvoicePayment'] as $key => $value) {
                $payment .= $this->iconv("$key: $value");
            }
            $this->MultiCell(self::HALF_W, 4, $payment);
            $this->Ln(6);

            // draw a line
            $this->SetDrawColor(0, 0, 0);
            $this->Rect(10, $this->GetY(), 190, 0);
            $this->Ln(6);

            $this->standard_font();
            $this->SetX(self::RIGHT);
            $this->MultiCell(self::HALF_W, 4, $this->ficonv('InvoiceDate'));
            $this->Ln(6);
            
            $this->cur_y = $this->GetY();
        }

        function InvoiceBody() {

            $this->debug(__FUNCTION__);

            $cur_y = $this->cur_y;
            $this->SetX(self::LEFT);
            
            $this->SetY($cur_y + 10);
            $this->debug('x = '.$this->GetX().', y = '.$this->GetY());

            $col_width = 180 / count($this->columns);
            $this->debug("column width = $col_width");

            $this->Ln(5);
            $this->standard_font(true);
            $this->Cell(self::HALF_W, 5, $this->iconv('Services'));

            $this->Ln(12);
            $this->standard_font();    
            
            $r = 0;
            foreach ($this->rows as $row) :
                
                $row_height = 0;
                for ($c = 0; $c < count($this->columns); $c++) :
                    $content = $row[$this->columns[$c]];

                    $total_string_width = $this->GetStringWidth($content);
                    $number_of_lines = ceil( $total_string_width / ($col_width + 5) );

                    $line_height = 5;
                    $height_of_cell = ceil( $number_of_lines * $line_height ) + ($line_height * 2); 

                    if ($height_of_cell > $row_height)
                        $row_height = $height_of_cell;
                endfor;

                $space_left = self::PAGE_HEIGHT - $this->GetY();
                $space_left -= self::BOTTOM_MARGIN;

                if ($row_height >= $space_left) {   
                    // start a new page
                    $this->AddPage();
                    $this->SetX(self::LEFT);
                    $this->SetY($this->cur_y + 7);

                    $this->Ln(5);
                    //$this->SetFillColor(255,255,255);
                    $this->SetX(self::LEFT);
                    //$this->SetFont(self::FONT, '', 12);
                }

                for ($c=0; $c < count($this->columns); $c++) :
                    $align = 'L';
              
                    if (strtolower($this->columns[$c]) == 'price') {
                        $align = 'R';
                        $row[$this->columns[$c]] = $this->price($row[$this->columns[$c]]);
                    }
                    
                    $row_y = $this->GetY();
                    $row_x = $this->GetX();
                    //$this->Rect($row_x, $row_y, $col_width, $row_height, "FD");
                    $this->MultiCell($col_width, 5, $row[$this->columns[$c]], 0, $align);
                    $this->SetY($row_y);
                    $this->SetX($row_x + $col_width);
                    
                endfor;
                $this->Ln($row_height);
                $cur_y = $this->GetY(); 

                $r++;
            endforeach;
            
            $this->SetY($cur_y + 7);

            $this->standard_font(true);
            for ($c=0; $c<count($this->columns); $c++) :
                if ($c == count($this->columns)-2) :
                    $this->Cell($col_width, 5, 'TOTAL INVOICE INCLUDING AGENCY FEES', 0, 0, 'R', 0);
                elseif ($c == count($this->columns) - 1) :
                    $this->Cell($col_width, 5, $this->price($this->invoice['InvoiceTotal']), 0, 0, 'R', 0);
                else :
                    $this->Cell($col_width, 5, '', 0, 0, 'C', 0);
                endif;
            endfor;

            $this->Ln(40);
            $this->SetX(self::LEFT);
            $this->SetY($cur_y + 40);    
   
        }

        function Footer() {
            $this->debug(__FUNCTION__);
            $this->debug('x = '.$this->GetX().', y = '.$this->GetY());

            $this->SetFont(self::FONT, '', 9);

            $this->SetX(self::LEFT);
            $this->Cell(self::HALF_W, 5, $this->iconv('Payment terms are 30 days from invoice date'));
            $this->Ln(8);
            $this->SetX(self::LEFT);
            $this->Cell(self::HALF_W, 5, $this->iconv('Kiddiwinks is a Beautiful Bumps Ltd company'));
            $this->Ln(4);
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
            if (!empty($this->invoice[$field]) && !is_null($this->invoice[$field]))
                return $this->iconv($this->invoice[$field], $append);
        }

        function standard_font($bold = false) {
            $this->SetFont(self::FONT, $bold ? 'B' : '', self::FONT_SIZE_BODY);
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
    $pdf->setData($invoice, $columns, $rows);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(1, 20);
    $pdf->AddPage();
    $pdf->InvoiceBody();
    $pdf->save();
?>