<?php

add_option('bbinv_theme_version_ttl', current_time('timestamp'));
add_option('bbinv_theme_version_update_notice', '');

function bbinv_theme_version_check() {
    //if (get_option('bbinv_theme_version_ttl') < current_time('timestamp')) :
        $dir = str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/';
        $folder = scandir($dir);
        $exclude = array('.', '..', '.DS_Store', 'default');
        foreach ($folder as $f) {
            if (!in_array($f, $exclude)) {
                if (!file_exists($dir.$f."/version")) $version = '1.0';
                else $version = file_get_contents($dir.$f."/version");
                
                $stable_version = get_option('bbinv_safe_theme');
                
                if ((float) $version < (float) $stable_version) {
                    $header[] = "Accept: text/html;q=0.9,text/plain;q=0.8";
                    $header[] = "Cache-Control: max-age=0";
                    $header[] = "Connection: keep-alive";
                    $header[] = "Keep-Alive: 300";
                    $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
                    $header[] = "Accept-Language: en-us,en;q=0.5";

                    $opts = array('http'=>array('method'=>"GET",'header'=>implode('\r\n',$header)."\r\n"."Referer: ".$_SERVER['HTTP_HOST']."\r\n",'user_agent'=> "Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) Gecko/2008092313 Ubuntu/9.25 (jaunty) Firefox/3.8"));

                    $context = stream_context_create($opts);
                    $current_version = file_get_contents("http://kingpro.me/version_check.php?theme=".$f."&v=".$version, false, $context);
                    
                    if (!empty($current_version)) {
                        $notice = get_option('bbinv_theme_version_update_notice');
                        $notice[$f] = __("There is a new version of the invoice theme", "bbinvtext")." <strong>".ucfirst(str_replace(array('-', '_'), ' ', $f))."</strong>. ".__("Please make sure you up have the latest version of BB Agency Invoices before downloading and installing your updated theme.", "bbinvtext")." <a href='http://kingpro.me/download_theme.php?k=".$current_version."'>".__("Please download the new version here", "bbinvtext")."</a><br /><br /><a href='".admin_url('admin.php?action=bbinvcheckthemeversion&theme='.$f)."'>".__("Have you just installed this?", "bbinvtext")."</a>";
                        update_option('bbinv_theme_version_update_notice', $notice);
                    }
                }
            }
        }
        
        update_option('bbinv_theme_version_ttl', strtotime('+1 day', current_time('timestamp')));
    //endif;
}
add_action('admin_init', 'bbinv_theme_version_check');


function bbinvcheckthemeversion_admin_action()
{
    $theme = $_GET['theme'];
    $dir = str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/';
    if (!file_exists($dir.$theme."/version")) $version = '1.0';
    else $version = file_get_contents($dir.$theme."/version");
    
    $stable_version = get_option('bbinv_safe_theme');
    if ($stable_version <= $version) {
        $notice = get_option('bbinv_theme_version_update_notice');
        unset($notice[$theme]);
        update_option('bbinv_theme_version_update_notice', $notice);
    }
    
    header("Location: ".$_SERVER['HTTP_REFERER']);
    
}
add_action( 'admin_action_bbinvcheckthemeversion', 'bbinvcheckthemeversion_admin_action' );

function bbinv_check_page($hook) {
    global $current_screen;
    $bbinv_pages = array('king-pro-plugins_page_bb-agency-invoices', "toplevel_page_kpp_menu");
    $pages_req = array('post.php', 'post-new.php', 'edit.php');
    
    if (in_array($hook, $bbinv_pages)) return true;
    if (in_array($hook, $pages_req) && ($current_screen->post_type == 'bbinv_invoices' || $current_screen->post_type == 'bbinv_clients')) return true;
    return false;
}

$theme_notices = get_option('bbinv_theme_version_update_notice');

if (!empty($theme_notices)) {
    function bbinv_theme_notice() {
        global $theme_notices;
        foreach ($theme_notices as $note) : ?>
        <div class="error">
            <p><?php _e( $note, 'bbinvtext' ); ?></p>
        </div>
        <?php endforeach;
    }
    add_action( 'admin_notices', 'bbinv_theme_notice' );
}

// Check if sent email
if (isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'bbinv_invoices' && isset($_REQUEST['send'])) {
    function bbinv_admin_notice() {
        if ($_REQUEST['send'] == 0) :
        ?>
        <div class="error">
            <p><?php _e( "Your email could not be sent. Please check the email address and try again.", 'bbinvtext' ); ?></p>
        </div>
        <?php
        elseif ($_REQUEST['send'] == 1) :
        ?>
        <div class="updated">
            <p><?php _e( "Email sent successfully!", 'bbinvtext' ); ?></p>
        </div>
        <?php
        endif;
    }
    add_action( 'admin_notices', 'bbinv_admin_notice' );
}

// Default Options
add_option( 'bbinv_revenue_currency', '$' );
add_option( 'bbinv_comany_name', '' );
add_option( 'bbinv_address', '' );
add_option( 'bbinv_suburb', '' );
add_option( 'bbinv_state', '' );
add_option( 'bbinv_postcode', '' );
add_option( 'bbinv_phone', '' );
add_option( 'bbinv_email', '' );
add_option( 'bbinv_bcc', 0 );

add_option( 'bbinv_invoice_type', 'INVOICE' );
add_option( 'bbinv_paid_invoice_type', 'RECEIPT' );
add_option( 'bbinv_paid_watermark', 'PAID' );
add_option( 'bbinv_invoice_no_label', 'Invoice #:' );
add_option( 'bbinv_po_label', 'PO:' );
add_option( 'bbinv_attn_name_label', 'Attn:' );
add_option( 'bbinv_tax_label', 'GST' );
add_option( 'bbinv_tax_value', '0' );
add_option( 'bbinv_subtotal_label', 'Subtotal' );
add_option( 'bbinv_discount_label', 'Discount' );
add_option( 'bbinv_total_label', 'Total' );

add_option( 'bbinv_open_content_1', '');
add_option( 'bbinv_open_content_2', '');

add_option( 'bbinv_invoice_no_gen', 'Ymd##' );
add_option( 'bbinv_invoice_no_gen_last', '' );
add_option( 'bbinv_invoice_no_gen_incr', '0' );
add_option( 'bbinv_invoice_last_post_id', '0' );
add_option( 'bbinv_pdf_filename', '{pid}');

add_option( 'bbinv_columns', '' );
add_option( 'bbinv_column_types', '' );
add_option( 'bbinv_column_widths', '' );

add_option( 'bbinv_theme', 'default' );

add_option( 'bbinv_calculate_rows', '' );
add_option( 'bbinv_calculate_operators', '' );

add_option( 'bbinv_calculate_subtotal', 'Total ($)');

add_option( 'bbinv_from', get_bloginfo('name'));
add_option( 'bbinv_from_email', get_bloginfo('admin_email'));
add_option( 'bbinv_email_subject', "{{invoice_type}} ".__('From', "bbinvtext")." ".get_bloginfo('name'));
$message = __("Hi", "bbinvtext")." {{client_name}},"."\r\n\r\n";
$message .= __('Please find attached the', "bbinvtext").' {{invoice_type}} '.__('for $', "bbinvtext").'{{invoice_total}}.'."\r\n\r\n";

$message .= __("Regards,", "bbinvtext")."\r\n";
$message .= get_bloginfo('name');
add_option( 'bbinv_email_message', $message);
add_option( 'bbinv_paid_email_subject', "{{invoice_type}} ".__("From", "bbinvtext")." ".get_bloginfo('name'));
$message = __("Hi", "bbinvtext")." {{client_name}},"."\r\n\r\n";
$message .= __('Please find attached the', "bbinvtext").' {{invoice_type}} '.__('for', "bbinvtext").' {{invoice_number_label}}{{invoice_number}}.'."\r\n";
$message .= __('Thank you for your business', "bbinvtext")."\r\n\r\n";

$message .= __("Regards,", "bbinvtext")."\r\n";
$message .= get_bloginfo('name');
add_option( 'bbinv_paid_email_message', $message);

