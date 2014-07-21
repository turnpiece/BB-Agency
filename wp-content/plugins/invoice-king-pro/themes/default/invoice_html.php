<div id="invkp_invoice" class="default">
    <input type="hidden" name="invkp_meta_box_nonce" value="<?= $inv_nonce ?>" />
    <div class="inv_header">
        <input type="text" name="invkp_company_name" value="<?= $custom_fields['invkp_company_name'][0] ?>" placeholder="Your Company Name" class="h2" />
        <input type="text" name="invkp_invoice_type" value="<?= $custom_fields['invkp_invoice_type'][0] ?>" placeholder="INVOICE TYPE" class="h1" />
    </div>
    
    <div class="inv_details">
        <div class="inv_address">
            <input type="text" name="invkp_address" value="<?= $custom_fields['invkp_address'][0] ?>" placeholder="Your Street Address" /><br />
            <input type="text" name="invkp_suburb" value="<?= $custom_fields['invkp_suburb'][0] ?>" placeholder="Your Suburb/Town" /><br />
            <input type="text" name="invkp_state" value="<?= $custom_fields['invkp_state'][0] ?>" placeholder="Your State" /><input type="text" name="invkp_postcode" value="<?= $custom_fields['invkp_postcode'][0] ?>" placeholder="Your Postcode/Zip" />
        </div>
        <div class="inv_numbers">
            <input type="text" name="invkp_date" value="<?= $custom_fields['invkp_date'][0] ?>" placeholder="The Date" /><br />
            <input type="text" name="invkp_invoice_no_label" value="<?= $custom_fields['invkp_invoice_no_label'][0] ?>" placeholder="Invoice Number Label" /><input type="text" name="invkp_invoice_no" value="<?= $custom_fields['invkp_invoice_no'][0] ?>" placeholder="Invoice Number" /><br />
            <input type="text" name="invkp_po_label" value="<?= $custom_fields['invkp_po_label'][0] ?>" placeholder="PO Number Label" /><input type="text" name="invkp_po" value="<?= $custom_fields['invkp_po'][0] ?>" placeholder="PO Number" />
        </div>
    </div>
    
    <div class="inv_details_2">
        <div class="inv_contact">
            <input type="text" name="invkp_phone" value="<?= $custom_fields['invkp_phone'][0] ?>" placeholder="Your Phone Number" /><br />
            <input type="text" name="invkp_email" value="<?= $custom_fields['invkp_email'][0] ?>" placeholder="Your Email Address" /><br />
            <input type="text" name="invkp_add_detail" value="<?= $custom_fields['invkp_add_detail'][0] ?>" placeholder="Additional Detail" />
        </div>
        <div class="inv_client">
            <input type="text" name="invkp_attn_name_label" value="<?= $custom_fields['invkp_attn_name_label'][0] ?>" placeholder="Client Name Label" class="h3" /><input type="text" name="invkp_attn_name" value="<?= $custom_fields['invkp_attn_name'][0] ?>" placeholder="Client Name" class="h3" /><br />
            <input type="text" name="invkp_client_company" value="<?= $custom_fields['invkp_client_company'][0] ?>" placeholder="Client Company Name" class="h3" />
        </div>
    </div>
    
    <div class="inv_content_1">
        <textarea name="invkp_open_content_1" placeholder="Open content field 1 - NO HTML RENDERED" class="elastic"><?= $custom_fields['invkp_open_content_1'][0] ?></textarea>
    </div>
    
    <div class="inv_columns">
        <input type="hidden" name="invkp_columns" value='<?= htmlentities($custom_fields['invkp_columns'][0]) ?>' />
        <input type="hidden" name="invkp_column_types" value='<?= htmlentities($custom_fields['invkp_column_types'][0]) ?>' />
        <input type="hidden" name="invkp_column_widths" value='<?= htmlentities($custom_fields['invkp_column_widths'][0]) ?>' />
        <input type="hidden" name="invkp_calculate_rows" value='<?= htmlentities($custom_fields['invkp_calculate_rows'][0]) ?>' />
        <input type="hidden" name="invkp_calculate_operators" value='<?= htmlentities($custom_fields['invkp_calculate_operators'][0]) ?>' />
        <input type="hidden" name="invkp_calculate_subtotal" value='<?= $custom_fields['invkp_calculate_subtotal'][0] ?>' />
        <input type="hidden" id="row_calc" value='<?= $json_calc ?>' />
        <table class="styled">
            <thead>
                <tr>
                    <?php for ($c=0;$c<count($columns);$c++) : ?>
                    <th style="width: <?= $column_widths[$c] ?>%;"><?= $columns[$c]; ?></th>
                    <?php endfor ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (is_array($rows)) : 
                    $c = 0;
                    foreach ($rows as $row) : ?>
                <tr>
                    <?php $i = 1; for ($col=0;$col<count($columns);$col++) : ?>
                    <td style="width: <?= $column_widths[$col] ?>%;"><input type="text" name="invkp_column[<?= $c ?>][<?= $columns[$col] ?>]" placeholder="Row <?= $c+1 ?> - <?= $columns[$col] ?>" data-name="<?= $columns[$col] ?>" class="<?= preg_replace('~[^\p{L}\p{N}]++~u', '', $columns[$col]) ?><?php if (in_array(preg_replace('~[^\p{L}\p{N}]++~u', '', $columns[$col]), $row_calc)) echo ' calc'; ?><?php if ($columns[$col] === $subtotal_col) echo ' subtotal_col'; ?>"<?php if ($columns[$col] === $subtotal_col) echo ' readonly'; ?> value="<?= htmlentities($row[$columns[$col]]) ?>"<?php if ($row_calc[count($row_calc)-1] === $columns[$col]) echo ' readonly' ?> />
                    <?php if ($c > 0 && $i == count($columns)) : ?>
                        <a class="remove_invkp_row">X</a>
                    <?php endif; ?>
                    </td>
                    <?php $i++; endfor; ?>
                </tr>
                <?php $c++; endforeach; else : ?>
                <tr>
                    <?php for ($col=0;$col<count($columns);$col++) : ?>
                    <td style="width: <?= $column_widths[$col] ?>%;"><input type="text" name="invkp_column[0][<?= $columns[$col] ?>]" placeholder="Row 1 - <?= $columns[$col] ?>" data-name="<?= $columns[$col] ?>" class="<?= preg_replace('~[^\p{L}\p{N}]++~u', '', $columns[$col]) ?><?php if (in_array(preg_replace('~[^\p{L}\p{N}]++~u', '', $columns[$col]), $row_calc)) echo ' calc'; ?><?php if ($columns[$col] === $subtotal_col) echo ' subtotal_col'; ?>"<?php if ($subtotal_col === $columns[$col]) echo ' readonly' ?> /></td>
                    <?php endfor; ?>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a class="add_invkp_row">Add a Row</a>
        <table style="float: right; width: 100%;">
            <tr>
                <?php $c=1; for ($col=0;$col<count($columns);$col++) : ?>
                <?php if ($c === count($columns)) : ?>
                <td style="width: <?= $column_widths[$col] ?>%;"><input type="text" name="invkp_subtotal" id="invkp_subtotal" value="<?= $custom_fields['invkp_subtotal'][0] ?>" placeholder="Subtotal - To be calculated" readonly /></td>
                <?php elseif ($c === count($columns)-1) : ?>
                <th style="text-align: right;"><?= $custom_fields['invkp_subtotal_label'][0] ?></th>
                <?php else : ?>
                <td></td>
                <?php endif; ?>
                <?php $c++; endfor; ?>
            </tr>
            <tr>
                <?php $c=1; for ($col=0;$col<count($columns);$col++) : ?>
                <?php if ($c === count($columns)) : ?>
                <td style="width: <?= $column_widths[$col] ?>%;"><input type="text" name="invkp_discount" id="invkp_discount" value="<?= $custom_fields['invkp_discount'][0] ?>" placeholder="Discount - To be calculated" readonly /></td>
                <?php elseif ($c === count($columns)-1) : ?>
                <th style="text-align: right;"><?= $custom_fields['invkp_discount_label'][0] ?></th>
                <?php else : ?>
                <td></td>
                <?php endif; ?>
                <?php $c++; endfor; ?>
            </tr>
            <tr>
                <?php $c=1; for ($col=0;$col<count($columns);$col++) : ?>
                <?php if ($c === count($columns)) : ?>
                <td style="width: <?= $column_widths[$col] ?>%;"><input type="text" name="invkp_gst" id="invkp_gst" value="<?= $custom_fields['invkp_gst'][0] ?>" placeholder="GST/Tax - To be calculated" readonly /></td>
                <?php elseif ($c === count($columns)-1) : ?>
                <th style="text-align: right;"><?= $custom_fields['invkp_tax_label'][0] ?></th>
                <?php else : ?>
                <td></td>
                <?php endif; ?>
                <?php $c++; endfor; ?>
            </tr>
            <tr>
                <?php $c=1; for ($col=0;$col<count($columns);$col++) : ?>
                <?php if ($c === count($columns)) : ?>
                <td style="width: <?= $column_widths[$col] ?>%;"><input type="text" name="invkp_total" id="invkp_total" value="<?= $custom_fields['invkp_total'][0] ?>" placeholder="Total - To be calculated" readonly /></td>
                <?php elseif ($c === count($columns)-1) : ?>
                <th style="text-align: right;"><?= $custom_fields['invkp_total_label'][0] ?></th>
                <?php else : ?>
                <td></td>
                <?php endif; ?>
                <?php $c++; endfor; ?>
            </tr>
        </table>
    </div>
    
    <div class="inv_content_2">
        <textarea name="invkp_open_content_2" placeholder="Open content field 2 - NO HTML RENDERED" class="elastic"><?= $custom_fields['invkp_open_content_2'][0] ?></textarea>
    </div>
</div>