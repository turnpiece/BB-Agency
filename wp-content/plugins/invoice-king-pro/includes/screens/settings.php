<div class="wrap">
    <?php screen_icon(); ?>
    <h2>Invoice King Pro</h2>
    
    <div class="kpp_block filled">
        <h2><?= __('Connect', 'invkptext') ?></h2>
        <div id="kpp_social">
            <div class="kpp_social facebook"><a href="https://www.facebook.com/KingProPlugins" target="_blank"><i class="icon-facebook"></i> <span class="kpp_width"><span class="kpp_opacity">Facebook</span></span></a></div>
            <div class="kpp_social twitter"><a href="https://twitter.com/KingProPlugins" target="_blank"><i class="icon-twitter"></i> <span class="kpp_width"><span class="kpp_opacity">Twitter</span></span></a></div>
            <div class="kpp_social google"><a href="https://plus.google.com/b/101488033905569308183/101488033905569308183/about" target="_blank"><i class="icon-google-plus"></i> <span class="kpp_width"><span class="kpp_opacity">Google+</span></span></a></div>
        </div>
        <h4><?= __("Found an issue? Post your issue on the", 'invkptext') ?> <a href="http://wordpress.org/support/plugin/invoice-king-pro" target="_blank"><?= __("support forums", 'invkptext') ?></a>. <?= __("If you would prefer, please email your concern to", 'invkptext') ?> <a href="mailto:plugins@kingpro.me">plugins@kingpro.me</a></h4>   
    </div>
    
    <div class="invkp_tabs">
        <a class="invkp_invoice_settings active"><?= __("Invoice Settings", 'invkptext') ?></a>
        <a class="invkp_default_details"><?= __("Default Details", 'invkptext') ?></a>
        <a class="invkp_email_settings"><?= __("Email Settings", 'invkptext') ?></a>
        <?php do_action('invkp_additional_settings_tab'); ?>
        <a class="invkp_themes"><?= __("Themes", 'invkptext') ?></a>
        <a class="invkp_addons"><?= __("Add-ons", 'invkptext') ?></a>
        <a class="invkp_howto"><?= __("How-To", 'invkptext') ?></a>
        <a class="invkp_faq"><?= __("FAQ", 'invkptext') ?></a>
    </div>
    
    <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true') :
        do_action('invkp_on_update_settings');
    ?>
    <div class="updated invkp_notice">
        <p><?php _e( "Settings have been saved", 'invkptext' ); ?></p>
    </div>
    <?php elseif (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'false') : ?>
    <div class="error invkp_notice">
        <p><?php _e( "Settings have <strong>NOT</strong> been saved. Please try again.", 'invkptext' ); ?></p>
    </div>
    <?php endif; ?>
    
    <div class="invkp_sections">
        <form method="post" action="options.php">
        <?php settings_fields('invkp-options'); ?>
        <?php do_settings_sections('invkp-options'); ?>
        
        <?php /****** INVOICE SETTINGS ******/ ?>
        <div id="invkp_invoice_settings" class="invkp_section active">
                <?php submit_button(__('Save Settings', 'invkptext'), 'primary', 'submit', false, array('id'=>'invkp_invoice_settings_top_submit')); ?>
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row"><?= __("Invoice Theme", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_theme'); ?>
                        <?php
                            $plugin_path = plugin_dir_path(__FILE__);
                            if (strstr($plugin_path, "includes\screens/")) {
                                    $dir = str_replace("includes\screens/","",$plugin_path.'themes\\');
                                    $folder = scandir($dir);
                            } else {
                                    $dir = str_replace("includes/screens/","",$plugin_path.'themes/');
                                    $folder = scandir($dir);
                            }
                            $exclude = array('.', '..');
                        ?>
                        <select name="invkp_theme">
                            <?php
                                foreach ($folder as $f) {
                                    if (!in_array($f, $exclude) && is_dir($dir.$f)) {
                                        $selected = '';
                                        if ($val == $f) $selected = ' selected';
                                        echo '<option value="'.$f.'"'.$selected.'>'.ucwords(str_replace(array('-', '_'), ' ', $f)).'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </td>
                    <td>* <?= __("More themes can be downloaded from the", 'invkptext') ?> <a href="http://kingpro.me/plugins/invoice-king-pro/themes/" target="_blank">King Pro Plugins <?=__("website", 'invkptext') ?></a></td>
                    </tr>

                    <?php if ( function_exists( 'invkp_theme_options' ) ) :
                        invkp_theme_options();
                     endif; ?>

                    <tr valign="top">
                    <th scope="row"><?= __("Currency Symbol", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_revenue_currency'); ?>
                        <input type="text" name="invkp_revenue_currency" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>
                    
                    <tr valign="top">
                    <th scope="row"><?= __("Invoice Type Label", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_invoice_type'); ?>
                        <input type="text" name="invkp_invoice_type" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Paid Invoice Type Label", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_paid_invoice_type'); ?>
                        <input type="text" name="invkp_paid_invoice_type" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Paid Invoice Watermark", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_paid_watermark'); ?>
                        <input type="text" name="invkp_paid_watermark" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Invoice Number Label", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_invoice_no_label'); ?>
                        <input type="text" name="invkp_invoice_no_label" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Purchase Order Label", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_po_label'); ?>
                        <input type="text" name="invkp_po_label" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Attention to Label", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_attn_name_label'); ?>
                        <input type="text" name="invkp_attn_name_label" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>
                    
                    <tr valign="top">
                    <th scope="row"><?= __("Subtotal Label", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_subtotal_label'); ?>
                        <input type="text" name="invkp_subtotal_label" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>
                    
                    <tr valign="top">
                    <th scope="row"><?= __("Discount Label", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_discount_label'); ?>
                        <input type="text" name="invkp_discount_label" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("GST/Tax Label", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_tax_label'); ?>
                        <input type="text" name="invkp_tax_label" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>
                    
                    <tr valign="top">
                    <th scope="row"><?= __("GST/Tax Value", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_tax_value'); ?>
                        <input type="text" name="invkp_tax_value" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>
                    
                    <tr valign="top">
                    <th scope="row"><?= __("Total Label", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_total_label'); ?>
                        <input type="text" name="invkp_total_label" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>
        
                    <tr valign="top">
                    <th scope="row"><?= __("Invoice Number Generating", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_invoice_no_gen'); ?>
                        <input type="text" name="invkp_invoice_no_gen" value="<?= $val ?>" />
                    </td>
                    <td><?= __("Refer to the help in the How-to tab", 'invkptext') ?></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Last Invoice Number Generated", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_invoice_no_gen_last'); ?>
                        <input type="text" name="invkp_invoice_no_gen_last" value="<?= $val ?>" />
                    </td>
                    <td><?= __("Refer to the help in the How-to tab", 'invkptext') ?></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Current Invoice Number Increment", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_invoice_no_gen_incr'); ?>
                        <input type="text" name="invkp_invoice_no_gen_incr" value="<?= $val ?>" />
                    </td>
                    <td><?= __("Refer to the help in the How-to tab", 'invkptext') ?></td>
                    </tr>
                    
                    <tr valign="top">
                    <th scope="row"><?= __("Invoice Filename Format", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_pdf_filename'); ?>
                        <input type="text" name="invkp_pdf_filename" value="<?= $val ?>" />
                    </td>
                    <td>
                        <?= __("Refer to the help in the How-to tab", 'invkptext') ?> - Example:<br />
                        {pid}-{company_name}-{inv_id}
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Define Columns", 'invkptext') ?></th>
                    <td>
                        <div class="invkp_columns">
                        <?php 
                            $val = get_option('invkp_columns'); 
                            $type_val = get_option('invkp_column_types'); 
                            $width_val = get_option('invkp_column_widths'); 
                        ?>
                        <div>
                            <span style="width: 181px; display: inline-block; font-weight: bold; margin-bottom: 5px;"><?= __("Name", 'invkptext') ?></span>
                            <span style="width: 72px; display: inline-block; font-weight: bold; margin-bottom: 5px;"><?= __("Type", 'invkptext') ?></span>
                            <span style="display: inline-block; font-weight: bold; margin-bottom: 5px;"><?= __("Width", 'invkptext') ?> (%)</span>
                        </div>
                        <?php if (is_array($val)) : ?>
                        <?php for ($c=0;$c<count($val);$c++) : ?>
                        <div>
                        <input type="text" name="invkp_columns[]" value="<?= $val[$c] ?>" />
                        <select name="invkp_column_types[]">
                            <option value="text"<?= ($type_val[$c] == 'text') ? ' selected' : '' ?>><?= __("Text", 'invkptext') ?></option>
                            <option value="numeric"<?= ($type_val[$c] == 'numeric') ? ' selected' : '' ?>><?= __("Numeric", 'invkptext') ?></option>
                            <option value="price"<?= ($type_val[$c] == 'price') ? ' selected' : '' ?>><?= __("Price", 'invkptext') ?></option>
                        </select>
                        <input type="text" name="invkp_column_widths[]" value="<?= $width_val[$c] ?>" size="3" placeholder="0%" style="text-align: right;" />%
                        <?php if ($c > 0) : ?><a class="remove_invkp_column"><?= __("Remove", 'invkptext') ?></a><?php endif; ?>
                        </div>
                        <?php endfor; ?>
                        <?php else : ?>
                        <div>
                        <input type="text" name="invkp_columns[]" value="" />
                        <select name="invkp_column_types[]">
                            <option value="text"><?= __("Text", 'invkptext') ?></option>
                            <option value="numeric"><?= __("Numeric", 'invkptext') ?></option>
                            <option value="price"><?= __("Price", 'invkptext') ?></option>
                        </select>
                        <input type="text" name="invkp_column_widths[]" value="" size="3" placeholder="0%" />%
                        </div>
                        <?php endif; ?>
                        </div>
                        <br />
                        <a class="add_invkp_column"><?= __("Add Column", 'invkptext') ?></a>
                    </td>
                    <td><?= __("Leave width blank to generate even columns", 'invkptext') ?></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Row Calculation", 'invkptext') ?></th>
                    <td>
                        <div class="invkp_row_calculation">
                            <?php 
                            $val = get_option('invkp_columns');
                            $cols = array();
                            if (is_array($val)) :
                                foreach ($val as $col)
                                    $cols[] = $col;
                            endif; 
                            $calc_cols = get_option('invkp_calculate_rows');
                            $calc_ops = get_option('invkp_calculate_operators');

                            if (!empty($calc_cols) && isset($calc_ops[0]) && $calc_ops[0] <> '') :
                                for ($c=0;$c<count($calc_cols); $c++) :
                                    if ($calc_cols[$c] <> '') :
                            ?>
                            <select name="invkp_calculate_rows[]" class="row">
                                <option value="">-- <?= __("SELECT", 'invkptext') ?> --</option>
                                <?php foreach ($cols as $col) : ?>
                                <option value="<?= $col ?>"<?php if ($calc_cols[$c] == $col) echo ' selected'; ?>><?= $col ?></option>
                                <?php endforeach ?>
                            </select>
                            <br />
                            <?php if (isset($calc_ops[$c]) && $calc_ops[$c] <> '') : ?>
                            <select name='invkp_calculate_operators[]'>
                                <option value=''>-- <?= __("SELECT", 'invkptext') ?> --</option>
                                <option value='*'<?php if ($calc_ops[$c] == '*') echo ' selected'; ?>>*</option>
                                <option value='+'<?php if ($calc_ops[$c] == '+') echo ' selected'; ?>>+</option>
                                <option value='/'<?php if ($calc_ops[$c] == '/') echo ' selected'; ?>>/</option>
                                <option value='-'<?php if ($calc_ops[$c] == '-') echo ' selected'; ?>>-</option>
                                <option value='='<?php if ($calc_ops[$c] == '=') echo ' selected'; ?>>=</option>
                            </select>
                            <br />
                            <?php endif; endif; endfor; ?>
                            <?php endif; ?>
                            <select name="invkp_calculate_rows[]" class="row">
                                <option value="">-- <?= __("SELECT", 'invkptext') ?> --</option>
                                <?php foreach ($cols as $col) : ?>
                                <option value="<?= $col ?>"><?= $col ?></option>
                                <?php endforeach ?>
                            </select>
                            <br />
                        </div>
                    </td>
                    <td><?= __('Options available after "Define Columns" saved.', 'invkptext') ?></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Subtotals Calculation Column", 'invkptext') ?></th>
                    <td>
                        <?php 
                        $subtotal_col = get_option('invkp_calculate_subtotal');
                        $val = get_option('invkp_columns');
                        $cols = array();
                        if (is_array($val)) :
                            foreach ($val as $col)
                                $cols[] = $col;
                        endif; 
                        ?>
                        <select name="invkp_calculate_subtotal">
                            <option value="">-- <?= __("SELECT", 'invkptext') ?> --</option>
                            <?php foreach ($cols as $col) : ?>
                            <option value="<?= $col ?>"<?php if ($subtotal_col == $col) echo ' selected'; ?>><?= $col ?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <td><?= __('Options available after "Define Columns" saved.', 'invkptext') ?></td>
                    </tr>
                </table>
                <?php submit_button(__('Save Settings', 'invkptext'), 'primary', 'submit', false, array('id'=>'invkp_invoice_settings_bottom_submit')); ?>
        </div>
        
        <?php /****** DEFAULT DETAILS ******/ ?>
        <div id="invkp_default_details" class="invkp_section">
                <?php submit_button(__('Save Options', 'invkptext'), 'primary', 'submit', false, array('id'=>'invkp_default_details_top_submit')); ?>
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row"><?= __("Your Company Name", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_company_name'); ?>
                        <input type="text" name="invkp_company_name" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Your Address", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_address'); ?>
                        <input type="text" name="invkp_address" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Your Suburb/Town", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_suburb'); ?>
                        <input type="text" name="invkp_suburb" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Your State", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_state'); ?>
                        <input type="text" name="invkp_state" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Your Postcode/Zip", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_postcode'); ?>
                        <input type="text" name="invkp_postcode" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Your Phone", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_phone'); ?>
                        <input type="text" name="invkp_phone" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Your Email", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_email'); ?>
                        <input type="text" name="invkp_email" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Additional Detail", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_add_detail'); ?>
                        <input type="text" name="invkp_add_detail" value="<?= $val ?>" />
                    </td>
                    <td><?= __("eg Business Details (ie ABN)", 'invkptext') ?></td>
                    </tr>
                    
                    <tr valign="top">
                    <th scope="row"><?= __("Open Content Block 1", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_open_content_1'); ?>
                        <textarea name="invkp_open_content_1"><?= $val ?></textarea>
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Open Content Block 2", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_open_content_2'); ?>
                        <textarea name="invkp_open_content_2"><?= $val ?></textarea>
                    </td>
                    <td></td>
                    </tr>
                </table>
                <?php submit_button(__('Save Options', 'invkptext'), 'primary', 'submit', false, array('id'=>'invkp_default_details_bottom_submit')); ?>
        </div>
        
        <?php /****** EMAIL SETTINGS ******/ ?>
        <div id="invkp_email_settings" class="invkp_section">
                <?php submit_button(__('Save Settings', 'invkptext'), 'primary', 'submit', false, array('id'=>'invkp_email_settings_top_submit')); ?>
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row"><?= __("From Name", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_from'); ?>
                        <input type="text" name="invkp_from" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("From Email", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_from_email'); ?>
                        <input type="text" name="invkp_from_email" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("BCC the above email?", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_bcc'); ?>
                        <input type="hidden" name="invkp_bcc" value="0" />
                        <input type="checkbox" name="invkp_bcc" value="1"<?= ($val == 1) ? " checked" : '' ?> />
                    </td>
                    <td><?= __("Have all email correspondence sent to the above email", 'invkptext') ?></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Subject", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_email_subject'); ?>
                        <input type="text" name="invkp_email_subject" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Message", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_email_message'); ?>
                        <textarea name="invkp_email_message" style="height: 280px; width: 275px;"><?= $val ?></textarea>
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Paid Subject", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_paid_email_subject'); ?>
                        <input type="text" name="invkp_paid_email_subject" value="<?= $val ?>" />
                    </td>
                    <td></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?= __("Paid Message", 'invkptext') ?></th>
                    <td>
                        <?php $val = get_option('invkp_paid_email_message'); ?>
                        <textarea name="invkp_paid_email_message" style="height: 280px; width: 275px;"><?= $val ?></textarea>
                    </td>
                    <td></td>
                    </tr>
                </table>
                <?php submit_button(__('Save Settings', 'invkptext'), 'primary', 'submit', false, array('id'=>'invkp_email_settings_bottom_submit')); ?>
        </div>
            
        <?php do_action('invkp_additional_settings_section'); ?>
            
        <?php /****** THEMES ******/ ?>
        <div id="invkp_themes" class="invkp_section">
            <h2><?= __("Themes", 'invkptext') ?></h2>
            
            <p><?= __("Personalise your invoices by using an Invoice King Pro theme. The current selection is below. If you would like one that is customised just for you,
                contact", 'invkptext') ?> <a href="mailto:plugins@kingpro.me">plugins@kingpro.me</a>.</p>
            
            <div class="kpp_item">
                <a href="<?= plugins_url('../images/clean-invoice-theme.png', dirname(__FILE__)) ?>"><img alt="Clean Invoice" src="<?= plugins_url('../images/clean-invoice-theme.png', dirname(__FILE__)) ?>" /></a>
                <span class="title">Clean</span>
                <span class="description"><?= __("Nice sharp design using<br />a custom font", 'invkptext') ?>.</span>
                <span class="links"><a href="http://kingpro.me/plugins/invoice-king-pro/themes/" target='_blank' title="Get the 'Clean' theme"><?= __("Get It", 'invkptext') ?></a></span>
            </div>

            <div class="kpp_item">
                <a href="<?= plugins_url('../images/default-with-image-invoice-theme.png', dirname(__FILE__)) ?>"><img alt="Default with Logo" src="<?= plugins_url('../images/default-with-image-invoice-theme.png', dirname(__FILE__)) ?>" /></a>
                <span class="title"><?= __("Default with Logo", 'invkptext') ?></span>
                <span class="description"><?= __("The default theme with added feature to upload a logo", 'invkptext') ?></span>
                <span class="links"><a href="http://kingpro.me/plugins/invoice-king-pro/themes/" target='_blank' title="Get the 'Default with Logo' theme"><?= __("Get It", 'invkptext') ?></a></span>
            </div>
            
            <div class="kpp_item">
                <a href="<?= plugins_url('../images/clean-invoice-theme.png', dirname(__FILE__)) ?>"><img alt="Clean with Logo" src="<?= plugins_url('../images/clean-invoice-theme.png', dirname(__FILE__)) ?>" /></a>
                <span class="title"><?= __("Clean with Logo", 'invkptext') ?></span>
                <span class="description"><?= __("Nice sharp design using<br />a custom font with added feature to upload a logo", 'invkptext') ?>.</span>
                <span class="links"><a href="http://kingpro.me/plugins/invoice-king-pro/themes/" target='_blank' title="Get the 'Clean' theme"><?= __("Get It", 'invkptext') ?></a></span>
            </div>
        </div>
            
        <?php /****** ADDONS ******/ ?>
        <div id="invkp_addons" class="invkp_section">
            <h2><?= __("Add-ons", 'invkptext') ?></h2>
            <p><?= __("Expand the functionality of Invoice King Pro to your necessity by installing Addons. Is there additional functionality your looking for that an addon
            doesn't cover? Contact", 'invkptext') ?> <a href="mailto:plugins@kingpro.me">plugins@kingpro.me</a> <?= __("and fill us in about what you after.", 'invkptext') ?></p>
            
            <div class="kpp_item">
                <img alt="<?= __("Recurring Invoice", 'invkptext') ?>" src="<?= plugins_url('../images/addon-recurring.jpg', dirname(__FILE__)) ?>" />
                <span class="title"><?= __("Recurring Invoices", 'invkptext') ?></span>
                <span class="description"><?= __("Turn new and existing invoices into automatic recurring invoices via a cron", 'invkptext') ?></span>
                <span class="links"><a href="http://kingpro.me/plugins/invoice-king-pro/add-ons/" target='_blank' title="Get the 'Recurring Invoices' Add-on"><?= __("Get It", 'invkptext') ?></a></span>
            </div> 
            <div class="kpp_item">
                <img alt="<?= __("Attachments", 'invkptext') ?>" src="<?= plugins_url('../images/addon-attachments.jpg', dirname(__FILE__)) ?>" />
                <span class="title"><?= __("Attachments", 'invkptext') ?></span>
                <span class="description"><?= __("Attach additional files to all and individual invoices when sent to client", 'invkptext') ?></span>
                <span class="links"><a href="http://kingpro.me/plugins/invoice-king-pro/add-ons/" target='_blank' title="Get the 'Attachments' Add-on"><?= __("Get It", 'invkptext') ?></a></span>
            </div> 
        </div>
        
        <?php /****** HOW-TO ******/ ?>
        <div id="invkp_howto" class="invkp_section">
            <h2>How To Use</h2>
            <h3>1) Set your defaults</h3>
            <p>Default fields have been provided to the left to auto-populate common data on each invoice you create. You are able to modify all default data on each invoice to suit if needed.</p>
            <h3>2) Define your columns</h3>
            <p>Invoice King Pro is customisable right down to the columns you have in your invoice. Have as many or as little as you need. Keep in mind that the more columns you have the thinner the column width will be.</p>
            <h3>3) Define your row calculation</h3>
            <p>As your columns are completely customised by you, the system has no way to know what columns are part of your calculations unless you tell it. As you select a column from the dropdown provided (the dropdowns are populated after you save the settings with your columns defined), you will be given an operator to append to the calculation.</p>
            <p>Selecting the operator will give you another dropdown of columns. Continue this for all columns you need to include. Once you have chosen the columns and the operators that you need to give you a total for the row, select the equals operator followed by the row totals column.</p>
            <p>An example would be "Hours" "*" "Hourly Rate" "=" "Total". This will times the hours by your hourly rate and put the total in the total column.</p>
            <h3>4) Select the column to calculate the subtotal</h3>
            <p>Following the process that the system doesn't know what columns you may have, it needs to know what your row total column is to be able to calculate the subtotal and complete the calculation of the invoice. This dropdown will be populated once you have save the settings with the columns defined.</p>

            <h3>Generating invoice number</h3>
            <p><span style="font-weight:bold; font-style:italic;font-size:10px">Big Thank You to <a href="http://profiles.wordpress.org/mstaaij/" target="_blank">Mark</a> for providing code for this section.</span></p>
            <p>Setting up your auto generated invoice number is quite simple. You can have anything you would like in this field including static letters and numbers
            (if your letters exist in the PHP date list, escape them with backslashes eg \I\NVYmd## will return INV<?= date('Ymd') ?>01) as well as any date value and the incrementing number.
            Please do not use the symbol '#' for anything but the incrementing number.</p>
            <p>For reference, check the <a href="http://php.net/manual/en/function.date.php" target="_blank">date values</a> you can use in this field.</p>
            <p>The amount of '#' in the string determines how many numbers the invoice increments to. For example:</p>
            <ul>
                <li><strong>Ymd#</strong> will return <?= date('Ymd') ?>1 through to <?= date('Ymd') ?>9 then <?= date('Ymd') ?>0 and will continue on that loop</li>
                <li><strong>Ymd##</strong> will return <?= date('Ymd') ?>01 through to <?= date('Ymd') ?>99 then <?= date('Ymd') ?>00 and will continue on that loop</li>
                <li><strong>Ymd####</strong> will return <?= date('Ymd') ?>0001 through to <?= date('Ymd') ?>9999 then <?= date('Ymd') ?>0000 and will continue on that loop</li>
            </ul>
            <p>When using the date values in the invoice string, the increment number will reset, for example:</p>
            <ul>
                <li><strong>Using Ymd##</strong> today on 2 invoices will return <?= date('Ymd') ?>01 and <?= date('Ymd') ?>02 respectively. Tomorrow it will be <?= date('Ymd', strtotime('tomorrow')) ?>01</li>
                <li><strong>Using Y##</strong> today on 2 invoices will return <?= date('Y') ?>01 and <?= date('Y') ?>02 respectively. Next year it will be <?= date('Y', strtotime('+1 year')) ?>01</li>
                <li><strong>Using \I\NV##</strong> today on 2 invoices will return INV01 and INV02 respectively. Tomorrow it will be INV03, next year INV04 until it reaches its limit (described above)</li>
                <li><strong>Using \I\NV##</strong> today on 2 invoices will return INV01 and INV02 respectively. Changing the string to ##\I\NV will return 01INV</li>
            </ul>
            <p>The invoice number field is overwritable per invoice if needed. The options to control the last invoice number generated and the currently used increment is available if needed, <strong>but is not required to be entered or changed</strong>.</p>
            <h3>Setting Invoice Filename</h3>
            <p>By default, Invoice King Pro names your files using the invoices ID from your system. This number is required in the filename to prevent overriding of already
            generated invoices in the system. There are a handful of variables you can use to customise your invoice filenames. These include:</p>
            <ul>
                <li><strong>{pid}</strong> - invoices ID from your system. This variable is required SOMEWHERE in the filename</li>
                <li><strong>{company_name}</strong> - The company name attached to the invoice.</li>
                <li><strong>{date}</strong> - The generation date with the format 'Y-m-d'. NOT the date of the invoice.</li>
                <li><strong>{inv_id}</strong> - The invoice ID.</li>
            </ul>
            <p>Examples of use for the string could be:</p>
            <ul>
                <li>{company_name}-{pid}</li>
                <li>{company_name}-{inv_id}-{pid}</li>
                <li>your-company-name-{inv_id}-{pid}</li>
                <li>{date}-inv-{pid}</li>
                <li>{pid}-{company_name}</li>
            </ul>
            <h3>Setup Email Message</h3>
            <p>You have the option to modify the default email subject and message the sends with your invoice. There are dynamic variables in place that you can call into your subject line and message. They are:</p>
            <ul>
                <li><strong>{{invoice_type}}</strong> - The status of the invoice being sent. By default this is either Invoice or Receipt</li>
                <li><strong>{{invoice_number_label}}</strong> - The invoice number label you set to the left</li>
                <li><strong>{{invoice_number}}</strong> - The invoice number you set to the invoice being sent</li>
                <li><strong>{{invoice_date}}</strong> - The date you enter on the invoice being sent</li>
                <li><strong>{{invoice_due_date}}</strong> - The due date you enter on the invoice being sent</li>
                <li><strong>{{client_company_name}}</strong> - The client company name of the selected client (not the company name on the invoice) attached to the invoice being sent</li>
                <li><strong>{{client_name}}</strong> - The Attn name of your selected client (not the Attn name on the invoice) attached to the invoice being sent</li>
                <li><strong>{{invoice_total}}</strong> - The total of the invoice that is being sent</li>
            </ul>

            <h3>Install PDF Themes</h3>
            <p>Download themes from the <a href="http://kingpro.me/plugins/invoice-king-pro/themes/" target="_blank">King Pro Plugins page</a>. Locate the themes folder in the invoicekingpro plugin folder, generally located:</p>
            <pre>/wp-content/plugins/invoicekingpro/themes/</pre>
            <p>Unzip the downloaded zip file and upload the entire folder into the themes folder mentioned above.</p>
            <p>Once uploaded, return to this page and your theme will be present in the PDF Theme dropdown to the left. Choose the theme and save the options. Next time you generate a report, the theme you have chosen will be used.</p>
            <p>The ability to upload the zip file straight from here will be added soon</p>
        </div>
        
        <?php /****** FAQ ******/ ?>
        <div id="invkp_faq" class="invkp_section">
            <h2>FAQ</h2>
            <h4>Q. I change my columns in the settings yet my existing invoices don't show the change. Why?</h4>
            <p>This is done to protect your existing invoices from future updates. You may run your invoices with a specific set of information
            for a year then update the set which wouldn't match your old invoices. If this wasn't in place your old invoice data would be lost.</p>
            <h4>Q. Some fields I see used around the plugin don't exist on my invoice. How do I set them?</h4>
            <p>Not all themes will use every field available in the system. For example, the default theme that comes with the plugin does not use the clients
            contact details (address, phone, email) on the invoice, nor does the due date field get used. Some of the other themes available may use these.</p>
            <h4>Q. I made a change to my invoice and viewed the PDF to see the update, but it hasn't changed. Why?</h4>
            <p>If your viewing this in the browser then its highly possible that your viewing a cached version. You should get your new version simply by
            refreshing the page. Depending on what browser you use will determine how it displays the PDF to you. You shouldn't have this problem if you download it.</p>
            <h4>I get an error saying the PDF can't be saved due to write permissions on the server. What do I do?</h4>
            <p>The plugin needs your permission to save the PDFs you generate to the output folder in the plugins folder. To do this, you are required to
            update the outputs permissions to be writable. Please see <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">the wordpress help page</a> to carry this out.</p>

            <h4>Found an issue? Post your issue on the <a href="http://wordpress.org/support/plugin/invoice-king-pro" target="_blank">support forums</a>. If you would prefer, please email your concern to <a href="mailto:plugins@kingpro.me">plugins@kingpro.me</a></h4>   
        </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    jQuery('.invkp_tabs a').click(function() {
        jQuery(this).parent().children('a.active').removeClass('active');
        jQuery('.invkp_sections').find('div.invkp_section.active').removeClass('active');
        
        var active = jQuery(this).attr('class');
        jQuery(this).addClass('active');
        jQuery("#"+active).addClass('active');
    });
</script>