$pdf_theme = get_option('bbinv_theme');
if (file_exists(str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/theme_options.php')) {
    include str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/theme_options.php';
}

// Register Post types for invoicing
function bbinv_create_post_type() {
    register_post_type( 'bbinv_invoices',
        array(
            'labels' => array(
                'name' => __( 'Invoices', "bbinvtext"),
                'singular_name' => __( 'Invoice', "bbinvtext"),
                'all_items'=> __('All Invoices', "bbinvtext"),
                'edit_item'=>__('Edit Invoice', "bbinvtext"),
                'update_item'=>__('Update Invoice', "bbinvtext"),
                'add_new_item'=>__('Add New Invoice', "bbinvtext"),
                'new_item_name'=>__('New Invoice', "bbinvtext"),
                'add_new' => __('Add New', "bbinvtext"),
                'new_item' => __('New Invoice', "bbinvtext"),
                'view_item' => __('View Invoice', "bbinvtext"),
                'search_items' => __('Search Invoices', "bbinvtext"),
                'not_found' =>  __('No invoices found', "bbinvtext"),
                'not_found_in_trash' => __('No invoices found in Trash', "bbinvtext"), 
                'parent_item_colon' => '',
                'menu_name' => __('Invoices', "bbinvtext")
            ),
            'public' => true,
            'exclude_from_search' => true,
            'menu_position' => 5,
            'supports' => array('title')
        )
    );
    
    register_post_type( 'bbinv_clients',
        array(
            'labels' => array(
                'name' => __( 'Clients', "bbinvtext"),
                'singular_name' => __( 'Client', "bbinvtext"),
                'all_items'=>__('All Clients', "bbinvtext"),
                'edit_item'=>__('Edit Client', "bbinvtext"),
                'update_item'=>__('Update Client', "bbinvtext"),
                'add_new_item'=>__('Add New Client', "bbinvtext"),
                'new_item_name'=>__('New Client', "bbinvtext"),
                'add_new' => __('Add New', "bbinvtext"),
                'new_item' => __('New Client', "bbinvtext"),
                'view_item' => __('View Client', "bbinvtext"),
                'search_items' => __('Search Clients', "bbinvtext"),
                'not_found' =>  __('No clients found', "bbinvtext"),
                'not_found_in_trash' => __('No clients found in Trash', "bbinvtext"), 
                'parent_item_colon' => '',
                'menu_name' => __('Clients', "bbinvtext")
            ),
            'public' => true,
            'exclude_from_search' => true,
            'show_in_menu' => 'edit.php?post_type=bbinv_invoices',
            'supports' => array('title')
        )
    );
}
add_action( 'init', 'bbinv_create_post_type' );

// Styling for the custom post type icon
function wpt_bbinv_icons() {
    ?>
    <style type="text/css" media="screen">
        #toplevel_page_kpp_menu .wp-menu-image {
            background: url(<?= plugins_url('/images/kpp-icon_16x16_sat.png', dirname(__FILE__)) ?>) no-repeat center center !important;
        }
	#toplevel_page_kpp_menu:hover .wp-menu-image, #toplevel_page_kpp_menu.wp-has-current-submenu .wp-menu-image {
            background: url(<?= plugins_url('/images/kpp-icon_16x16.png', dirname(__FILE__)) ?>) no-repeat center center !important;
        }
        #toplevel_page_kpp_menu .wp-menu-image:before {display: none;}
	#icon-options-general.icon32-posts-kpp_menu, #icon-kpp_menu.icon32 {background: url(<?= plugins_url('/images/kpp-icon_32x32.png', dirname(__FILE__)) ?>) no-repeat;}
        
        #menu-posts-bbinv_invoices .wp-menu-image {
            background: url(<?= plugins_url('/images/bbinv-icon_16x16_sat.png', dirname(__FILE__)) ?>) no-repeat center center !important;
        }
        #menu-posts-bbinv_invoices .wp-menu-image:before {display: none;}
	#menu-posts-bbinv_invoices:hover .wp-menu-image, #menu-posts-bbinv_invoices.wp-has-current-submenu .wp-menu-image {
            background: url(<?= plugins_url('/images/bbinv-icon_16x16.png', dirname(__FILE__)) ?>) no-repeat center center !important;
        }
        #menu-posts-bbinv_invoices .wp-menu-image:before {display: none;}
	#icon-edit.icon32-posts-bbinv_invoices {background: url(<?= plugins_url('/images/bbinv-icon_32x32_sat.png', dirname(__FILE__)) ?>) no-repeat;}
    </style>
<?php }
add_action( 'admin_head', 'wpt_bbinv_icons' );

// Columns in custom post types
function bbinv_edit_invoice_columns( $columns ) {

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Description' , "bbinvtext"),
        'invoice_no' => __('Invoice No.', "bbinvtext"),
        'client' => __( 'Client' , "bbinvtext"),
        'total' => __( 'Total' , "bbinvtext"),
        'invoice_paid' => __( 'Paid?' , "bbinvtext"),
        'date' => __( 'Date Created' , "bbinvtext"),
        'invoice_sent' => __('Last Sent', "bbinvtext")
    );

    return $columns;
}
add_filter( 'manage_edit-bbinv_invoices_columns', 'bbinv_edit_invoice_columns' ) ;

// Update column data with custom data
function bbinv_columns($column_name, $ID) {
    switch ($column_name) {
        case 'invoice_no' :
            // Get invoice number
            $invoice_no = get_post_meta($ID, 'bbinv_invoice_no');
            echo $invoice_no[0];
            break;
        
        case 'client' :
            // Get client details via client id and output company name and email
            $client = get_post_meta($ID, 'bbinv_client_company');
            echo $client[0];
            break;
            
        case 'total' :
            // Get total of invoice
            $total = get_post_meta($ID, 'bbinv_total');
            echo $total[0];
            break;
        
        case 'invoice_paid' :
            // Count invoices attached to this client
            $paid = get_post_meta($ID, 'bbinv_paid_invoice');
            if (!empty($paid) && $paid[0] == 1) echo "<a href='".admin_url('admin.php?action=bbinvmarkunpaid&post='.$ID)."'><img src='".str_replace('includes', 'images', plugin_dir_url(__FILE__))."tick.png' /></a>";
            else echo "<a href='".admin_url('admin.php?action=bbinvmarkpaid&post='.$ID)."'><img src='".str_replace('includes', 'images', plugin_dir_url(__FILE__))."cross.png' /></a>";
            break;
            
        case 'invoice_sent' :
            // Display last sent date
            $sent = get_post_meta($ID, 'bbinv_sent_invoice');
            if (!empty($sent) && is_numeric($sent[0]) && $sent[0] > 0) echo date("F j, Y g:i a", $sent[0]);
            else echo "Not Sent";
            break;
    }
}
add_action('manage_bbinv_invoices_posts_custom_column', 'bbinv_columns', 10, 2); 

// Columns in custom post types
function bbinv_edit_client_columns( $columns ) {

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __( 'Client Name', "bbinvtext" ),
        'title' => __( 'Company Name' , "bbinvtext"),
        'email' => __( 'Attached Email' , "bbinvtext"),
        'invoices_attached' => __( 'Invoices' , "bbinvtext"),
    );

    return $columns;
}
add_filter( 'manage_edit-bbinv_clients_columns', 'bbinv_edit_client_columns' ) ;

// Update column data with custom data
function bbinv_client_columns($column_name, $ID) {
    switch ($column_name) {
        case 'name' :
            // Get client email
            $email = get_post_meta($ID, 'bbinv_client_attn_name');
            echo $email[0];
            break;
        
        case 'email' :
            // Get client email
            $email = get_post_meta($ID, 'bbinv_client_email');
            echo $email[0];
            break;
            
        case 'invoices_attached' :
            // Count invoices attached to this client
            $invoices = query_posts(array('post_type'=>'bbinv_invoices', 'meta_key'=>'bbinv_client_link', 'meta_value'=>$ID));
            echo count($invoices);
            break;
    }
}
add_action('manage_bbinv_clients_posts_custom_column', 'bbinv_client_columns', 10, 2); 

function bbinv_action_row($actions){
    global $post;
   if ($post->post_type == "bbinv_invoices"){
      //remove what you don't need
       unset( $actions['inline hide-if-no-js'] );
       unset( $actions['view'] );
       
       $paid_invoice = get_post_meta($post->ID, 'bbinv_paid_invoice');
       if (!empty($paid_invoice) && $paid_invoice[0] == 1)
           $actions['markunpaid'] = '<a href=\''.admin_url('admin.php?action=bbinvmarkunpaid&post='.$post->ID).'\'>'.__("Mark Unpaid", "bbinvtext").'</a>';
       else
           $actions['markpaid'] = '<a href=\''.admin_url('admin.php?action=bbinvmarkpaid&post='.$post->ID).'\'>'.__("Mark Paid", "bbinvtext").'</a>';
       $actions['viewpdf'] = '<a href=\''.admin_url('admin.php?action=bbinvviewpdf&post='.$post->ID).'\' target=\'blank\'>'.__('View PDF', "bbinvtext").'</a>';
       $actions['emailpdf'] = '<a href=\''.admin_url('admin.php?action=bbinvemailpdf&post='.$post->ID).'\'>'.__('Email PDF', "bbinvtext").'</a>';
   }
   return $actions;
}
add_filter('post_row_actions','bbinv_action_row');

function bbinvmarkpaid_admin_action()
{
    $post_id = $_GET['post'];
    update_post_meta( $post_id, 'bbinv_paid_invoice', 1 );
    
    header("Location: ".$_SERVER['HTTP_REFERER']);
    
}
add_action( 'admin_action_bbinvmarkpaid', 'bbinvmarkpaid_admin_action' );

function bbinvmarkunpaid_admin_action()
{
    $post_id = $_GET['post'];
    update_post_meta( $post_id, 'bbinv_paid_invoice', 0 );
    
    header("Location: ".$_SERVER['HTTP_REFERER']);
    
}
add_action( 'admin_action_bbinvmarkunpaid', 'bbinvmarkunpaid_admin_action' );

