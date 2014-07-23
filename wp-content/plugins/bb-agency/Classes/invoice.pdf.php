<?php
    class LocalPDF extends FPDF {

        const LEFT = 5;
        const RIGHT = 125;

        const BB_URL = 'www.beautifulbumpsagency.co.uk';
        const KW_URL = 'www.kiddiwinksagency.co.uk';

        const PAGE_HEIGHT = 279.4;
        const BOTTOM_MARGIN = 20;

        const CURRENCY = '£';

        function setData($invoice, $columns, $rows) {
            $this->invoice = $invoice;
            $this->columns = $columns;
            $this->rows = $rows;
        }

        function Header() {
            $cell_width = 95;

            // images
            $bb = bb_agency_BASEDIR.'style/logos/bbumps.jpg';
            $kw = bb_agency_BASEDIR.'style/logos/kiddiwinks.png';

            //$this->SetX(15);
            $this->Image($bb, self::LEFT, 10, 35, null, null, 'http://'.self::BB_URL);
            $this->Image($kw, 145, 15, 50, null, null, 'http://'.self::KW_URL);

            $this->SetY(30);

            $this->Ln(4);

            $this->SetX(self::RIGHT);
            $this->SetTextColor(204, 102, 102);
            $this->SetFont('Arial', 'B', 12);
            $this->MultiCell(
                $cell_width, 
                8, 
                $this->iconv('INVOICE NO.:') . $this->ficonv('InvoiceNumber') . $this->iconv('Please quote in all payments')
            );

            //$this->Cell(self::RIGHT, 8, $this->ficonv('CompanyName'), 0, 0, 'L');

            $this->Ln(15);
            $this->SetX(self::LEFT);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Arial', '', 12);
            $this->MultiCell($cell_width, 7, $this->iconv('INVOICE TO:-'));
            $this->Ln(6);

            //$this->SetFont('Arial', '', 12);

            $address = $this->ficonv('ProfileContactDisplay')."\r\n";
            foreach (array('ProfileLocationStreet', 'ProfileLocationCity', 'ProfileLocationState', 'ProfileLocationZip', 'ProfileContactPhoneWork', 'ProfileContactEmail') as $field)
                $address .= $this->ficonv($field);

            $this->MultiCell($cell_width, 8, $address);

            $this->SetX(self::RIGHT);
            $this->SetFont('Arial', 'B', 9);
            $payment = '';
            foreach ($this->invoice['InvoicePayment'] as $key => $value) {
                $payment .= $this->iconv("$key: $value");
            }
            $this->MultiCell($cell_width, 4, $payment);
            $this->Ln(6);

            // draw a line
            $this->SetDrawColor(0, 0, 0);
            $this->Rect(10, $this->GetY(), 190, 0);
            $this->Ln(6);

            $this->SetFont('Arial', '', 11);
            $this->SetX(105);
            $this->MultiCell($cell_width, 4, $this->ficonv('InvoiceDate'));
            $this->Ln(6);
            
            $this->cur_y = $this->GetY();
        }

        function Body() {
            $this->SetX(self::LEFT);
            $this->SetY($this->cur_y+7);

            $cur_y = $this->GetY();
            $this->SetFillColor(255,255,255);
            $this->SetTextColor(0,0,0);
            $this->SetX(10);
            $this->SetY($cur_y+10);
            
            $col_width = 190 / count($this->columns);
            /*
            foreach ($this->columns as $column)
                $this->Cell($col_width,5,iconv('UTF-8', 'windows-1252', $column),1,0,'C',1);
            */
            $this->Ln(5);
            $this->SetFillColor(255,255,255);
            $this->SetX(10);
            $this->SetFont('Arial', '', 11);
            
            $r = 0;
            foreach ($this->rows as $row) :
                
                $row_height = 0;
                for ($c=0;$c<count($this->columns);$c++) :
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

                if ( $row_height >= $space_left) {   
                    $this->AddPage();
                    $this->SetX(self::LEFT);
                    $this->SetY($this->cur_y+7);

                    $this->Ln(5);
                    $this->SetFillColor(255,255,255);
                    $this->SetX(10);
                    //$this->SetFont('Arial', '', 12);
                }

                for ($c=0;$c<count($this->columns);$c++) :
                    $align = 'L';
              
                    if (strtolower($this->columns[$c]) == 'price') {
                        $align = 'R';
                        $row[$this->columns[$c]] = $this->price($row[$this->columns[$c]]);
                    }
                    
                    $row_y = $this->GetY();
                    $row_x = $this->GetX();
                    $this->Rect($row_x, $row_y, $col_width, $row_height, "FD");
                    $this->MultiCell($col_width, 5, $row[$this->columns[$c]], 0, $align);
                    $this->SetY($row_y);
                    $this->SetX($row_x + $col_width);
                    
                endfor;
                $this->Ln($row_height); 
                $r++;
            endforeach;
            
            $this->SetY($this->GetY()+7);
            
            $this->SetFillColor(255,255,255);

            for ($c=0;$c<count($this->columns);$c++) :
                if ($c == count($this->columns)-2) :
                    $this->SetFont('Arial', 'B', 12);
                    $this->Cell($col_width, 5, $total_label, 0, 0, 'R', 0);
                elseif ($c == count($this->columns)-1) :
                    $this->SetFont('Arial', 'B', 12);
                    $this->Cell($col_width, 5, $this->price($this->invoice['InvoiceTotal']), 0, 0, 'R', 0);
                else :
                    $this->Cell($col_width, 5, '', 0, 0, 'C', 0);
                endif;
            endfor;
            $this->Ln(6);
            $this->SetX(10);
            $this->SetY($this->GetY()+10);            
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
    }

    $pdf = new LocalPDF('P','mm','A4');
    $pdf->setData($invoice, $columns, $rows);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(1, 20);
    $pdf->Body();
        
    $pdf->AddPage();
    
    //$pdf->SetDrawColor(204, 204, 204);
    


    $pdf_path = bb_agency_BASEPATH.'invoices/'.$invoice['FileName'].'.pdf';
    $pdf->Output($pdf_path, "F");
?>