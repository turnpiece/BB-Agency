<?php
    class LocalPDF extends FPDF {
        function setData($invoice, $paid_watermark, $paid_label) {
            $this->invoice = $invoice;
            $this->paid_watermark = $paid_watermark;
            $this->paid_label = $paid_label;
        }
        function Header() {
            if ($this->invoice[0]->custom['bbinv_paid_invoice'][0] == 1)
                $this->watermark(iconv('UTF-8', 'windows-1252', $this->paid_watermark));

            $this->SetX(15); $this->SetY($this->GetY() + 5);
            $this->SetFont('Arial','B',20);
            $this->Cell(95,8,iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_company_name'][0]),0,0,'L');
            $this->SetFont('Arial','B',24);
            if ($this->invoice[0]->custom['bbinv_paid_invoice'][0] == 1)
                $this->Cell(95,8,iconv('UTF-8', 'windows-1252', $this->paid_label),0,0,'R');
            else
                $this->Cell(95,8,iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_invoice_type'][0]),0,0,'R');
            $this->Ln(15);

            $this->SetX(10);
            $cur_y = $this->GetY();
            $this->SetFont('Arial', '', 9);
            $this->MultiCell(95, 4, iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_address'][0])."\r\n".iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_suburb'][0])."\r\n".iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_state'][0])." ".iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_postcode'][0])."\r\n\r\n".iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_phone'][0])."\r\n".iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_email'][0])."\r\n".iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_add_detail'][0]));

            $this->SetY($cur_y);
            $this->SetX(105);
            $this->MultiCell(95, 4, iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_date'][0])."\r\n".iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_invoice_no_label'][0])." ".iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_invoice_no'][0])."\r\n".iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_po_label'][0])." ".iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_po'][0]), 0, 'R');
            $this->Ln(4);
            $this->SetX(105);
            $this->SetFont('Arial','B',16);
            $this->MultiCell(95, 7, iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_attn_name_label'][0])." ".iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_attn_name'][0])."\r\n".iconv('UTF-8', 'windows-1252', $this->invoice[0]->custom['bbinv_client_company'][0]), 0, 'R');
            $this->Ln(6);

            $cur_y = $this->GetY();
            $this->SetDrawColor(204, 204, 204);
            $this->Rect(10, $cur_y, 190, 0);
            
            $this->cur_y = $cur_y;
        }
    }
    $pdf = new LocalPDF('P','mm','A4');
    $pdf->setData($invoice, $paid_watermark, $paid_label);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(1, 20);

    $page_height = 279.4;
    $bottom_margin = 20;
        
    $pdf->AddPage();
    
    $pdf->SetDrawColor(204, 204, 204);
    
    $pdf->SetX(10);
    $pdf->SetY($pdf->cur_y+7);
    $pdf->SetFont('Arial', '', 9);
    $pdf->MultiCell(190, 4, iconv('UTF-8', 'windows-1252', $invoice[0]->custom['bbinv_open_content_1'][0]));
    
    $cur_y = $pdf->GetY();
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetX(10);
    $pdf->SetY($cur_y+10);
    $pdf->SetFont('Arial','B',9);
    $pdf->SetFillColor(227,227,227);
    
    $col_width = 190 / count($columns);
    foreach ($columns as $column)
        $pdf->Cell($col_width,5,iconv('UTF-8', 'windows-1252', $column),1,0,'C',1);
    $pdf->Ln(5);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetX(10);
    $pdf->SetFont('Arial','',9);
    
    $r = 0;
    foreach ($rows as $row) :
        
        $row_height = 0;
        for ($c=0;$c<count($columns);$c++) :
            $content = $row[$columns[$c]];

            $total_string_width = $pdf->GetStringWidth($content);
            $number_of_lines = ceil( $total_string_width / ($col_width + 5) );

            $line_height = 5;
            $height_of_cell = ceil( $number_of_lines * $line_height ) + ($line_height * 2); 

            if ($height_of_cell > $row_height)
                $row_height = $height_of_cell;
        endfor;

        $space_left = $page_height - $pdf->GetY();
        $space_left -= $bottom_margin;

        if ( $row_height >= $space_left) {   
           $pdf->AddPage();
            $pdf->SetX(10);
            $pdf->SetY($pdf->cur_y+7);
           $pdf->SetFont('Arial','B',9);
            $pdf->SetFillColor(227,227,227);

            foreach ($columns as $column)
                $pdf->Cell($col_width,5,iconv('UTF-8', 'windows-1252', $column),1,0,'C',1);
            $pdf->Ln(5);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetX(10);
            $pdf->SetFont('Arial','',9);
        }
        
        if ($r % 2) {
            $pdf->SetFillColor(238,238,238);
            $fill = 1;
        } else {
            $pdf->SetFillColor(255,255,255);
            $fill = 0;
        }
        
        for ($c=0;$c<count($columns);$c++) :
            $align = 'L';
      
            if ($column_types[$c] == 'numeric') $align = 'C';
            if ($column_types[$c] == 'price') {
                $align = 'R';
                $row[$columns[$c]] = iconv('UTF-8', 'windows-1252', $currency_symbol).iconv('UTF-8', 'windows-1252', $row[$columns[$c]]);
            }
            
            $row_y = $pdf->GetY();
            $row_x = $pdf->GetX();
            $pdf->Rect($row_x, $row_y, $col_width, $row_height, "FD");
            $pdf->MultiCell($col_width,5,$row[$columns[$c]],0,$align);
            $pdf->SetY($row_y);
            $pdf->SetX($row_x + $col_width);
            
        endfor;
        $pdf->Ln($row_height); 
        $r++;
    endforeach;
    
    $pdf->SetY($pdf->GetY()+7);
    
    $pdf->SetFillColor(255,255,255);
    
    for ($c=0;$c<count($columns);$c++) :
        if ($c == count($columns)-2) :
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell($col_width,5,$subtotal_label,0,0,'R',0);
        elseif ($c == count($columns)-1) :
            $pdf->SetFont('Arial','',9);
            $pdf->Cell($col_width,5,iconv('UTF-8', 'windows-1252', $currency_symbol).$invoice[0]->custom['bbinv_subtotal'][0],0,0,'R',0);
        else :
            $pdf->Cell($col_width,5,'',0,0,'C',0);
        endif;
    endfor;
    $pdf->Ln(5);
    
    for ($c=0;$c<count($columns);$c++) :
        if ($c == count($columns)-2) :
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell($col_width,5,$discount_label,0,0,'R',0);
        elseif ($c == count($columns)-1) :
            $pdf->SetFont('Arial','',9);
            $pdf->Cell($col_width,5,iconv('UTF-8', 'windows-1252', $currency_symbol).$invoice[0]->custom['bbinv_discount'][0],0,0,'R',0);
        else :
            $pdf->Cell($col_width,5,'',0,0,'C',0);
        endif;
    endfor;
    $pdf->Ln(5);
    
    for ($c=0;$c<count($columns);$c++) :
        if ($c == count($columns)-2) :
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell($col_width,5,$tax_label,0,0,'R',0);
        elseif ($c == count($columns)-1) :
            $pdf->SetFont('Arial','',9);
            $pdf->Cell($col_width,5,iconv('UTF-8', 'windows-1252', $currency_symbol).$invoice[0]->custom['bbinv_gst'][0],0,0,'R',0);
        else :
            $pdf->Cell($col_width,5,'',0,0,'C',0);
        endif;
    endfor;
    $pdf->Ln(5);
    
    for ($c=0;$c<count($columns);$c++) :
        if ($c == count($columns)-2) :
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell($col_width,5,$total_label,0,0,'R',0);
        elseif ($c == count($columns)-1) :
            $pdf->SetFont('Arial','',9);
            $pdf->Cell($col_width,5,iconv('UTF-8', 'windows-1252', $currency_symbol).$invoice[0]->custom['bbinv_total'][0],0,0,'R',0);
        else :
            $pdf->Cell($col_width,5,'',0,0,'C',0);
        endif;
    endfor;
    $pdf->Ln(5);
    $pdf->SetX(10);
    $pdf->SetY($pdf->GetY()+10);
    $pdf->SetFont('Arial', '', 9);
    $pdf->MultiCell(190, 4, iconv('UTF-8', 'windows-1252', $invoice[0]->custom['bbinv_open_content_2'][0]));
    
    $pdf_filename = bbinv_gen_filename($invoice[0]);

    $pdf_path = plugin_dir_path(__FILE__)."../outputs/".$pdf_filename.".pdf";
    $pdf->Output($pdf_path, "F");
?>