function bbinvviewpdf_admin_action()
{
    include plugin_dir_path(__FILE__)."../packages/fpdf/fpdf.php";
    $post_id = $_GET['post'];
    $invoice = query_posts(array('post_type'=>'bbinv_invoices', 'p'=>$post_id));
    $invoice[0]->custom = bbinv_return_fields($post_id);
    if (isset($invoice[0]->custom['bbinv_column'][0])) {
        if (!is_array($invoice[0]->custom['bbinv_column'][0]))
            $rows = unserialize($invoice[0]->custom['bbinv_column'][0]); 
        else $rows = $invoice[0]->custom['bbinv_column'][0];
    }
    $columns = unserialize($invoice[0]->custom['bbinv_columns'][0]);
    $column_types = unserialize($invoice[0]->custom['bbinv_column_types'][0]);
    $column_widths = unserialize($invoice[0]->custom['bbinv_column_widths'][0]);
    $currency_symbol = get_option('bbinv_revenue_currency');
    $tax_label = get_option("bbinv_tax_label");
    $subtotal_label = get_option("bbinv_subtotal_label");
    $discount_label = get_option("bbinv_discount_label");
    $total_label = get_option("bbinv_total_label");
    $paid_label = get_option("bbinv_paid_invoice_type");
    $paid_watermark = get_option('bbinv_paid_watermark');
    //print_r($invoice[0]->custom);die;
    
    if ($invoice[0]->custom['bbinv_paid_invoice'][0] == 1) {
        $paid = 'paid_';
        $invoice_type = $paid_label;
    } else {
        $paid = '';
        $invoice_type = $invoice[0]->custom['bbinv_invoice_type'][0];
    }
    
    $search = array(
        '{{invoice_type}}',
        '{{invoice_number_label}}',
        '{{invoice_number}}',
        '{{invoice_date}}',
        '{{invoice_due_date}}',
        '{{client_company_name}}',
        '{{client_name}}',
        '{{invoice_total}}',
    );
    $replace = array(
        $invoice_type,
        $invoice[0]->custom['bbinv_invoice_no_label'][0],
        $invoice[0]->custom['bbinv_invoice_no'][0],
        $invoice[0]->custom['bbinv_date'][0],
        $invoice[0]->custom['bbinv_due_date'][0],
        $invoice[0]->custom['bbinv_selected_client_company'][0],
        $invoice[0]->custom['bbinv_selected_client_attn'][0],
        $invoice[0]->custom['bbinv_total'][0],
    );
    
    $invoice[0]->custom['bbinv_open_content_1'][0] = str_replace($search, $replace, $invoice[0]->custom['bbinv_open_content_1'][0]);
    $invoice[0]->custom['bbinv_open_content_2'][0] = str_replace($search, $replace, $invoice[0]->custom['bbinv_open_content_2'][0]);
    
    do_action('bbinv_additional_pdf_invoice_data');
    
    $pdf_theme = get_option('bbinv_theme');
    if (file_exists(str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/pdf.php'))
        include str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/pdf.php';
    else
        include 'pdf.php';
    
    header("Location: ".str_replace("includes/","",plugin_dir_url(__FILE__))."outputs/".$pdf_filename.".pdf");
    
}
add_action( 'admin_action_bbinvviewpdf', 'bbinvviewpdf_admin_action' );

function bbinvemailpdf_admin_action()
{
    global $wpdb;
    $post_id = $_GET['post'];
    $invoice = query_posts(array('post_type'=>'bbinv_invoices', 'p'=>$post_id));
    $invoice[0]->custom = bbinv_return_fields($post_id);
    
    if (isset($invoice[0]->custom['bbinv_selected_client_email'][0]) && $invoice[0]->custom['bbinv_selected_client_email'][0] !== '')
        $email = $invoice[0]->custom['bbinv_selected_client_email'][0];
    elseif (isset($invoice[0]->custom['bbinv_client_email'][0]) && $invoice[0]->custom['bbinv_client_email'][0] !== '')
        $email = $invoice[0]->custom['bbinv_client_email'][0];
    else header("Location: ".$_SERVER['HTTP_REFERER']."&send=0");
    
    $name = $invoice[0]->custom['bbinv_selected_client_attn'][0];
    
    $from = get_option('bbinv_from');
    $from_email = get_option('bbinv_from_email');
    
    $headers = "From: ".$from." <".$from_email.">";
    if (get_option('bbinv_bcc')) $headers = "Bcc: ".$from." <".$from_email.">";
    
    include plugin_dir_path(__FILE__)."../packages/fpdf/fpdf.php";
    if (isset($invoice[0]->custom['bbinv_column'][0])) {
        if (!is_array($invoice[0]->custom['bbinv_column'][0]))
            $rows = unserialize($invoice[0]->custom['bbinv_column'][0]); 
        else $rows = $invoice[0]->custom['bbinv_column'][0];
    }
    $columns = unserialize($invoice[0]->custom['bbinv_columns'][0]);
    $column_types = unserialize($invoice[0]->custom['bbinv_column_types'][0]);
    $column_widths = unserialize($invoice[0]->custom['bbinv_column_widths'][0]);
    $currency_symbol = get_option('bbinv_revenue_currency');
    $tax_label = get_option("bbinv_tax_label");
    $subtotal_label = get_option("bbinv_subtotal_label");
    $discount_label = get_option("bbinv_discount_label");
    $total_label = get_option("bbinv_total_label");
    $paid_label = get_option("bbinv_paid_invoice_type");
    $paid_watermark = get_option('bbinv_paid_watermark');
    
    if ($invoice[0]->custom['bbinv_paid_invoice'][0] == 1) {
        $paid = 'paid_';
        $invoice_type = $paid_label;
    } else {
        $paid = '';
        $invoice_type = $invoice[0]->custom['bbinv_invoice_type'][0];
    }
    
    $search = array(
        '{{invoice_type}}',
        '{{invoice_number_label}}',
        '{{invoice_number}}',
        '{{invoice_date}}',
        '{{invoice_due_date}}',
        '{{client_company_name}}',
        '{{client_name}}',
        '{{invoice_total}}',
    );
    $replace = array(
        $invoice_type,
        $invoice[0]->custom['bbinv_invoice_no_label'][0],
        $invoice[0]->custom['bbinv_invoice_no'][0],
        $invoice[0]->custom['bbinv_date'][0],
        $invoice[0]->custom['bbinv_due_date'][0],
        $invoice[0]->custom['bbinv_selected_client_company'][0],
        $invoice[0]->custom['bbinv_selected_client_attn'][0],
        $invoice[0]->custom['bbinv_total'][0],
    );
    
    $invoice[0]->custom['bbinv_open_content_1'][0] = str_replace($search, $replace, $invoice[0]->custom['bbinv_open_content_1'][0]);
    $invoice[0]->custom['bbinv_open_content_2'][0] = str_replace($search, $replace, $invoice[0]->custom['bbinv_open_content_2'][0]);
    
    do_action('bbinv_additional_pdf_invoice_data');
    
    $pdf_theme = get_option('bbinv_theme');
    if (file_exists(str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/pdf.php'))
        include str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/pdf.php';
    else
        include 'pdf.php';
    
    $attachments = array(str_replace("includes/","",plugin_dir_path(__FILE__))."outputs/".$pdf_filename.".pdf");
    
    $subject = str_replace($search, $replace, get_option('bbinv_'.$paid.'email_subject'));
    $message = str_replace($search, $replace, get_option('bbinv_'.$paid.'email_message'));
    
    global $bbinv_attachments;
    $bbinv_attachments = $attachments;
    
    do_action('bbinv_additional_email_pdf_invoice_data', $attachments, $post_id);
    $attachments = $bbinv_attachments;

    if (@wp_mail($email, $subject, $message, $headers, $attachments)) $sent = 1; else $sent = 0;
    
    if ($sent == 1) {
        update_post_meta( $post_id, 'bbinv_sent_invoice', current_time('timestamp') );
    }
    
    header("Location: ".$_SERVER['HTTP_REFERER']."&send=".$sent);
}
add_action( 'admin_action_bbinvemailpdf', 'bbinvemailpdf_admin_action' );

// Update title field to become URL field
function bbinv_title_text_input( $title ){
    global $post;
    if($post->post_type == 'bbinv_invoices') 
        return $title = __('Invoice Description for your convenience', "bbinvtext");
    if($post->post_type == 'bbinv_clients')
        return $title = __('Company Name', "bbinvtext");
    return $title;
}
add_filter( 'enter_title_here', 'bbinv_title_text_input' );

// Update Feature Image to become Advert Image
function bbinv_change_meta_boxes()
{
    add_meta_box('postinvoicedatadiv', __('Invoice', "bbinvtext"), 'bbinv_post_invoice', 'bbinv_invoices', 'advanced', 'high');
    add_meta_box('postinvoiceoptionsdatadiv', __('Invoice Options', "bbinvtext"), 'bbinv_post_invoice_options', 'bbinv_invoices', 'side', 'high');
    add_meta_box('postinvoiceclientdatadiv', __('Client Details', "bbinvtext"), 'bbinv_post_client_details', 'bbinv_invoices', 'side', 'high');
    
    add_meta_box('postclientdatadiv', __('Client Details', "bbinvtext"), 'bbinv_post_client', 'bbinv_clients', 'advanced', 'high');
    do_action('bbinv_additional_invoice_meta_box');
}
add_action('do_meta_boxes', 'bbinv_change_meta_boxes');

// Output stats for post
function bbinv_post_client($object, $box) {
    global $wpdb;
    global $post;
    $custom_fields = bbinv_return_client_fields();
    $inv_nonce = wp_create_nonce(basename(__FILE__));
    echo '<input type="hidden" name="bbinv_client_meta_box_nonce" value="'.$inv_nonce.'" />';
    echo '<div><label>'.__('Company Contact Name', "bbinvtext").':</label><input type="text" name="bbinv_client_attn_name" value="'.$custom_fields['bbinv_client_attn_name'][0].'" /></div>';
    echo '<div><label>'.__('Address', "bbinvtext").':</label><input type="text" name="bbinv_client_address" value="'.$custom_fields['bbinv_client_address'][0].'" /></div>';
    echo '<div class="left"><label>'.__('Suburb', "bbinvtext").':</label><input type="text" name="bbinv_client_suburb" value="'.$custom_fields['bbinv_client_suburb'][0].'" /></div>';
    echo '<div class="right"><label>'.__('State', "bbinvtext").':</label><input type="text" name="bbinv_client_state" value="'.$custom_fields['bbinv_client_state'][0].'" /></div>';
    echo '<div class="left"><label>'.__('Postcode/Zip', "bbinvtext").':</label><input type="text" name="bbinv_client_postcode" value="'.$custom_fields['bbinv_client_postcode'][0].'" /></div>';
    echo '<div class="left"><label>'.__('Email', "bbinvtext").':</label><input type="text" name="bbinv_client_email" value="'.$custom_fields['bbinv_client_email'][0].'" /></div>';
    echo '<div class="right"><label>'.__('Phone', "bbinvtext").':</label><input type="text" name="bbinv_client_phone" value="'.$custom_fields['bbinv_client_phone'][0].'" /></div>';
}

function bbinv_post_invoice($object, $box) {
    global $wpdb;
    global $post;
    $custom_fields = bbinv_return_fields();
    $inv_nonce = wp_create_nonce(basename(__FILE__));
    
    if (isset($custom_fields["bbinv_column"][0])) {
        if (!is_array($custom_fields['bbinv_column'][0]))
            $rows = unserialize($custom_fields['bbinv_column'][0]); 
        else $rows = $custom_fields['bbinv_column'][0];
    }
    
    if (isset($custom_fields["bbinv_columns"][0])) $columns = unserialize($custom_fields["bbinv_columns"][0]);
    else $columns = get_option('bbinv_columns');
    //$columns = get_option('bbinv_columns');
    if (isset($custom_fields["bbinv_column_types"][0])) $column_types = unserialize($custom_fields["bbinv_column_types"][0]);
    else $column_types = get_option('bbinv_column_types');
    //$column_types = get_option('bbinv_column_types');
    if (isset($custom_fields["bbinv_column_widths"][0])) $column_widths = unserialize($custom_fields["bbinv_column_widths"][0]);
    else $column_widths = get_option('bbinv_column_widths');
    //$column_widths = get_option('bbinv_column_widths');
    if (isset($custom_fields["bbinv_calculate_rows"][0])) $calc_cols = unserialize($custom_fields["bbinv_calculate_rows"][0]);
    else $calc_cols = get_option('bbinv_calculate_rows');
    //$calc_cols = get_option('bbinv_calculate_rows');
    if (isset($custom_fields["bbinv_calculate_operators"][0])) $calc_ops = unserialize($custom_fields["bbinv_calculate_operators"][0]);
    else $calc_ops = get_option('bbinv_calculate_operators');
    //$calc_ops = get_option('bbinv_calculate_operators');
    if (isset($custom_fields["bbinv_calculate_subtotal"][0])) $subtotal_col = $custom_fields["bbinv_calculate_subtotal"][0];
    else $subtotal_col = get_option('bbinv_calculate_subtotal');
    if (!isset($calc_ops[0])) {
        echo __("Please visit the settings page to setup your defaults for the invoice", "bbinvtext");
        echo "<br /><br />";
        echo "<a href='".admin_url('/admin.php?page=bb-agency-invoices')."'>".__('Settings Page', "bbinvtext")."</a>";
        return;
    }
    $row_calc = array();
    if (!empty($calc_cols) && $calc_ops[0] <> '') :
        for ($c=0;$c<count($calc_cols); $c++) :
            $row_calc[] = preg_replace('~[^\p{L}\p{N}]++~u', '', $calc_cols[$c]);
            if (isset($calc_ops[$c]) && $calc_ops[$c] <> '') :
                $row_calc[] = $calc_ops[$c];
            endif;
        endfor; 
    endif;
    $json_calc = json_encode($row_calc);
    $pdf_theme = get_option('bbinv_theme');
    if (file_exists(str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/invoice_html.php'))
        include str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/invoice_html.php';
    else
        include('invoice_html.php');
    
    echo "<input type='hidden' name='bbinv_invoice_no_gen_incr' value='".$custom_fields["bbinv_invoice_no_gen_incr"][0]."' />"; 
    echo "<input type='hidden' name='bbinv_invoice_last_post_id' value='".$custom_fields["bbinv_invoice_last_post_id"][0]."' />";
    echo "<input type='hidden' name='bbinv_invoice_no_gen_last' value='".$custom_fields["bbinv_invoice_no_gen_last"][0]."' />";
}

function bbinv_post_invoice_options($object, $box) {
    global $wpdb;
    global $post;
    $discount_type = get_post_meta( $post->ID, 'bbinv_discount_type', true );
    $discount_value = get_post_meta( $post->ID, 'bbinv_discount_value', true );
    $tax_percentage = get_post_meta( $post->ID, 'bbinv_tax_percentage', true ) ? get_post_meta( $post->ID, 'bbinv_tax_percentage', true ) : get_option('bbinv_tax_value');
    $paid_invoice = get_post_meta( $post->ID, 'bbinv_paid_invoice', true );
    $checked = '';
    if ($paid_invoice == "1") $checked = ' checked';
    
    do_action('bbinv_additional_post_invoice_options');
    
    echo '<div class="misc-pub-section"><label for="bbinv_discount_type">'.__('Discount Type', "bbinvtext").':</label>';
    echo '<select name="bbinv_discount_type" id="bbinv_discount_type" style="float: right;margin-top: -3px;"><option value="percentage"', ($discount_type == 'percentage') ? ' selected' : '' , '>'.__('Percentage', "bbinvtext").'</option><option value="setvalue"', ($discount_type == 'setvalue') ? ' selected' : '' , '>'.__('Set Value', "bbinvtext").'</option></select>';
    echo '</div>';
    echo '<div class="misc-pub-section"><label for="bbinv_discount_value">'.__('Discount Value', "bbinvtext").':</label>';
    echo '<input type="text" name="bbinv_discount_value" id="bbinv_discount_value" value="'. $discount_value .'" class="calculate" style="width: 70px;float: right;margin-top: -3px;" />';
    echo '</div>';
    echo '<div class="misc-pub-section"><label for="bbinv_tax_percentage">'.__('Tax Percentage', "bbinvtext").':</label>';
    echo '<input type="text" name="bbinv_tax_percentage" id="bbinv_tax_percentage" value="'. $tax_percentage .'" class="calculate" style="width: 70px;float: right;margin-top: -3px;" />';
    echo '</div>';
    echo '<div class="misc-pub-section"><label for="bbinv_paid_invoice">'.__('Invoice Paid?', "bbinvtext").':</label>';
    echo '<input type="hidden" name="bbinv_paid_invoice" id="bbinv_paid_invoice_no" value="0" />';
    echo '<input type="checkbox" name="bbinv_paid_invoice" id="bbinv_paid_invoice_yes" value="1"'.$checked.' style="float: right;margin-top: 2px;" />';
    echo '</div>';
}

function bbinv_post_client_details($object, $box) {
    global $wpdb;
    global $post;
    
    $output = array();
    $output['bbinv_client_link'] = (get_post_meta( $post->ID, 'bbinv_client_link' ) ? get_post_meta( $post->ID, 'bbinv_client_link' ) : array(''));
    $output['bbinv_selected_client_company'] = (get_post_meta( $post->ID, 'bbinv_selected_client_company' ) ? get_post_meta( $post->ID, 'bbinv_selected_client_company' ) : array(''));
    $output['bbinv_selected_client_attn'] = (get_post_meta( $post->ID, 'bbinv_selected_client_attn' ) ? get_post_meta( $post->ID, 'bbinv_selected_client_attn' ) : array(''));
    $output['bbinv_selected_client_address'] = (get_post_meta( $post->ID, 'bbinv_selected_client_address' ) ? get_post_meta( $post->ID, 'bbinv_selected_client_address' ) : array(''));
    $output['bbinv_selected_client_suburb'] = (get_post_meta( $post->ID, 'bbinv_selected_client_suburb' ) ? get_post_meta( $post->ID, 'bbinv_selected_client_suburb' ) : array(''));
    $output['bbinv_selected_client_state'] = (get_post_meta( $post->ID, 'bbinv_selected_client_state' ) ? get_post_meta( $post->ID, 'bbinv_selected_client_state' ) : array(''));
    $output['bbinv_selected_client_postcode'] = (get_post_meta( $post->ID, 'bbinv_selected_client_postcode' ) ? get_post_meta( $post->ID, 'bbinv_selected_client_postcode' ) : array(''));
    $output['bbinv_selected_client_email'] = (get_post_meta( $post->ID, 'bbinv_selected_client_email' ) ? get_post_meta( $post->ID, 'bbinv_selected_client_email' ) : array(''));
    $output['bbinv_selected_client_phone'] = (get_post_meta( $post->ID, 'bbinv_selected_client_phone' ) ? get_post_meta( $post->ID, 'bbinv_selected_client_phone' ) : array(''));
    
    $clients = query_posts(array('post_type'=>'bbinv_clients', "posts_per_page"=>-1));
    $client_dropdown = '';
    foreach ($clients as $client) {
        $client_val = array(
            'id'=>$client->ID,
            'company_name'=>$client->post_title,
            'attn_name'=>get_post_meta( $client->ID, 'bbinv_client_attn_name'),
            'address'=>get_post_meta( $client->ID, 'bbinv_client_address'),
            'suburb'=>get_post_meta( $client->ID, 'bbinv_client_suburb'),
            'state'=>get_post_meta( $client->ID, 'bbinv_client_state'),
            'postcode'=>get_post_meta( $client->ID, 'bbinv_client_postcode'),
            'email'=>get_post_meta( $client->ID, 'bbinv_client_email'),
            'phone'=>get_post_meta( $client->ID, 'bbinv_client_phone')
        );
        
        $selected = '';
        //echo $client->ID." = ".$output['bbinv_client_link'][0]."<br />";
        if ($client->ID == $output['bbinv_client_link'][0]) $selected = ' selected';
        $client_dropdown .= "<option value='".json_encode($client_val)."'".$selected.">".$client->post_title."</option>";
    }
    
    echo '<div class="misc-pub-section" style="margin-bottom: 20px;"><label for="bbinv_select_client">'.__('Use Existing Client Details', "bbinvtext").':</label>';
    echo '<select id="bbinv_select_client" style="display:block;width: 100%;"><option value="">-- '.__('SELECT', "bbinvtext").' --</option>'.$client_dropdown.'</select>';
    echo '</div>';
    echo '<input type="hidden" name="bbinv_client_link" id="bbinv_client_link" value="'.$output['bbinv_client_link'][0].'" />';
    echo '<div style="margin-bottom: 10px;"><label for="bbinv_selected_client_company">'.__('Company Name', "bbinvtext").':</label>';
    echo '<input type="text" name="bbinv_selected_client_company" id="bbinv_selected_client_company" value="'.$output['bbinv_selected_client_company'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="bbinv_selected_client_company">'.__('Company Contact Name', "bbinvtext").':</label>';
    echo '<input type="text" name="bbinv_selected_client_attn" id="bbinv_selected_client_attn" value="'.$output['bbinv_selected_client_attn'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="bbinv_selected_client_address">'.__('Address', "bbinvtext").':</label>';
    echo '<input type="text" name="bbinv_selected_client_address" id="bbinv_selected_client_address" value="'.$output['bbinv_selected_client_address'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="bbinv_selected_client_suburb">'.__('Suburb', "bbinvtext").':</label>';
    echo '<input type="text" name="bbinv_selected_client_suburb" id="bbinv_selected_client_suburb" value="'.$output['bbinv_selected_client_suburb'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="bbinv_selected_client_state">'.__('State', "bbinvtext").':</label>';
    echo '<input type="text" name="bbinv_selected_client_state" id="bbinv_selected_client_state" value="'.$output['bbinv_selected_client_state'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="bbinv_selected_client_postcode">'.__('Postcode', "bbinvtext").':</label>';
    echo '<input type="text" name="bbinv_selected_client_postcode" id="bbinv_selected_client_postcode" value="'.$output['bbinv_selected_client_postcode'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="bbinv_selected_client_email">'.__('Email', "bbinvtext").':</label>';
    echo '<input type="text" name="bbinv_selected_client_email" id="bbinv_selected_client_email" value="'.$output['bbinv_selected_client_email'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="bbinv_selected_client_phone">'.__('Phone', "bbinvtext").':</label>';
    echo '<input type="text" name="bbinv_selected_client_phone" id="bbinv_selected_client_phone" value="'.$output['bbinv_selected_client_phone'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;">';
    echo '<input type="button" id="insert_details" class="button" value="'.__('Insert Details', "bbinvtext").'" /><input type="button" id="save_client" class="button-primary right" value="'.__('Save Client', "bbinvtext").'" />';
    echo '</div>';
}

// Process the custom metabox fields
function bbinv_save_custom_fields( ) {
	global $post;	
        
        // verify nonce
        if (!isset($_POST['bbinv_meta_box_nonce']) || !wp_verify_nonce($_POST['bbinv_meta_box_nonce'], basename(__FILE__))) {
            return;
        }
	
	if( $_POST ) {
            if (isset($_POST['bbinv_company_name']))
                update_post_meta( $post->ID, 'bbinv_company_name', $_POST['bbinv_company_name'] );
            if (isset($_POST['bbinv_invoice_type']))
                update_post_meta( $post->ID, 'bbinv_invoice_type', $_POST['bbinv_invoice_type'] );
            
            if (isset($_POST['bbinv_address']))
                update_post_meta( $post->ID, 'bbinv_address', $_POST['bbinv_address'] );
            if (isset($_POST['bbinv_suburb']))
                update_post_meta( $post->ID, 'bbinv_suburb', $_POST['bbinv_suburb'] );
            if (isset($_POST['bbinv_state']))
                update_post_meta( $post->ID, 'bbinv_state', $_POST['bbinv_state'] );
            if (isset($_POST['bbinv_postcode']))
                update_post_meta( $post->ID, 'bbinv_postcode', $_POST['bbinv_postcode'] );
            if (isset($_POST['bbinv_date']))
                update_post_meta( $post->ID, 'bbinv_date', ($_POST['bbinv_date'] != '' ? $_POST['bbinv_date'] : the_date()) );
            if (isset($_POST['bbinv_due_date']))
                update_post_meta( $post->ID, 'bbinv_due_date', $_POST['bbinv_due_date'] );
            if (isset($_POST['bbinv_invoice_no_label']))
                update_post_meta( $post->ID, 'bbinv_invoice_no_label', $_POST['bbinv_invoice_no_label'] );
            if (isset($_POST['bbinv_invoice_no']))
                update_post_meta( $post->ID, 'bbinv_invoice_no', $_POST['bbinv_invoice_no'] );
            if (isset($_POST['bbinv_po_label']))
                update_post_meta( $post->ID, 'bbinv_po_label', $_POST['bbinv_po_label'] );
            if (isset($_POST['bbinv_po']))
                update_post_meta( $post->ID, 'bbinv_po', $_POST['bbinv_po'] );
            if (isset($_POST['bbinv_payment_terms']))
                update_post_meta( $post->ID, 'bbinv_payment_terms', $_POST['bbinv_payment_terms'] );
            
            if (isset($_POST['bbinv_phone']))
                update_post_meta( $post->ID, 'bbinv_phone', $_POST['bbinv_phone'] );
            if (isset($_POST['bbinv_email']))
                update_post_meta( $post->ID, 'bbinv_email', $_POST['bbinv_email'] );
            if (isset($_POST['bbinv_add_detail']))
                update_post_meta( $post->ID, 'bbinv_add_detail', $_POST['bbinv_add_detail'] );
            if (isset($_POST['bbinv_attn_name_label']))
                update_post_meta( $post->ID, 'bbinv_attn_name_label', $_POST['bbinv_attn_name_label'] );
            if (isset($_POST['bbinv_attn_name']))
                update_post_meta( $post->ID, 'bbinv_attn_name', $_POST['bbinv_attn_name'] );
            if (isset($_POST['bbinv_client_company']))
                update_post_meta( $post->ID, 'bbinv_client_company', $_POST['bbinv_client_company'] );
            if (isset($_POST['bbinv_client_address']))
                update_post_meta( $post->ID, 'bbinv_client_address', $_POST['bbinv_client_address'] );
            if (isset($_POST['bbinv_client_suburb']))
                update_post_meta( $post->ID, 'bbinv_client_suburb', $_POST['bbinv_client_suburb'] );
            if (isset($_POST['bbinv_client_state']))
                update_post_meta( $post->ID, 'bbinv_client_state', $_POST['bbinv_client_state'] );
            if (isset($_POST['bbinv_client_postcode']))
                update_post_meta( $post->ID, 'bbinv_client_postcode', $_POST['bbinv_client_postcode'] );
            if (isset($_POST['bbinv_client_email']))
                update_post_meta( $post->ID, 'bbinv_client_email', $_POST['bbinv_client_email'] );
            if (isset($_POST['bbinv_client_phone']))
                update_post_meta( $post->ID, 'bbinv_client_phone', $_POST['bbinv_client_phone'] );
            
            if (isset($_POST['bbinv_client_link']))
                update_post_meta( $post->ID, 'bbinv_client_link', $_POST['bbinv_client_link'] );
            if (isset($_POST['bbinv_selected_client_company']))
                update_post_meta( $post->ID, 'bbinv_selected_client_company', $_POST['bbinv_selected_client_company'] );
            if (isset($_POST['bbinv_selected_client_attn']))
                update_post_meta( $post->ID, 'bbinv_selected_client_attn', $_POST['bbinv_selected_client_attn'] );
            if (isset($_POST['bbinv_selected_client_address']))
                update_post_meta( $post->ID, 'bbinv_selected_client_address', $_POST['bbinv_selected_client_address'] );
            if (isset($_POST['bbinv_selected_client_suburb']))
                update_post_meta( $post->ID, 'bbinv_selected_client_suburb', $_POST['bbinv_selected_client_suburb'] );
            if (isset($_POST['bbinv_selected_client_state']))
                update_post_meta( $post->ID, 'bbinv_selected_client_state', $_POST['bbinv_selected_client_state'] );
            if (isset($_POST['bbinv_selected_client_postcode']))
                update_post_meta( $post->ID, 'bbinv_selected_client_postcode', $_POST['bbinv_selected_client_postcode'] );
            if (isset($_POST['bbinv_selected_client_email']))
                update_post_meta( $post->ID, 'bbinv_selected_client_email', $_POST['bbinv_selected_client_email'] );
            if (isset($_POST['bbinv_selected_client_phone']))
                update_post_meta( $post->ID, 'bbinv_selected_client_phone', $_POST['bbinv_selected_client_phone'] );
            
            if (isset($_POST['bbinv_column']))
                update_post_meta( $post->ID, 'bbinv_column', $_POST['bbinv_column'] );//mysql_real_escape_string(serialize(
            if (isset($_POST['bbinv_subtotal']))
                update_post_meta( $post->ID, 'bbinv_subtotal', $_POST['bbinv_subtotal'] );
            if (isset($_POST['bbinv_discount']))
                update_post_meta( $post->ID, 'bbinv_discount', $_POST['bbinv_discount'] );
            if (isset($_POST['bbinv_gst']))
                update_post_meta( $post->ID, 'bbinv_gst', $_POST['bbinv_gst'] );
            if (isset($_POST['bbinv_total']))
                update_post_meta( $post->ID, 'bbinv_total', $_POST['bbinv_total'] );
            
            if (isset($_POST['bbinv_columns']))
                update_post_meta( $post->ID, 'bbinv_columns', $_POST['bbinv_columns'] );
            if (isset($_POST['bbinv_column_types']))
                update_post_meta( $post->ID, 'bbinv_column_types', $_POST['bbinv_column_types'] );
            if (isset($_POST['bbinv_column_widths']))
                update_post_meta( $post->ID, 'bbinv_column_widths', $_POST['bbinv_column_widths'] );
            if (isset($_POST['bbinv_calculate_rows']))
                update_post_meta( $post->ID, 'bbinv_calculate_rows', $_POST['bbinv_calculate_rows'] );
            if (isset($_POST['bbinv_calculate_operators']))
                update_post_meta( $post->ID, 'bbinv_calculate_operators', $_POST['bbinv_calculate_operators'] );
            if (isset($_POST['bbinv_calculate_subtotal']))
                update_post_meta( $post->ID, 'bbinv_calculate_subtotal', $_POST['bbinv_calculate_subtotal'] );
            
            if (isset($_POST['bbinv_open_content_1']))
                update_post_meta( $post->ID, 'bbinv_open_content_1', $_POST['bbinv_open_content_1'] );
            if (isset($_POST['bbinv_open_content_2']))
                update_post_meta( $post->ID, 'bbinv_open_content_2', $_POST['bbinv_open_content_2'] );
            
            if (isset($_POST['bbinv_discount_type']))
                update_post_meta( $post->ID, 'bbinv_discount_type', $_POST['bbinv_discount_type'] );
            if (isset($_POST['bbinv_discount_value']))
                update_post_meta( $post->ID, 'bbinv_discount_value', $_POST['bbinv_discount_value'] );
            if (isset($_POST['bbinv_tax_percentage']))
                update_post_meta( $post->ID, 'bbinv_tax_percentage', $_POST['bbinv_tax_percentage'] );
            if (isset($_POST['bbinv_paid_invoice']))
                update_post_meta( $post->ID, 'bbinv_paid_invoice', $_POST['bbinv_paid_invoice'] );
            
            if (isset($_POST['bbinv_custom_data_1']))
                update_post_meta( $post->ID, 'bbinv_custom_data_1', $_POST['bbinv_custom_data_1'] );
            if (isset($_POST['bbinv_custom_data_2']))
                update_post_meta( $post->ID, 'bbinv_custom_data_2', $_POST['bbinv_custom_data_2'] );
            if (isset($_POST['bbinv_custom_data_3']))
                update_post_meta( $post->ID, 'bbinv_custom_data_3', $_POST['bbinv_custom_data_3'] );
            if (isset($_POST['bbinv_custom_data_4']))
                update_post_meta( $post->ID, 'bbinv_custom_data_4', $_POST['bbinv_custom_data_4'] );
            if (isset($_POST['bbinv_custom_data_5']))
                update_post_meta( $post->ID, 'bbinv_custom_data_5', $_POST['bbinv_custom_data_5'] );
            if (isset($_POST['bbinv_custom_data_6']))
                update_post_meta( $post->ID, 'bbinv_custom_data_6', $_POST['bbinv_custom_data_6'] );
            if (isset($_POST['bbinv_custom_data_7']))
                update_post_meta( $post->ID, 'bbinv_custom_data_7', $_POST['bbinv_custom_data_7'] );
            if (isset($_POST['bbinv_custom_data_8']))
                update_post_meta( $post->ID, 'bbinv_custom_data_8', $_POST['bbinv_custom_data_8'] );
            if (isset($_POST['bbinv_custom_data_9']))
                update_post_meta( $post->ID, 'bbinv_custom_data_9', $_POST['bbinv_custom_data_9'] );
            if (isset($_POST['bbinv_custom_data_10']))
                update_post_meta( $post->ID, 'bbinv_custom_data_10', $_POST['bbinv_custom_data_10'] );
            
            do_action('bbinv_additional_save_custom_fields', $_POST);
	}
}

add_action( 'save_post', 'bbinv_save_custom_fields' );

// Created function that returns generated invoice number. This function is modified to be used in new post created manually and from cron job.
function bbinv_generate_invoice_no($post_id) {		

    // Get option bbinv_<metakey> values (workaround to get it to work from cron job)
    global $wpdb;
    $results = $wpdb->get_results("SELECT option_name,option_value FROM {$wpdb->prefix}options WHERE option_name LIKE '%bbinv_%'", ARRAY_A);
    foreach ($results as $key=>$value) {
            $bbinv_option[$value[option_name]] = $value[option_value];
    }

    // Generate date numbers 
    // (mysql2date is also used in WP's get_the_date function to generate date digits. But WP uses the post date, we want to use the current date)
    $bbinv_invoice_no_gen = mysql2date($bbinv_option['bbinv_invoice_no_gen'], date("Y-m-d H:i:s")); // ( Ymd## becomes 20130902## on the september second in 2013)
    // Get last generated date number 
    $bbinv_invoice_no_gen_last = $bbinv_option['bbinv_invoice_no_gen_last'];	// (last invoice nr without the ## changed into an incremental number)
    // Get last invoice post id
    $bbinv_invoice_last_post_id = $bbinv_option['bbinv_invoice_last_post_id'];	// (last post-ID used for an invoice)

    // Check if last generated date number is the same as new.
    // if true: add increment to invoice number, increased by 1
    // if false: add increment to invoice number, reset it to 1
    $bbinv_invoice_no_gen_incr = $bbinv_option['bbinv_invoice_no_gen_incr'];
    if ( $bbinv_invoice_no_gen == $bbinv_invoice_no_gen_last ) {

                    // Check if not in same post/invoice
                    if ($bbinv_invoice_last_post_id != $post_id) {
                                    // Get invoice increment number and increase
                                    $bbinv_invoice_no_gen_incr++;
                    }
    } else {
                    // Set invoice increment number to 1
                    $bbinv_invoice_no_gen_incr = '1';
    }

    // Update options
    bbinv_update_option( 'bbinv_invoice_no_gen_incr', $bbinv_invoice_no_gen_incr );
    bbinv_update_option( 'bbinv_invoice_last_post_id', $post_id );
    bbinv_update_option( 'bbinv_invoice_no_gen_last', $bbinv_invoice_no_gen );

    // Add zero's to increment number if needed
    $CountX = substr_count($bbinv_invoice_no_gen, '#');	// Number of x characters in generated invoice number 
    while (strlen($bbinv_invoice_no_gen_incr) < $CountX) {	
                    $bbinv_invoice_no_gen_incr = '0'.$bbinv_invoice_no_gen_incr;		
    }

    // Split incr number into chars
    $bbinv_invoice_no_gen_incr_chars = preg_split('//', $bbinv_invoice_no_gen_incr, -1, PREG_SPLIT_NO_EMPTY);
    $bbinv_invoice_no_gen_chars = preg_split('//', $bbinv_invoice_no_gen, -1, 0);

    // Generate final invoice number. Replace x characters with increment number (from right to left). 
    $j = count($bbinv_invoice_no_gen_incr_chars); // set array length of increment number
    $bbinv_invoice_no = '';
    for ($i = count($bbinv_invoice_no_gen_chars); $i > 0; $i--) {

                    if (preg_match("/#/i", $bbinv_invoice_no_gen_chars[$i]) ) {
                                    $j--;
                                    $bbinv_invoice_no_gen_chars[$i] = $bbinv_invoice_no_gen_incr_chars[$j];
                                    $bbinv_invoice_no = $bbinv_invoice_no_gen_incr_chars[$j].$bbinv_invoice_no;
                    } else {
                                    $bbinv_invoice_no = $bbinv_invoice_no_gen_chars[$i].$bbinv_invoice_no;
                    }
    }

    return $bbinv_invoice_no;
} // function end

function bbinv_gen_filename($invoice_data) {
    $pdf_filename = get_option('bbinv_pdf_filename');
    if (strstr($pdf_filename, '{pid}')) {
        $search = array(
            '{pid}',
            '{company_name}',
            '{date}',
            '{inv_id}',
            ' '
        );
        
        $replace = array(
            $invoice_data->ID,
            str_replace(array(' '), array('-'), strtolower($invoice_data->custom['bbinv_company_name'][0])),
            date('Y-m-d', current_time('timestamp')),
            $invoice_data->custom['bbinv_invoice_no'][0],
            '-'
        );
        
        $filename = str_replace($search, $replace, $pdf_filename);
    } elseif ($pdf_filename == '') {
        $filename = $invoice_data->ID;
    } else {
        $search = array(
            '{pid}',
            '{company_name}',
            '{date}',
            '{inv_id}',
            ' '
        );
        
        $replace = array(
            $invoice_data->ID,
            str_replace(array(' '), array('-'), strtolower($invoice_data->custom['bbinv_company_name'][0])),
            date('Y-m-d', current_time('timestamp')),
            $invoice_data->custom['bbinv_invoice_no'][0],
            '-'
        );
        
        $filename = str_replace($search, $replace, $pdf_filename).'-'.$invoice_data->custom['bbinv_invoice_no'][0];
    }
    
    $filename = preg_replace('/[^A-Za-z0-9\-_]/', '', $filename); // Removes special chars.

    return preg_replace('/-+/', '-', $filename);
}

// Our own update_option() function, which works from cron jobs.
function bbinv_update_option( $option, $newvalue ) {	
	global $wpdb;
	$result = $wpdb->update( $wpdb->options, array( 'option_value' => $newvalue ), array( 'option_name' => $option ) );
	return true;
}


// Process the custom metabox fields
function bbinv_return_fields( $id = NULL ) {
	global $post;
        if (is_null($id)) $id = $post->ID;
	$output = array();
        $output['bbinv_company_name'] = get_post_meta( $id, 'bbinv_company_name' );
        if (!isset($output['bbinv_company_name'][0])) $output['bbinv_company_name'][0] = get_option('bbinv_company_name');
        
        $output['bbinv_invoice_type'] = get_post_meta( $id, 'bbinv_invoice_type' );
        if (!isset($output['bbinv_invoice_type'][0])) $output['bbinv_invoice_type'][0] = get_option('bbinv_invoice_type');
        
        $output['bbinv_address'] = get_post_meta( $id, 'bbinv_address' );
        if (!isset($output['bbinv_address'][0])) $output['bbinv_address'][0] = get_option('bbinv_address');
        $output['bbinv_suburb'] = get_post_meta( $id, 'bbinv_suburb' );
        if (!isset($output['bbinv_suburb'][0])) $output['bbinv_suburb'][0] = get_option('bbinv_suburb');
        $output['bbinv_state'] = get_post_meta( $id, 'bbinv_state' );
        if (!isset($output['bbinv_state'][0])) $output['bbinv_state'][0] = get_option('bbinv_state');
        $output['bbinv_postcode'] = get_post_meta( $id, 'bbinv_postcode' );
        if (!isset($output['bbinv_postcode'][0])) $output['bbinv_postcode'][0] = get_option('bbinv_postcode');
        $output['bbinv_date'][0] = (get_post_meta( $id, 'bbinv_date', true ) ? get_post_meta( $id, 'bbinv_date', true ) : get_the_date());
        $output['bbinv_due_date'][0] = (get_post_meta( $id, 'bbinv_due_date' ) ? get_post_meta( $id, 'bbinv_due_date' ) : array(''));
        $output['bbinv_invoice_no_label'] = get_post_meta( $id, 'bbinv_invoice_no_label' );
        if (!isset($output['bbinv_invoice_no_label'][0])) $output['bbinv_invoice_no_label'][0] = get_option('bbinv_invoice_no_label');
        
        $output['bbinv_invoice_no'] = (get_post_meta( $id, 'bbinv_invoice_no' ) ? get_post_meta( $id, 'bbinv_invoice_no' ) : array( bbinv_generate_invoice_no($id) ) );
        
        $output['bbinv_po_label'] = get_post_meta( $id, 'bbinv_po_label' );
        if (!isset($output['bbinv_po_label'][0])) $output['bbinv_po_label'][0] = get_option('bbinv_po_label');
        $output['bbinv_po'] = (get_post_meta( $id, 'bbinv_po' ) ? get_post_meta( $id, 'bbinv_po' ) : array(''));
        $output['bbinv_tax_label'] = get_post_meta( $id, 'bbinv_tax_label' );
        if (!isset($output['bbinv_tax_label'][0])) $output['bbinv_tax_label'][0] = get_option('bbinv_tax_label');
        
        $output['bbinv_tax_value'] = get_post_meta( $id, 'bbinv_tax_value' );
        if (!isset($output['bbinv_tax_value'][0])) $output['bbinv_tax_value'][0] = get_option('bbinv_tax_value');
        
        $output['bbinv_subtotal_label'] = get_post_meta( $id, 'bbinv_subtotal_label' );
        if (!isset($output['bbinv_subtotal_label'][0])) $output['bbinv_subtotal_label'][0] = get_option('bbinv_subtotal_label');
        
        $output['bbinv_discount_label'] = get_post_meta( $id, 'bbinv_discount_label' );
        if (!isset($output['bbinv_discount_label'][0])) $output['bbinv_discount_label'][0] = get_option('bbinv_discount_label');
        
        $output['bbinv_total_label'] = get_post_meta( $id, 'bbinv_total_label' );
        if (!isset($output['bbinv_total_label'][0])) $output['bbinv_total_label'][0] = get_option('bbinv_total_label');
        
        $output['bbinv_payment_terms'] = (get_post_meta( $id, 'bbinv_payment_terms' ) ? get_post_meta( $id, 'bbinv_payment_terms' ) : array(''));
        
        $output['bbinv_phone'] = get_post_meta( $id, 'bbinv_phone' );
        if (!isset($output['bbinv_phone'][0])) $output['bbinv_phone'][0] = get_option('bbinv_phone');
        $output['bbinv_email'] = get_post_meta( $id, 'bbinv_email' );
        if (!isset($output['bbinv_email'][0])) $output['bbinv_email'][0] = get_option('bbinv_email');
        $output['bbinv_add_detail'] = get_post_meta( $id, 'bbinv_add_detail' );
        if (!isset($output['bbinv_add_detail'][0])) $output['bbinv_add_detail'][0] = get_option('bbinv_add_detail');
        $output['bbinv_attn_name_label'] = get_post_meta( $id, 'bbinv_attn_name_label' );
        if (!isset($output['bbinv_attn_name_label'][0])) $output['bbinv_attn_name_label'][0] = get_option('bbinv_attn_name_label');
        $output['bbinv_attn_name'] = (get_post_meta( $id, 'bbinv_attn_name' ) ? get_post_meta( $id, 'bbinv_attn_name' ) : array(''));
        $output['bbinv_client_company'] = (get_post_meta( $id, 'bbinv_client_company' ) ? get_post_meta( $id, 'bbinv_client_company' ) : array(''));
        $output['bbinv_client_address'] = (get_post_meta( $id, 'bbinv_client_address' ) ? get_post_meta( $id, 'bbinv_client_address' ) : array(''));
        $output['bbinv_client_suburb'] = (get_post_meta( $id, 'bbinv_client_suburb' ) ? get_post_meta( $id, 'bbinv_client_suburb' ) : array(''));
        $output['bbinv_client_state'] = (get_post_meta( $id, 'bbinv_client_state' ) ? get_post_meta( $id, 'bbinv_client_state' ) : array(''));
        $output['bbinv_client_postcode'] = (get_post_meta( $id, 'bbinv_client_postcode' ) ? get_post_meta( $id, 'bbinv_client_postcode' ) : array(''));
        $output['bbinv_client_email'] = (get_post_meta( $id, 'bbinv_client_email' ) ? get_post_meta( $id, 'bbinv_client_email' ) : array(''));
        $output['bbinv_client_phone'] = (get_post_meta( $id, 'bbinv_client_phone' ) ? get_post_meta( $id, 'bbinv_client_phone' ) : array(''));
        
        $output['bbinv_selected_client_company'] = (get_post_meta( $id, 'bbinv_selected_client_company' ) ? get_post_meta( $id, 'bbinv_selected_client_company' ) : array(''));
        $output['bbinv_selected_client_attn'] = (get_post_meta( $id, 'bbinv_selected_client_attn' ) ? get_post_meta( $id, 'bbinv_selected_client_attn' ) : array(''));
        $output['bbinv_selected_client_address'] = (get_post_meta( $id, 'bbinv_selected_client_address' ) ? get_post_meta( $id, 'bbinv_selected_client_address' ) : array(''));
        $output['bbinv_selected_client_suburb'] = (get_post_meta( $id, 'bbinv_selected_client_suburb' ) ? get_post_meta( $id, 'bbinv_selected_client_suburb' ) : array(''));
        $output['bbinv_selected_client_state'] = (get_post_meta( $id, 'bbinv_selected_client_state' ) ? get_post_meta( $id, 'bbinv_selected_client_state' ) : array(''));
        $output['bbinv_selected_client_postcode'] = (get_post_meta( $id, 'bbinv_selected_client_postcode' ) ? get_post_meta( $id, 'bbinv_selected_client_postcode' ) : array(''));
        $output['bbinv_selected_client_email'] = (get_post_meta( $id, 'bbinv_selected_client_email' ) ? get_post_meta( $id, 'bbinv_selected_client_email' ) : array(''));
        $output['bbinv_selected_client_phone'] = (get_post_meta( $id, 'bbinv_selected_client_phone' ) ? get_post_meta( $id, 'bbinv_selected_client_phone' ) : array(''));
        
        $output['bbinv_column'] = (get_post_meta( $id, 'bbinv_column' ) ? get_post_meta( $id, 'bbinv_column' ) : array(''));
        $output['bbinv_subtotal'] = (get_post_meta( $id, 'bbinv_subtotal' ) ? get_post_meta( $id, 'bbinv_subtotal' ) : array(''));
        $output['bbinv_discount'] = (get_post_meta( $id, 'bbinv_discount' ) ? get_post_meta( $id, 'bbinv_discount' ) : array(''));
        $output['bbinv_gst'] = (get_post_meta( $id, 'bbinv_gst' ) ? get_post_meta( $id, 'bbinv_gst' ) : array(''));
        $output['bbinv_total'] = (get_post_meta( $id, 'bbinv_total' ) ? get_post_meta( $id, 'bbinv_total' ) : array(''));
        $output['bbinv_open_content_1'] = get_post_meta( $id, 'bbinv_open_content_1' );
        if (!isset($output['bbinv_open_content_1'][0])) $output['bbinv_open_content_1'][0] = get_option('bbinv_open_content_1');
        $output['bbinv_open_content_2'] = get_post_meta( $id, 'bbinv_open_content_2' );
        if (!isset($output['bbinv_open_content_2'][0])) $output['bbinv_open_content_2'][0] = get_option('bbinv_open_content_2');
        
        $output['bbinv_columns'] = get_post_meta( $id, 'bbinv_columns' );
        if (!isset($output['bbinv_columns'][0])) $output['bbinv_columns'][0] = serialize(get_option('bbinv_columns'));
        $output['bbinv_column_types'] = get_post_meta( $id, 'bbinv_column_types' );
        if (!isset($output['bbinv_column_types'][0])) $output['bbinv_column_types'][0] = serialize(get_option('bbinv_column_types'));
        $output['bbinv_column_widths'] = get_post_meta( $id, 'bbinv_column_widths' );
        if (!isset($output['bbinv_column_widths'][0])) $output['bbinv_column_widths'][0] = serialize(get_option('bbinv_column_widths'));
        $output['bbinv_calculate_rows'] = get_post_meta( $id, 'bbinv_calculate_rows' );
        if (!isset($output['bbinv_calculate_rows'][0])) $output['bbinv_calculate_rows'][0] = serialize(get_option('bbinv_calculate_rows'));
        $output['bbinv_calculate_operators'] = get_post_meta( $id, 'bbinv_calculate_operators' );
        if (!isset($output['bbinv_calculate_operators'][0])) $output['bbinv_calculate_operators'][0] = serialize(get_option('bbinv_calculate_operators'));
        $output['bbinv_calculate_subtotal'] = get_post_meta( $id, 'bbinv_calculate_subtotal' );
        if (!isset($output['bbinv_calculate_subtotal'][0])) $output['bbinv_calculate_subtotal'][0] = get_option('bbinv_calculate_subtotal');
        
        $output['bbinv_custom_data_1'] = (get_post_meta( $id, 'bbinv_custom_data_1' ) ? get_post_meta( $id, 'bbinv_custom_data_1' ) : array(''));
        $output['bbinv_custom_data_2'] = (get_post_meta( $id, 'bbinv_custom_data_2' ) ? get_post_meta( $id, 'bbinv_custom_data_2' ) : array(''));
        $output['bbinv_custom_data_3'] = (get_post_meta( $id, 'bbinv_custom_data_3' ) ? get_post_meta( $id, 'bbinv_custom_data_3' ) : array(''));
        $output['bbinv_custom_data_4'] = (get_post_meta( $id, 'bbinv_custom_data_4' ) ? get_post_meta( $id, 'bbinv_custom_data_4' ) : array(''));
        $output['bbinv_custom_data_5'] = (get_post_meta( $id, 'bbinv_custom_data_5' ) ? get_post_meta( $id, 'bbinv_custom_data_5' ) : array(''));
        $output['bbinv_custom_data_6'] = (get_post_meta( $id, 'bbinv_custom_data_6' ) ? get_post_meta( $id, 'bbinv_custom_data_6' ) : array(''));
        $output['bbinv_custom_data_7'] = (get_post_meta( $id, 'bbinv_custom_data_7' ) ? get_post_meta( $id, 'bbinv_custom_data_7' ) : array(''));
        $output['bbinv_custom_data_8'] = (get_post_meta( $id, 'bbinv_custom_data_8' ) ? get_post_meta( $id, 'bbinv_custom_data_8' ) : array(''));
        $output['bbinv_custom_data_9'] = (get_post_meta( $id, 'bbinv_custom_data_9' ) ? get_post_meta( $id, 'bbinv_custom_data_9' ) : array(''));
        $output['bbinv_custom_data_10'] = (get_post_meta( $id, 'bbinv_custom_data_10' ) ? get_post_meta( $id, 'bbinv_custom_data_10' ) : array(''));
        
        $output['bbinv_paid_invoice'] = (get_post_meta( $id, 'bbinv_paid_invoice' ) ? get_post_meta( $id, 'bbinv_paid_invoice' ) : array('0'));
        
        return $output;
}

function bbinv_save_client_custom_fields( ) {
	global $post;	
        
        // verify nonce
        if (!isset($_POST['bbinv_client_meta_box_nonce']) || !wp_verify_nonce($_POST['bbinv_client_meta_box_nonce'], basename(__FILE__))) {
            return;
        }
	
	if( $_POST ) {
            if (isset($_POST['bbinv_client_company_name']))
                update_post_meta( $post->ID, 'bbinv_client_company_name', $_POST['bbinv_client_company_name'] );
            if (isset($_POST['bbinv_client_attn_name']))
                update_post_meta( $post->ID, 'bbinv_client_attn_name', $_POST['bbinv_client_attn_name'] );
            if (isset($_POST['bbinv_client_address']))
                update_post_meta( $post->ID, 'bbinv_client_address', $_POST['bbinv_client_address'] );
            if (isset($_POST['bbinv_client_suburb']))
                update_post_meta( $post->ID, 'bbinv_client_suburb', $_POST['bbinv_client_suburb'] );
            if (isset($_POST['bbinv_client_state']))
                update_post_meta( $post->ID, 'bbinv_client_state', $_POST['bbinv_client_state'] );
            if (isset($_POST['bbinv_client_postcode']))
                update_post_meta( $post->ID, 'bbinv_client_postcode', $_POST['bbinv_client_postcode'] );
            if (isset($_POST['bbinv_client_email']))
                update_post_meta( $post->ID, 'bbinv_client_email', $_POST['bbinv_client_email'] );
            if (isset($_POST['bbinv_client_phone']))
                update_post_meta( $post->ID, 'bbinv_client_phone', $_POST['bbinv_client_phone'] );
            
	}
}

add_action( 'save_post', 'bbinv_save_client_custom_fields' );

// Process the custom metabox fields
function bbinv_return_client_fields( $id = NULL ) {
	global $post;
        if (is_null($id)) $id = $post->ID;
	$output = array();
        
        $output['bbinv_client_company_name'] = (get_post_meta( $id, 'bbinv_client_company_name' ) ? get_post_meta( $post->ID, 'bbinv_client_company_name' ) : array(''));
        $output['bbinv_client_attn_name'] = (get_post_meta( $id, 'bbinv_client_attn_name' ) ? get_post_meta( $post->ID, 'bbinv_client_attn_name' ) : array(''));
        $output['bbinv_client_address'] = (get_post_meta( $id, 'bbinv_client_address' ) ? get_post_meta( $post->ID, 'bbinv_client_address' ) : array(''));
        $output['bbinv_client_suburb'] = (get_post_meta( $id, 'bbinv_client_suburb' ) ? get_post_meta( $post->ID, 'bbinv_client_suburb' ) : array(''));
        $output['bbinv_client_state'] = (get_post_meta( $id, 'bbinv_client_state' ) ? get_post_meta( $post->ID, 'bbinv_client_state' ) : array(''));
        $output['bbinv_client_postcode'] = (get_post_meta( $id, 'bbinv_client_postcode' ) ? get_post_meta( $post->ID, 'bbinv_client_postcode' ) : array(''));
        $output['bbinv_client_email'] = (get_post_meta( $id, 'bbinv_client_email' ) ? get_post_meta( $post->ID, 'bbinv_client_email' ) : array(''));
        $output['bbinv_client_phone'] = (get_post_meta( $id, 'bbinv_client_phone' ) ? get_post_meta( $post->ID, 'bbinv_client_phone' ) : array(''));
        
        return $output;
}

// Remove the Permalinks
function bbinv_perm($return, $id, $new_title, $new_slug){
    global $post;
    if(isset($post->post_type) && $post->post_type == 'bbinv_invoices') return '';
    return $return;
}
add_filter('get_sample_permalink_html', 'bbinv_perm', '', 4);

// Dashboard Widget

function bbinv_enqueue($hook) {
    if (bbinv_check_page($hook)) :
        wp_register_style( 'bbinv_jquery_ui', plugins_url('css/jquery-ui.css', dirname(__FILE__)), false, '1.9.2' );
        wp_register_style( 'bbinv_css', plugins_url('css/bb-agency-invoices-styles.css', dirname(__FILE__)), false, '1.0.0' );
        $theme = get_option('bbinv_theme');
        wp_register_style( 'bbinv_'.$theme.'_css', plugins_url('themes/'.$theme.'/styles.css', dirname(__FILE__)), false, '1.0.0' );
        wp_register_style( 'fontawesome', plugins_url('css/font-awesome.min.css', dirname(__FILE__)), false, '3.2.1');

        wp_enqueue_style( 'bbinv_jquery_ui' );
        wp_enqueue_style( 'bbinv_css' );
        wp_enqueue_style( 'bbinv_'.$theme.'_css' );
        wp_enqueue_style( 'fontawesome' );
        wp_enqueue_style( 'thickbox' );

        wp_enqueue_script( 'jquery-ui-datepicker');
        wp_register_script( 'bbinv_elastic', plugins_url( '/js/jquery.elastic.source.js', dirname(__FILE__) ), array('jquery'), '1.6.11');
        wp_register_script( 'bbinv_admin_js', plugins_url( '/js/bb-agency-invoices-admin-functions.js', dirname(__FILE__) ), array('jquery'), '1.0.0');

        wp_enqueue_script( 'bbinv_elastic' );
        wp_enqueue_script( 'bbinv_admin_js' );
        wp_enqueue_script( 'thickbox' );

        // in javascript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
        wp_localize_script( 'bbinv_admin_js', 'bbinv_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'bbinv_ajaxnonce' => wp_create_nonce( 'bbinvN0nc3' ) ) );
        
        // For attachments plugin
        wp_enqueue_media();
    endif;
}
add_action( 'admin_enqueue_scripts', 'bbinv_enqueue' );

// Add King Pro Plugins Section
if(!function_exists('find_kpp_menu_item')) {
  function find_kpp_menu_item($handle, $sub = false) {
    if(!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
      return false;
    }
    global $menu, $submenu;
    $check_menu = $sub ? $submenu : $menu;
    if(empty($check_menu)) {
      return false;
    }
    foreach($check_menu as $k => $item) {
      if($sub) {
        foreach($item as $sm) {
          if($handle == $sm[2]) {
            return true;
          }
        }
      } 
      else {
        if($handle == $item[2]) {
          return true;
        }
      }
    }
    return false;
  }
}

function bbinv_add_parent_page() {
  if(!find_kpp_menu_item('kpp_menu')) {
    add_menu_page('King Pro Plugins','King Pro Plugins', 'manage_options', 'kpp_menu', 'kpp_menu_page');
  }
//  if(!function_exists('remove_submenu_page')) {
//    unset($GLOBALS['submenu']['kpp_menu'][0]);
//  }
//  else {
//    remove_submenu_page('kpp_menu','kpp_menu');
//  }
  
  add_submenu_page('kpp_menu', 'BB Agency Invoices', 'BB Agency Invoices', 'manage_options', 'bb-agency-invoices', 'bbinv_settings_output');
}
add_action('admin_menu', 'bbinv_add_parent_page');

if(!function_exists('kpp_menu_page')) {
    function kpp_menu_page() {
        include 'screens/kpp.php';
    }
}

function on_update_bbinv_column_widths($new_value, $old_value = 0) {
    $total_columns = count($new_value);
    $area_by = 100;
    
    // Deal with filled in values
    for ($v=0;$v<count($new_value);$v++) {
        if ($new_value[$v] <> '') {
            $total_columns--;
            $new_value[$v] = preg_replace("/[^0-9.]/", "", $new_value[$v]);
            $area_by -= $new_value[$v];
        }
    }
    
    // Deal with empty values
    for ($v=0;$v<count($new_value);$v++) {
        if ($new_value[$v] == '') {
            $new_value[$v] = round($area_by / $total_columns, 3);
        }
    }
    
    return $new_value;
}
add_action('pre_update_option_bbinv_column_widths', 'on_update_bbinv_column_widths');

function register_bbinv_options() {
    
    register_setting( 'bbinv-options', 'bbinv_theme' );
    
    register_setting( 'bbinv-options', 'bbinv_revenue_currency' );
  
    register_setting( 'bbinv-options', 'bbinv_company_name' );
    register_setting( 'bbinv-options', 'bbinv_address' );
    register_setting( 'bbinv-options', 'bbinv_suburb' );
    register_setting( 'bbinv-options', 'bbinv_state' );
    register_setting( 'bbinv-options', 'bbinv_postcode' );
    register_setting( 'bbinv-options', 'bbinv_phone' );
    register_setting( 'bbinv-options', 'bbinv_email' );
    register_setting( 'bbinv-options', 'bbinv_bcc' );
    register_setting( 'bbinv-options', 'bbinv_add_detail' );

    register_setting( 'bbinv-options', 'bbinv_invoice_type' );
    register_setting( 'bbinv-options', 'bbinv_paid_invoice_type' );
    register_setting( 'bbinv-options', 'bbinv_paid_watermark' );
    register_setting( 'bbinv-options', 'bbinv_invoice_no_label' );
    register_setting( 'bbinv-options', 'bbinv_po_label' );
    register_setting( 'bbinv-options', 'bbinv_attn_name_label' );
    register_setting( 'bbinv-options', 'bbinv_tax_label' );
    register_setting( 'bbinv-options', 'bbinv_tax_value' );
    register_setting( 'bbinv-options', 'bbinv_subtotal_label' );
    register_setting( 'bbinv-options', 'bbinv_discount_label' );
    register_setting( 'bbinv-options', 'bbinv_total_label' );
    
    register_setting( 'bbinv-options', 'bbinv_open_content_1' );
    register_setting( 'bbinv-options', 'bbinv_open_content_2' );
    
    register_setting( 'bbinv-options', 'bbinv_columns' );
    register_setting( 'bbinv-options', 'bbinv_column_types' );
    register_setting( 'bbinv-options', 'bbinv_column_widths' );
    
    register_setting( 'bbinv-options', 'bbinv_invoice_no_gen' );
    register_setting( 'bbinv-options', 'bbinv_invoice_no_gen_last' );
    register_setting( 'bbinv-options', 'bbinv_invoice_no_gen_incr' );
    register_setting( 'bbinv-options', 'bbinv_invoice_last_post_id' );
    register_setting( 'bbinv-options', 'bbinv_pdf_filename' );
    
    register_setting( 'bbinv-options', 'bbinv_calculate_rows' );
    register_setting( 'bbinv-options', 'bbinv_calculate_operators' );

    register_setting( 'bbinv-options', 'bbinv_calculate_subtotal' );
    
    register_setting( 'bbinv-options', 'bbinv_from');
    register_setting( 'bbinv-options', 'bbinv_from_email');
    register_setting( 'bbinv-options', 'bbinv_email_subject');
    register_setting( 'bbinv-options', 'bbinv_email_message');
    register_setting( 'bbinv-options', 'bbinv_paid_email_subject');
    register_setting( 'bbinv-options', 'bbinv_paid_email_message');
    
    do_action('register_additional_bbinv_options');
}
add_action( 'admin_init', 'register_bbinv_options' );

function bbinv_settings_output() {
    include 'screens/settings.php';
} 
?>