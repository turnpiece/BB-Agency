<?php

add_option('invkp_theme_version_ttl', current_time('timestamp'));
add_option('invkp_theme_version_update_notice', '');

function invkp_theme_version_check() {
    //if (get_option('invkp_theme_version_ttl') < current_time('timestamp')) :
        $dir = str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/';
        $folder = scandir($dir);
        $exclude = array('.', '..', '.DS_Store', 'default');
        foreach ($folder as $f) {
            if (!in_array($f, $exclude)) {
                if (!file_exists($dir.$f."/version")) $version = '1.0';
                else $version = file_get_contents($dir.$f."/version");
                
                $stable_version = get_option('invkp_safe_theme');
                
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
                        $notice = get_option('invkp_theme_version_update_notice');
                        $notice[$f] = __("There is a new version of the invoice theme", "invkptext")." <strong>".ucfirst(str_replace(array('-', '_'), ' ', $f))."</strong>. ".__("Please make sure you up have the latest version of Invoice King Pro before downloading and installing your updated theme.", "invkptext")." <a href='http://kingpro.me/download_theme.php?k=".$current_version."'>".__("Please download the new version here", "invkptext")."</a><br /><br /><a href='".admin_url('admin.php?action=invkpcheckthemeversion&theme='.$f)."'>".__("Have you just installed this?", "invkptext")."</a>";
                        update_option('invkp_theme_version_update_notice', $notice);
                    }
                }
            }
        }
        
        update_option('invkp_theme_version_ttl', strtotime('+1 day', current_time('timestamp')));
    //endif;
}
add_action('admin_init', 'invkp_theme_version_check');


function invkpcheckthemeversion_admin_action()
{
    $theme = $_GET['theme'];
    $dir = str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/';
    if (!file_exists($dir.$theme."/version")) $version = '1.0';
    else $version = file_get_contents($dir.$theme."/version");
    
    $stable_version = get_option('invkp_safe_theme');
    if ($stable_version <= $version) {
        $notice = get_option('invkp_theme_version_update_notice');
        unset($notice[$theme]);
        update_option('invkp_theme_version_update_notice', $notice);
    }
    
    header("Location: ".$_SERVER['HTTP_REFERER']);
    
}
add_action( 'admin_action_invkpcheckthemeversion', 'invkpcheckthemeversion_admin_action' );

function invkp_check_page($hook) {
    global $current_screen;
    $invkp_pages = array('king-pro-plugins_page_invoicekingpro', "toplevel_page_kpp_menu");
    $pages_req = array('post.php', 'post-new.php', 'edit.php');
    
    if (in_array($hook, $invkp_pages)) return true;
    if (in_array($hook, $pages_req) && ($current_screen->post_type == 'invkp_invoices' || $current_screen->post_type == 'invkp_clients')) return true;
    return false;
}

$theme_notices = get_option('invkp_theme_version_update_notice');

if (!empty($theme_notices)) {
    function invkp_theme_notice() {
        global $theme_notices;
        foreach ($theme_notices as $note) : ?>
        <div class="error">
            <p><?php _e( $note, 'invkptext' ); ?></p>
        </div>
        <?php endforeach;
    }
    add_action( 'admin_notices', 'invkp_theme_notice' );
}

// Check if sent email
if (isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'invkp_invoices' && isset($_REQUEST['send'])) {
    function invkp_admin_notice() {
        if ($_REQUEST['send'] == 0) :
        ?>
        <div class="error">
            <p><?php _e( "Your email could not be sent. Please check the email address and try again.", 'invkptext' ); ?></p>
        </div>
        <?php
        elseif ($_REQUEST['send'] == 1) :
        ?>
        <div class="updated">
            <p><?php _e( "Email sent successfully!", 'invkptext' ); ?></p>
        </div>
        <?php
        endif;
    }
    add_action( 'admin_notices', 'invkp_admin_notice' );
}

// Default Options
add_option( 'invkp_revenue_currency', '$' );
add_option( 'invkp_comany_name', '' );
add_option( 'invkp_address', '' );
add_option( 'invkp_suburb', '' );
add_option( 'invkp_state', '' );
add_option( 'invkp_postcode', '' );
add_option( 'invkp_phone', '' );
add_option( 'invkp_email', '' );
add_option( 'invkp_bcc', 0 );

add_option( 'invkp_invoice_type', 'INVOICE' );
add_option( 'invkp_paid_invoice_type', 'RECEIPT' );
add_option( 'invkp_paid_watermark', 'PAID' );
add_option( 'invkp_invoice_no_label', 'Invoice #:' );
add_option( 'invkp_po_label', 'PO:' );
add_option( 'invkp_attn_name_label', 'Attn:' );
add_option( 'invkp_tax_label', 'GST' );
add_option( 'invkp_tax_value', '0' );
add_option( 'invkp_subtotal_label', 'Subtotal' );
add_option( 'invkp_discount_label', 'Discount' );
add_option( 'invkp_total_label', 'Total' );

add_option( 'invkp_open_content_1', '');
add_option( 'invkp_open_content_2', '');

add_option( 'invkp_invoice_no_gen', 'Ymd##' );
add_option( 'invkp_invoice_no_gen_last', '' );
add_option( 'invkp_invoice_no_gen_incr', '0' );
add_option( 'invkp_invoice_last_post_id', '0' );
add_option( 'invkp_pdf_filename', '{pid}');

add_option( 'invkp_columns', '' );
add_option( 'invkp_column_types', '' );
add_option( 'invkp_column_widths', '' );

add_option( 'invkp_theme', 'default' );

add_option( 'invkp_calculate_rows', '' );
add_option( 'invkp_calculate_operators', '' );

add_option( 'invkp_calculate_subtotal', 'Total ($)');

add_option( 'invkp_from', get_bloginfo('name'));
add_option( 'invkp_from_email', get_bloginfo('admin_email'));
add_option( 'invkp_email_subject', "{{invoice_type}} ".__('From', "invkptext")." ".get_bloginfo('name'));
$message = __("Hi", "invkptext")." {{client_name}},"."\r\n\r\n";
$message .= __('Please find attached the', "invkptext").' {{invoice_type}} '.__('for $', "invkptext").'{{invoice_total}}.'."\r\n\r\n";

$message .= __("Regards,", "invkptext")."\r\n";
$message .= get_bloginfo('name');
add_option( 'invkp_email_message', $message);
add_option( 'invkp_paid_email_subject', "{{invoice_type}} ".__("From", "invkptext")." ".get_bloginfo('name'));
$message = __("Hi", "invkptext")." {{client_name}},"."\r\n\r\n";
$message .= __('Please find attached the', "invkptext").' {{invoice_type}} '.__('for', "invkptext").' {{invoice_number_label}}{{invoice_number}}.'."\r\n";
$message .= __('Thank you for your business', "invkptext")."\r\n\r\n";

$message .= __("Regards,", "invkptext")."\r\n";
$message .= get_bloginfo('name');
add_option( 'invkp_paid_email_message', $message);

$pdf_theme = get_option('invkp_theme');
if (file_exists(str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/theme_options.php')) {
    include str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/theme_options.php';
}

// Register Post types for invoicing
function invkp_create_post_type() {
    register_post_type( 'invkp_invoices',
        array(
            'labels' => array(
                'name' => __( 'Invoices', "invkptext"),
                'singular_name' => __( 'Invoice', "invkptext"),
                'all_items'=> __('All Invoices', "invkptext"),
                'edit_item'=>__('Edit Invoice', "invkptext"),
                'update_item'=>__('Update Invoice', "invkptext"),
                'add_new_item'=>__('Add New Invoice', "invkptext"),
                'new_item_name'=>__('New Invoice', "invkptext"),
                'add_new' => __('Add New', "invkptext"),
                'new_item' => __('New Invoice', "invkptext"),
                'view_item' => __('View Invoice', "invkptext"),
                'search_items' => __('Search Invoices', "invkptext"),
                'not_found' =>  __('No invoices found', "invkptext"),
                'not_found_in_trash' => __('No invoices found in Trash', "invkptext"), 
                'parent_item_colon' => '',
                'menu_name' => __('Invoices', "invkptext")
            ),
            'public' => true,
            'exclude_from_search' => true,
            'menu_position' => 5,
            'supports' => array('title')
        )
    );
    
    register_post_type( 'invkp_clients',
        array(
            'labels' => array(
                'name' => __( 'Clients', "invkptext"),
                'singular_name' => __( 'Client', "invkptext"),
                'all_items'=>__('All Clients', "invkptext"),
                'edit_item'=>__('Edit Client', "invkptext"),
                'update_item'=>__('Update Client', "invkptext"),
                'add_new_item'=>__('Add New Client', "invkptext"),
                'new_item_name'=>__('New Client', "invkptext"),
                'add_new' => __('Add New', "invkptext"),
                'new_item' => __('New Client', "invkptext"),
                'view_item' => __('View Client', "invkptext"),
                'search_items' => __('Search Clients', "invkptext"),
                'not_found' =>  __('No clients found', "invkptext"),
                'not_found_in_trash' => __('No clients found in Trash', "invkptext"), 
                'parent_item_colon' => '',
                'menu_name' => __('Clients', "invkptext")
            ),
            'public' => true,
            'exclude_from_search' => true,
            'show_in_menu' => 'edit.php?post_type=invkp_invoices',
            'supports' => array('title')
        )
    );
}
add_action( 'init', 'invkp_create_post_type' );

// Styling for the custom post type icon
function wpt_invkp_icons() {
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
        
        #menu-posts-invkp_invoices .wp-menu-image {
            background: url(<?= plugins_url('/images/invkp-icon_16x16_sat.png', dirname(__FILE__)) ?>) no-repeat center center !important;
        }
        #menu-posts-invkp_invoices .wp-menu-image:before {display: none;}
	#menu-posts-invkp_invoices:hover .wp-menu-image, #menu-posts-invkp_invoices.wp-has-current-submenu .wp-menu-image {
            background: url(<?= plugins_url('/images/invkp-icon_16x16.png', dirname(__FILE__)) ?>) no-repeat center center !important;
        }
        #menu-posts-invkp_invoices .wp-menu-image:before {display: none;}
	#icon-edit.icon32-posts-invkp_invoices {background: url(<?= plugins_url('/images/invkp-icon_32x32_sat.png', dirname(__FILE__)) ?>) no-repeat;}
    </style>
<?php }
add_action( 'admin_head', 'wpt_invkp_icons' );

// Columns in custom post types
function invkp_edit_invoice_columns( $columns ) {

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Description' , "invkptext"),
        'invoice_no' => __('Invoice No.', "invkptext"),
        'client' => __( 'Client' , "invkptext"),
        'total' => __( 'Total' , "invkptext"),
        'invoice_paid' => __( 'Paid?' , "invkptext"),
        'date' => __( 'Date Created' , "invkptext"),
        'invoice_sent' => __('Last Sent', "invkptext")
    );

    return $columns;
}
add_filter( 'manage_edit-invkp_invoices_columns', 'invkp_edit_invoice_columns' ) ;

// Update column data with custom data
function invkp_columns($column_name, $ID) {
    switch ($column_name) {
        case 'invoice_no' :
            // Get invoice number
            $invoice_no = get_post_meta($ID, 'invkp_invoice_no');
            echo $invoice_no[0];
            break;
        
        case 'client' :
            // Get client details via client id and output company name and email
            $client = get_post_meta($ID, 'invkp_client_company');
            echo $client[0];
            break;
            
        case 'total' :
            // Get total of invoice
            $total = get_post_meta($ID, 'invkp_total');
            echo $total[0];
            break;
        
        case 'invoice_paid' :
            // Count invoices attached to this client
            $paid = get_post_meta($ID, 'invkp_paid_invoice');
            if (!empty($paid) && $paid[0] == 1) echo "<a href='".admin_url('admin.php?action=invkpmarkunpaid&post='.$ID)."'><img src='".str_replace('includes', 'images', plugin_dir_url(__FILE__))."tick.png' /></a>";
            else echo "<a href='".admin_url('admin.php?action=invkpmarkpaid&post='.$ID)."'><img src='".str_replace('includes', 'images', plugin_dir_url(__FILE__))."cross.png' /></a>";
            break;
            
        case 'invoice_sent' :
            // Display last sent date
            $sent = get_post_meta($ID, 'invkp_sent_invoice');
            if (!empty($sent) && is_numeric($sent[0]) && $sent[0] > 0) echo date("F j, Y g:i a", $sent[0]);
            else echo "Not Sent";
            break;
    }
}
add_action('manage_invkp_invoices_posts_custom_column', 'invkp_columns', 10, 2); 

// Columns in custom post types
function invkp_edit_client_columns( $columns ) {

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __( 'Client Name', "invkptext" ),
        'title' => __( 'Company Name' , "invkptext"),
        'email' => __( 'Attached Email' , "invkptext"),
        'invoices_attached' => __( 'Invoices' , "invkptext"),
    );

    return $columns;
}
add_filter( 'manage_edit-invkp_clients_columns', 'invkp_edit_client_columns' ) ;

// Update column data with custom data
function invkp_client_columns($column_name, $ID) {
    switch ($column_name) {
        case 'name' :
            // Get client email
            $email = get_post_meta($ID, 'invkp_client_attn_name');
            echo $email[0];
            break;
        
        case 'email' :
            // Get client email
            $email = get_post_meta($ID, 'invkp_client_email');
            echo $email[0];
            break;
            
        case 'invoices_attached' :
            // Count invoices attached to this client
            $invoices = query_posts(array('post_type'=>'invkp_invoices', 'meta_key'=>'invkp_client_link', 'meta_value'=>$ID));
            echo count($invoices);
            break;
    }
}
add_action('manage_invkp_clients_posts_custom_column', 'invkp_client_columns', 10, 2); 

function invkp_action_row($actions){
    global $post;
   if ($post->post_type == "invkp_invoices"){
      //remove what you don't need
       unset( $actions['inline hide-if-no-js'] );
       unset( $actions['view'] );
       
       $paid_invoice = get_post_meta($post->ID, 'invkp_paid_invoice');
       if (!empty($paid_invoice) && $paid_invoice[0] == 1)
           $actions['markunpaid'] = '<a href=\''.admin_url('admin.php?action=invkpmarkunpaid&post='.$post->ID).'\'>'.__("Mark Unpaid", "invkptext").'</a>';
       else
           $actions['markpaid'] = '<a href=\''.admin_url('admin.php?action=invkpmarkpaid&post='.$post->ID).'\'>'.__("Mark Paid", "invkptext").'</a>';
       $actions['viewpdf'] = '<a href=\''.admin_url('admin.php?action=invkpviewpdf&post='.$post->ID).'\' target=\'blank\'>'.__('View PDF', "invkptext").'</a>';
       $actions['emailpdf'] = '<a href=\''.admin_url('admin.php?action=invkpemailpdf&post='.$post->ID).'\'>'.__('Email PDF', "invkptext").'</a>';
   }
   return $actions;
}
add_filter('post_row_actions','invkp_action_row');

function invkpmarkpaid_admin_action()
{
    $post_id = $_GET['post'];
    update_post_meta( $post_id, 'invkp_paid_invoice', 1 );
    
    header("Location: ".$_SERVER['HTTP_REFERER']);
    
}
add_action( 'admin_action_invkpmarkpaid', 'invkpmarkpaid_admin_action' );

function invkpmarkunpaid_admin_action()
{
    $post_id = $_GET['post'];
    update_post_meta( $post_id, 'invkp_paid_invoice', 0 );
    
    header("Location: ".$_SERVER['HTTP_REFERER']);
    
}
add_action( 'admin_action_invkpmarkunpaid', 'invkpmarkunpaid_admin_action' );

function invkpviewpdf_admin_action()
{
    include plugin_dir_path(__FILE__)."../packages/fpdf/fpdf.php";
    $post_id = $_GET['post'];
    $invoice = query_posts(array('post_type'=>'invkp_invoices', 'p'=>$post_id));
    $invoice[0]->custom = invkp_return_fields($post_id);
    if (isset($invoice[0]->custom['invkp_column'][0])) {
        if (!is_array($invoice[0]->custom['invkp_column'][0]))
            $rows = unserialize($invoice[0]->custom['invkp_column'][0]); 
        else $rows = $invoice[0]->custom['invkp_column'][0];
    }
    $columns = unserialize($invoice[0]->custom['invkp_columns'][0]);
    $column_types = unserialize($invoice[0]->custom['invkp_column_types'][0]);
    $column_widths = unserialize($invoice[0]->custom['invkp_column_widths'][0]);
    $currency_symbol = get_option('invkp_revenue_currency');
    $tax_label = get_option("invkp_tax_label");
    $subtotal_label = get_option("invkp_subtotal_label");
    $discount_label = get_option("invkp_discount_label");
    $total_label = get_option("invkp_total_label");
    $paid_label = get_option("invkp_paid_invoice_type");
    $paid_watermark = get_option('invkp_paid_watermark');
    //print_r($invoice[0]->custom);die;
    
    if ($invoice[0]->custom['invkp_paid_invoice'][0] == 1) {
        $paid = 'paid_';
        $invoice_type = $paid_label;
    } else {
        $paid = '';
        $invoice_type = $invoice[0]->custom['invkp_invoice_type'][0];
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
        $invoice[0]->custom['invkp_invoice_no_label'][0],
        $invoice[0]->custom['invkp_invoice_no'][0],
        $invoice[0]->custom['invkp_date'][0],
        $invoice[0]->custom['invkp_due_date'][0],
        $invoice[0]->custom['invkp_selected_client_company'][0],
        $invoice[0]->custom['invkp_selected_client_attn'][0],
        $invoice[0]->custom['invkp_total'][0],
    );
    
    $invoice[0]->custom['invkp_open_content_1'][0] = str_replace($search, $replace, $invoice[0]->custom['invkp_open_content_1'][0]);
    $invoice[0]->custom['invkp_open_content_2'][0] = str_replace($search, $replace, $invoice[0]->custom['invkp_open_content_2'][0]);
    
    do_action('invkp_additional_pdf_invoice_data');
    
    $pdf_theme = get_option('invkp_theme');
    if (file_exists(str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/pdf.php'))
        include str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/pdf.php';
    else
        include 'pdf.php';
    
    header("Location: ".str_replace("includes/","",plugin_dir_url(__FILE__))."outputs/".$pdf_filename.".pdf");
    
}
add_action( 'admin_action_invkpviewpdf', 'invkpviewpdf_admin_action' );

function invkpemailpdf_admin_action()
{
    global $wpdb;
    $post_id = $_GET['post'];
    $invoice = query_posts(array('post_type'=>'invkp_invoices', 'p'=>$post_id));
    $invoice[0]->custom = invkp_return_fields($post_id);
    
    if (isset($invoice[0]->custom['invkp_selected_client_email'][0]) && $invoice[0]->custom['invkp_selected_client_email'][0] !== '')
        $email = $invoice[0]->custom['invkp_selected_client_email'][0];
    elseif (isset($invoice[0]->custom['invkp_client_email'][0]) && $invoice[0]->custom['invkp_client_email'][0] !== '')
        $email = $invoice[0]->custom['invkp_client_email'][0];
    else header("Location: ".$_SERVER['HTTP_REFERER']."&send=0");
    
    $name = $invoice[0]->custom['invkp_selected_client_attn'][0];
    
    $from = get_option('invkp_from');
    $from_email = get_option('invkp_from_email');
    
    $headers = "From: ".$from." <".$from_email.">";
    if (get_option('invkp_bcc')) $headers = "Bcc: ".$from." <".$from_email.">";
    
    include plugin_dir_path(__FILE__)."../packages/fpdf/fpdf.php";
    if (isset($invoice[0]->custom['invkp_column'][0])) {
        if (!is_array($invoice[0]->custom['invkp_column'][0]))
            $rows = unserialize($invoice[0]->custom['invkp_column'][0]); 
        else $rows = $invoice[0]->custom['invkp_column'][0];
    }
    $columns = unserialize($invoice[0]->custom['invkp_columns'][0]);
    $column_types = unserialize($invoice[0]->custom['invkp_column_types'][0]);
    $column_widths = unserialize($invoice[0]->custom['invkp_column_widths'][0]);
    $currency_symbol = get_option('invkp_revenue_currency');
    $tax_label = get_option("invkp_tax_label");
    $subtotal_label = get_option("invkp_subtotal_label");
    $discount_label = get_option("invkp_discount_label");
    $total_label = get_option("invkp_total_label");
    $paid_label = get_option("invkp_paid_invoice_type");
    $paid_watermark = get_option('invkp_paid_watermark');
    
    if ($invoice[0]->custom['invkp_paid_invoice'][0] == 1) {
        $paid = 'paid_';
        $invoice_type = $paid_label;
    } else {
        $paid = '';
        $invoice_type = $invoice[0]->custom['invkp_invoice_type'][0];
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
        $invoice[0]->custom['invkp_invoice_no_label'][0],
        $invoice[0]->custom['invkp_invoice_no'][0],
        $invoice[0]->custom['invkp_date'][0],
        $invoice[0]->custom['invkp_due_date'][0],
        $invoice[0]->custom['invkp_selected_client_company'][0],
        $invoice[0]->custom['invkp_selected_client_attn'][0],
        $invoice[0]->custom['invkp_total'][0],
    );
    
    $invoice[0]->custom['invkp_open_content_1'][0] = str_replace($search, $replace, $invoice[0]->custom['invkp_open_content_1'][0]);
    $invoice[0]->custom['invkp_open_content_2'][0] = str_replace($search, $replace, $invoice[0]->custom['invkp_open_content_2'][0]);
    
    do_action('invkp_additional_pdf_invoice_data');
    
    $pdf_theme = get_option('invkp_theme');
    if (file_exists(str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/pdf.php'))
        include str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/pdf.php';
    else
        include 'pdf.php';
    
    $attachments = array(str_replace("includes/","",plugin_dir_path(__FILE__))."outputs/".$pdf_filename.".pdf");
    
    $subject = str_replace($search, $replace, get_option('invkp_'.$paid.'email_subject'));
    $message = str_replace($search, $replace, get_option('invkp_'.$paid.'email_message'));
    
    global $invkp_attachments;
    $invkp_attachments = $attachments;
    
    do_action('invkp_additional_email_pdf_invoice_data', $attachments, $post_id);
    $attachments = $invkp_attachments;

    if (@wp_mail($email, $subject, $message, $headers, $attachments)) $sent = 1; else $sent = 0;
    
    if ($sent == 1) {
        update_post_meta( $post_id, 'invkp_sent_invoice', current_time('timestamp') );
    }
    
    header("Location: ".$_SERVER['HTTP_REFERER']."&send=".$sent);
}
add_action( 'admin_action_invkpemailpdf', 'invkpemailpdf_admin_action' );

// Update title field to become URL field
function invkp_title_text_input( $title ){
    global $post;
    if($post->post_type == 'invkp_invoices') 
        return $title = __('Invoice Description for your convenience', "invkptext");
    if($post->post_type == 'invkp_clients')
        return $title = __('Company Name', "invkptext");
    return $title;
}
add_filter( 'enter_title_here', 'invkp_title_text_input' );

// Update Feature Image to become Advert Image
function invkp_change_meta_boxes()
{
    add_meta_box('postinvoicedatadiv', __('Invoice', "invkptext"), 'invkp_post_invoice', 'invkp_invoices', 'advanced', 'high');
    add_meta_box('postinvoiceoptionsdatadiv', __('Invoice Options', "invkptext"), 'invkp_post_invoice_options', 'invkp_invoices', 'side', 'high');
    add_meta_box('postinvoiceclientdatadiv', __('Client Details', "invkptext"), 'invkp_post_client_details', 'invkp_invoices', 'side', 'high');
    
    add_meta_box('postclientdatadiv', __('Client Details', "invkptext"), 'invkp_post_client', 'invkp_clients', 'advanced', 'high');
    do_action('invkp_additional_invoice_meta_box');
}
add_action('do_meta_boxes', 'invkp_change_meta_boxes');

// Output stats for post
function invkp_post_client($object, $box) {
    global $wpdb;
    global $post;
    $custom_fields = invkp_return_client_fields();
    $inv_nonce = wp_create_nonce(basename(__FILE__));
    echo '<input type="hidden" name="invkp_client_meta_box_nonce" value="'.$inv_nonce.'" />';
    echo '<div><label>'.__('Company Contact Name', "invkptext").':</label><input type="text" name="invkp_client_attn_name" value="'.$custom_fields['invkp_client_attn_name'][0].'" /></div>';
    echo '<div><label>'.__('Address', "invkptext").':</label><input type="text" name="invkp_client_address" value="'.$custom_fields['invkp_client_address'][0].'" /></div>';
    echo '<div class="left"><label>'.__('Suburb', "invkptext").':</label><input type="text" name="invkp_client_suburb" value="'.$custom_fields['invkp_client_suburb'][0].'" /></div>';
    echo '<div class="right"><label>'.__('State', "invkptext").':</label><input type="text" name="invkp_client_state" value="'.$custom_fields['invkp_client_state'][0].'" /></div>';
    echo '<div class="left"><label>'.__('Postcode/Zip', "invkptext").':</label><input type="text" name="invkp_client_postcode" value="'.$custom_fields['invkp_client_postcode'][0].'" /></div>';
    echo '<div class="left"><label>'.__('Email', "invkptext").':</label><input type="text" name="invkp_client_email" value="'.$custom_fields['invkp_client_email'][0].'" /></div>';
    echo '<div class="right"><label>'.__('Phone', "invkptext").':</label><input type="text" name="invkp_client_phone" value="'.$custom_fields['invkp_client_phone'][0].'" /></div>';
}

function invkp_post_invoice($object, $box) {
    global $wpdb;
    global $post;
    $custom_fields = invkp_return_fields();
    $inv_nonce = wp_create_nonce(basename(__FILE__));
    
    if (isset($custom_fields["invkp_column"][0])) {
        if (!is_array($custom_fields['invkp_column'][0]))
            $rows = unserialize($custom_fields['invkp_column'][0]); 
        else $rows = $custom_fields['invkp_column'][0];
    }
    
    if (isset($custom_fields["invkp_columns"][0])) $columns = unserialize($custom_fields["invkp_columns"][0]);
    else $columns = get_option('invkp_columns');
    //$columns = get_option('invkp_columns');
    if (isset($custom_fields["invkp_column_types"][0])) $column_types = unserialize($custom_fields["invkp_column_types"][0]);
    else $column_types = get_option('invkp_column_types');
    //$column_types = get_option('invkp_column_types');
    if (isset($custom_fields["invkp_column_widths"][0])) $column_widths = unserialize($custom_fields["invkp_column_widths"][0]);
    else $column_widths = get_option('invkp_column_widths');
    //$column_widths = get_option('invkp_column_widths');
    if (isset($custom_fields["invkp_calculate_rows"][0])) $calc_cols = unserialize($custom_fields["invkp_calculate_rows"][0]);
    else $calc_cols = get_option('invkp_calculate_rows');
    //$calc_cols = get_option('invkp_calculate_rows');
    if (isset($custom_fields["invkp_calculate_operators"][0])) $calc_ops = unserialize($custom_fields["invkp_calculate_operators"][0]);
    else $calc_ops = get_option('invkp_calculate_operators');
    //$calc_ops = get_option('invkp_calculate_operators');
    if (isset($custom_fields["invkp_calculate_subtotal"][0])) $subtotal_col = $custom_fields["invkp_calculate_subtotal"][0];
    else $subtotal_col = get_option('invkp_calculate_subtotal');
    if (!isset($calc_ops[0])) {
        echo __("Please visit the settings page to setup your defaults for the invoice", "invkptext");
        echo "<br /><br />";
        echo "<a href='".admin_url('/admin.php?page=invoicekingpro')."'>".__('Settings Page', "invkptext")."</a>";
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
    $pdf_theme = get_option('invkp_theme');
    if (file_exists(str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/invoice_html.php'))
        include str_replace("includes/","",plugin_dir_path(__FILE__)).'themes/'.$pdf_theme.'/invoice_html.php';
    else
        include('invoice_html.php');
    
    echo "<input type='hidden' name='invkp_invoice_no_gen_incr' value='".$custom_fields["invkp_invoice_no_gen_incr"][0]."' />"; 
    echo "<input type='hidden' name='invkp_invoice_last_post_id' value='".$custom_fields["invkp_invoice_last_post_id"][0]."' />";
    echo "<input type='hidden' name='invkp_invoice_no_gen_last' value='".$custom_fields["invkp_invoice_no_gen_last"][0]."' />";
}

function invkp_post_invoice_options($object, $box) {
    global $wpdb;
    global $post;
    $discount_type = get_post_meta( $post->ID, 'invkp_discount_type', true );
    $discount_value = get_post_meta( $post->ID, 'invkp_discount_value', true );
    $tax_percentage = get_post_meta( $post->ID, 'invkp_tax_percentage', true ) ? get_post_meta( $post->ID, 'invkp_tax_percentage', true ) : get_option('invkp_tax_value');
    $paid_invoice = get_post_meta( $post->ID, 'invkp_paid_invoice', true );
    $checked = '';
    if ($paid_invoice == "1") $checked = ' checked';
    
    do_action('invkp_additional_post_invoice_options');
    
    echo '<div class="misc-pub-section"><label for="invkp_discount_type">'.__('Discount Type', "invkptext").':</label>';
    echo '<select name="invkp_discount_type" id="invkp_discount_type" style="float: right;margin-top: -3px;"><option value="percentage"', ($discount_type == 'percentage') ? ' selected' : '' , '>'.__('Percentage', "invkptext").'</option><option value="setvalue"', ($discount_type == 'setvalue') ? ' selected' : '' , '>'.__('Set Value', "invkptext").'</option></select>';
    echo '</div>';
    echo '<div class="misc-pub-section"><label for="invkp_discount_value">'.__('Discount Value', "invkptext").':</label>';
    echo '<input type="text" name="invkp_discount_value" id="invkp_discount_value" value="'. $discount_value .'" class="calculate" style="width: 70px;float: right;margin-top: -3px;" />';
    echo '</div>';
    echo '<div class="misc-pub-section"><label for="invkp_tax_percentage">'.__('Tax Percentage', "invkptext").':</label>';
    echo '<input type="text" name="invkp_tax_percentage" id="invkp_tax_percentage" value="'. $tax_percentage .'" class="calculate" style="width: 70px;float: right;margin-top: -3px;" />';
    echo '</div>';
    echo '<div class="misc-pub-section"><label for="invkp_paid_invoice">'.__('Invoice Paid?', "invkptext").':</label>';
    echo '<input type="hidden" name="invkp_paid_invoice" id="invkp_paid_invoice_no" value="0" />';
    echo '<input type="checkbox" name="invkp_paid_invoice" id="invkp_paid_invoice_yes" value="1"'.$checked.' style="float: right;margin-top: 2px;" />';
    echo '</div>';
}

function invkp_post_client_details($object, $box) {
    global $wpdb;
    global $post;
    
    $output = array();
    $output['invkp_client_link'] = (get_post_meta( $post->ID, 'invkp_client_link' ) ? get_post_meta( $post->ID, 'invkp_client_link' ) : array(''));
    $output['invkp_selected_client_company'] = (get_post_meta( $post->ID, 'invkp_selected_client_company' ) ? get_post_meta( $post->ID, 'invkp_selected_client_company' ) : array(''));
    $output['invkp_selected_client_attn'] = (get_post_meta( $post->ID, 'invkp_selected_client_attn' ) ? get_post_meta( $post->ID, 'invkp_selected_client_attn' ) : array(''));
    $output['invkp_selected_client_address'] = (get_post_meta( $post->ID, 'invkp_selected_client_address' ) ? get_post_meta( $post->ID, 'invkp_selected_client_address' ) : array(''));
    $output['invkp_selected_client_suburb'] = (get_post_meta( $post->ID, 'invkp_selected_client_suburb' ) ? get_post_meta( $post->ID, 'invkp_selected_client_suburb' ) : array(''));
    $output['invkp_selected_client_state'] = (get_post_meta( $post->ID, 'invkp_selected_client_state' ) ? get_post_meta( $post->ID, 'invkp_selected_client_state' ) : array(''));
    $output['invkp_selected_client_postcode'] = (get_post_meta( $post->ID, 'invkp_selected_client_postcode' ) ? get_post_meta( $post->ID, 'invkp_selected_client_postcode' ) : array(''));
    $output['invkp_selected_client_email'] = (get_post_meta( $post->ID, 'invkp_selected_client_email' ) ? get_post_meta( $post->ID, 'invkp_selected_client_email' ) : array(''));
    $output['invkp_selected_client_phone'] = (get_post_meta( $post->ID, 'invkp_selected_client_phone' ) ? get_post_meta( $post->ID, 'invkp_selected_client_phone' ) : array(''));
    
    $clients = query_posts(array('post_type'=>'invkp_clients', "posts_per_page"=>-1));
    $client_dropdown = '';
    foreach ($clients as $client) {
        $client_val = array(
            'id'=>$client->ID,
            'company_name'=>$client->post_title,
            'attn_name'=>get_post_meta( $client->ID, 'invkp_client_attn_name'),
            'address'=>get_post_meta( $client->ID, 'invkp_client_address'),
            'suburb'=>get_post_meta( $client->ID, 'invkp_client_suburb'),
            'state'=>get_post_meta( $client->ID, 'invkp_client_state'),
            'postcode'=>get_post_meta( $client->ID, 'invkp_client_postcode'),
            'email'=>get_post_meta( $client->ID, 'invkp_client_email'),
            'phone'=>get_post_meta( $client->ID, 'invkp_client_phone')
        );
        
        $selected = '';
        //echo $client->ID." = ".$output['invkp_client_link'][0]."<br />";
        if ($client->ID == $output['invkp_client_link'][0]) $selected = ' selected';
        $client_dropdown .= "<option value='".json_encode($client_val)."'".$selected.">".$client->post_title."</option>";
    }
    
    echo '<div class="misc-pub-section" style="margin-bottom: 20px;"><label for="invkp_select_client">'.__('Use Existing Client Details', "invkptext").':</label>';
    echo '<select id="invkp_select_client" style="display:block;width: 100%;"><option value="">-- '.__('SELECT', "invkptext").' --</option>'.$client_dropdown.'</select>';
    echo '</div>';
    echo '<input type="hidden" name="invkp_client_link" id="invkp_client_link" value="'.$output['invkp_client_link'][0].'" />';
    echo '<div style="margin-bottom: 10px;"><label for="invkp_selected_client_company">'.__('Company Name', "invkptext").':</label>';
    echo '<input type="text" name="invkp_selected_client_company" id="invkp_selected_client_company" value="'.$output['invkp_selected_client_company'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="invkp_selected_client_company">'.__('Company Contact Name', "invkptext").':</label>';
    echo '<input type="text" name="invkp_selected_client_attn" id="invkp_selected_client_attn" value="'.$output['invkp_selected_client_attn'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="invkp_selected_client_address">'.__('Address', "invkptext").':</label>';
    echo '<input type="text" name="invkp_selected_client_address" id="invkp_selected_client_address" value="'.$output['invkp_selected_client_address'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="invkp_selected_client_suburb">'.__('Suburb', "invkptext").':</label>';
    echo '<input type="text" name="invkp_selected_client_suburb" id="invkp_selected_client_suburb" value="'.$output['invkp_selected_client_suburb'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="invkp_selected_client_state">'.__('State', "invkptext").':</label>';
    echo '<input type="text" name="invkp_selected_client_state" id="invkp_selected_client_state" value="'.$output['invkp_selected_client_state'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="invkp_selected_client_postcode">'.__('Postcode', "invkptext").':</label>';
    echo '<input type="text" name="invkp_selected_client_postcode" id="invkp_selected_client_postcode" value="'.$output['invkp_selected_client_postcode'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="invkp_selected_client_email">'.__('Email', "invkptext").':</label>';
    echo '<input type="text" name="invkp_selected_client_email" id="invkp_selected_client_email" value="'.$output['invkp_selected_client_email'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;"><label for="invkp_selected_client_phone">'.__('Phone', "invkptext").':</label>';
    echo '<input type="text" name="invkp_selected_client_phone" id="invkp_selected_client_phone" value="'.$output['invkp_selected_client_phone'][0].'" style="width: 100%;" /></div>';
    echo '<div style="margin-bottom: 10px;">';
    echo '<input type="button" id="insert_details" class="button" value="'.__('Insert Details', "invkptext").'" /><input type="button" id="save_client" class="button-primary right" value="'.__('Save Client', "invkptext").'" />';
    echo '</div>';
}

// Process the custom metabox fields
function invkp_save_custom_fields( ) {
	global $post;	
        
        // verify nonce
        if (!isset($_POST['invkp_meta_box_nonce']) || !wp_verify_nonce($_POST['invkp_meta_box_nonce'], basename(__FILE__))) {
            return;
        }
	
	if( $_POST ) {
            if (isset($_POST['invkp_company_name']))
                update_post_meta( $post->ID, 'invkp_company_name', $_POST['invkp_company_name'] );
            if (isset($_POST['invkp_invoice_type']))
                update_post_meta( $post->ID, 'invkp_invoice_type', $_POST['invkp_invoice_type'] );
            
            if (isset($_POST['invkp_address']))
                update_post_meta( $post->ID, 'invkp_address', $_POST['invkp_address'] );
            if (isset($_POST['invkp_suburb']))
                update_post_meta( $post->ID, 'invkp_suburb', $_POST['invkp_suburb'] );
            if (isset($_POST['invkp_state']))
                update_post_meta( $post->ID, 'invkp_state', $_POST['invkp_state'] );
            if (isset($_POST['invkp_postcode']))
                update_post_meta( $post->ID, 'invkp_postcode', $_POST['invkp_postcode'] );
            if (isset($_POST['invkp_date']))
                update_post_meta( $post->ID, 'invkp_date', ($_POST['invkp_date'] != '' ? $_POST['invkp_date'] : the_date()) );
            if (isset($_POST['invkp_due_date']))
                update_post_meta( $post->ID, 'invkp_due_date', $_POST['invkp_due_date'] );
            if (isset($_POST['invkp_invoice_no_label']))
                update_post_meta( $post->ID, 'invkp_invoice_no_label', $_POST['invkp_invoice_no_label'] );
            if (isset($_POST['invkp_invoice_no']))
                update_post_meta( $post->ID, 'invkp_invoice_no', $_POST['invkp_invoice_no'] );
            if (isset($_POST['invkp_po_label']))
                update_post_meta( $post->ID, 'invkp_po_label', $_POST['invkp_po_label'] );
            if (isset($_POST['invkp_po']))
                update_post_meta( $post->ID, 'invkp_po', $_POST['invkp_po'] );
            if (isset($_POST['invkp_payment_terms']))
                update_post_meta( $post->ID, 'invkp_payment_terms', $_POST['invkp_payment_terms'] );
            
            if (isset($_POST['invkp_phone']))
                update_post_meta( $post->ID, 'invkp_phone', $_POST['invkp_phone'] );
            if (isset($_POST['invkp_email']))
                update_post_meta( $post->ID, 'invkp_email', $_POST['invkp_email'] );
            if (isset($_POST['invkp_add_detail']))
                update_post_meta( $post->ID, 'invkp_add_detail', $_POST['invkp_add_detail'] );
            if (isset($_POST['invkp_attn_name_label']))
                update_post_meta( $post->ID, 'invkp_attn_name_label', $_POST['invkp_attn_name_label'] );
            if (isset($_POST['invkp_attn_name']))
                update_post_meta( $post->ID, 'invkp_attn_name', $_POST['invkp_attn_name'] );
            if (isset($_POST['invkp_client_company']))
                update_post_meta( $post->ID, 'invkp_client_company', $_POST['invkp_client_company'] );
            if (isset($_POST['invkp_client_address']))
                update_post_meta( $post->ID, 'invkp_client_address', $_POST['invkp_client_address'] );
            if (isset($_POST['invkp_client_suburb']))
                update_post_meta( $post->ID, 'invkp_client_suburb', $_POST['invkp_client_suburb'] );
            if (isset($_POST['invkp_client_state']))
                update_post_meta( $post->ID, 'invkp_client_state', $_POST['invkp_client_state'] );
            if (isset($_POST['invkp_client_postcode']))
                update_post_meta( $post->ID, 'invkp_client_postcode', $_POST['invkp_client_postcode'] );
            if (isset($_POST['invkp_client_email']))
                update_post_meta( $post->ID, 'invkp_client_email', $_POST['invkp_client_email'] );
            if (isset($_POST['invkp_client_phone']))
                update_post_meta( $post->ID, 'invkp_client_phone', $_POST['invkp_client_phone'] );
            
            if (isset($_POST['invkp_client_link']))
                update_post_meta( $post->ID, 'invkp_client_link', $_POST['invkp_client_link'] );
            if (isset($_POST['invkp_selected_client_company']))
                update_post_meta( $post->ID, 'invkp_selected_client_company', $_POST['invkp_selected_client_company'] );
            if (isset($_POST['invkp_selected_client_attn']))
                update_post_meta( $post->ID, 'invkp_selected_client_attn', $_POST['invkp_selected_client_attn'] );
            if (isset($_POST['invkp_selected_client_address']))
                update_post_meta( $post->ID, 'invkp_selected_client_address', $_POST['invkp_selected_client_address'] );
            if (isset($_POST['invkp_selected_client_suburb']))
                update_post_meta( $post->ID, 'invkp_selected_client_suburb', $_POST['invkp_selected_client_suburb'] );
            if (isset($_POST['invkp_selected_client_state']))
                update_post_meta( $post->ID, 'invkp_selected_client_state', $_POST['invkp_selected_client_state'] );
            if (isset($_POST['invkp_selected_client_postcode']))
                update_post_meta( $post->ID, 'invkp_selected_client_postcode', $_POST['invkp_selected_client_postcode'] );
            if (isset($_POST['invkp_selected_client_email']))
                update_post_meta( $post->ID, 'invkp_selected_client_email', $_POST['invkp_selected_client_email'] );
            if (isset($_POST['invkp_selected_client_phone']))
                update_post_meta( $post->ID, 'invkp_selected_client_phone', $_POST['invkp_selected_client_phone'] );
            
            if (isset($_POST['invkp_column']))
                update_post_meta( $post->ID, 'invkp_column', $_POST['invkp_column'] );//mysql_real_escape_string(serialize(
            if (isset($_POST['invkp_subtotal']))
                update_post_meta( $post->ID, 'invkp_subtotal', $_POST['invkp_subtotal'] );
            if (isset($_POST['invkp_discount']))
                update_post_meta( $post->ID, 'invkp_discount', $_POST['invkp_discount'] );
            if (isset($_POST['invkp_gst']))
                update_post_meta( $post->ID, 'invkp_gst', $_POST['invkp_gst'] );
            if (isset($_POST['invkp_total']))
                update_post_meta( $post->ID, 'invkp_total', $_POST['invkp_total'] );
            
            if (isset($_POST['invkp_columns']))
                update_post_meta( $post->ID, 'invkp_columns', $_POST['invkp_columns'] );
            if (isset($_POST['invkp_column_types']))
                update_post_meta( $post->ID, 'invkp_column_types', $_POST['invkp_column_types'] );
            if (isset($_POST['invkp_column_widths']))
                update_post_meta( $post->ID, 'invkp_column_widths', $_POST['invkp_column_widths'] );
            if (isset($_POST['invkp_calculate_rows']))
                update_post_meta( $post->ID, 'invkp_calculate_rows', $_POST['invkp_calculate_rows'] );
            if (isset($_POST['invkp_calculate_operators']))
                update_post_meta( $post->ID, 'invkp_calculate_operators', $_POST['invkp_calculate_operators'] );
            if (isset($_POST['invkp_calculate_subtotal']))
                update_post_meta( $post->ID, 'invkp_calculate_subtotal', $_POST['invkp_calculate_subtotal'] );
            
            if (isset($_POST['invkp_open_content_1']))
                update_post_meta( $post->ID, 'invkp_open_content_1', $_POST['invkp_open_content_1'] );
            if (isset($_POST['invkp_open_content_2']))
                update_post_meta( $post->ID, 'invkp_open_content_2', $_POST['invkp_open_content_2'] );
            
            if (isset($_POST['invkp_discount_type']))
                update_post_meta( $post->ID, 'invkp_discount_type', $_POST['invkp_discount_type'] );
            if (isset($_POST['invkp_discount_value']))
                update_post_meta( $post->ID, 'invkp_discount_value', $_POST['invkp_discount_value'] );
            if (isset($_POST['invkp_tax_percentage']))
                update_post_meta( $post->ID, 'invkp_tax_percentage', $_POST['invkp_tax_percentage'] );
            if (isset($_POST['invkp_paid_invoice']))
                update_post_meta( $post->ID, 'invkp_paid_invoice', $_POST['invkp_paid_invoice'] );
            
            if (isset($_POST['invkp_custom_data_1']))
                update_post_meta( $post->ID, 'invkp_custom_data_1', $_POST['invkp_custom_data_1'] );
            if (isset($_POST['invkp_custom_data_2']))
                update_post_meta( $post->ID, 'invkp_custom_data_2', $_POST['invkp_custom_data_2'] );
            if (isset($_POST['invkp_custom_data_3']))
                update_post_meta( $post->ID, 'invkp_custom_data_3', $_POST['invkp_custom_data_3'] );
            if (isset($_POST['invkp_custom_data_4']))
                update_post_meta( $post->ID, 'invkp_custom_data_4', $_POST['invkp_custom_data_4'] );
            if (isset($_POST['invkp_custom_data_5']))
                update_post_meta( $post->ID, 'invkp_custom_data_5', $_POST['invkp_custom_data_5'] );
            if (isset($_POST['invkp_custom_data_6']))
                update_post_meta( $post->ID, 'invkp_custom_data_6', $_POST['invkp_custom_data_6'] );
            if (isset($_POST['invkp_custom_data_7']))
                update_post_meta( $post->ID, 'invkp_custom_data_7', $_POST['invkp_custom_data_7'] );
            if (isset($_POST['invkp_custom_data_8']))
                update_post_meta( $post->ID, 'invkp_custom_data_8', $_POST['invkp_custom_data_8'] );
            if (isset($_POST['invkp_custom_data_9']))
                update_post_meta( $post->ID, 'invkp_custom_data_9', $_POST['invkp_custom_data_9'] );
            if (isset($_POST['invkp_custom_data_10']))
                update_post_meta( $post->ID, 'invkp_custom_data_10', $_POST['invkp_custom_data_10'] );
            
            do_action('invkp_additional_save_custom_fields', $_POST);
	}
}

add_action( 'save_post', 'invkp_save_custom_fields' );

// Created function that returns generated invoice number. This function is modified to be used in new post created manually and from cron job.
function invkp_generate_invoice_no($post_id) {		

    // Get option invkp_<metakey> values (workaround to get it to work from cron job)
    global $wpdb;
    $results = $wpdb->get_results("SELECT option_name,option_value FROM {$wpdb->prefix}options WHERE option_name LIKE '%invkp_%'", ARRAY_A);
    foreach ($results as $key=>$value) {
            $invkp_option[$value[option_name]] = $value[option_value];
    }

    // Generate date numbers 
    // (mysql2date is also used in WP's get_the_date function to generate date digits. But WP uses the post date, we want to use the current date)
    $invkp_invoice_no_gen = mysql2date($invkp_option['invkp_invoice_no_gen'], date("Y-m-d H:i:s")); // ( Ymd## becomes 20130902## on the september second in 2013)
    // Get last generated date number 
    $invkp_invoice_no_gen_last = $invkp_option['invkp_invoice_no_gen_last'];	// (last invoice nr without the ## changed into an incremental number)
    // Get last invoice post id
    $invkp_invoice_last_post_id = $invkp_option['invkp_invoice_last_post_id'];	// (last post-ID used for an invoice)

    // Check if last generated date number is the same as new.
    // if true: add increment to invoice number, increased by 1
    // if false: add increment to invoice number, reset it to 1
    $invkp_invoice_no_gen_incr = $invkp_option['invkp_invoice_no_gen_incr'];
    if ( $invkp_invoice_no_gen == $invkp_invoice_no_gen_last ) {

                    // Check if not in same post/invoice
                    if ($invkp_invoice_last_post_id != $post_id) {
                                    // Get invoice increment number and increase
                                    $invkp_invoice_no_gen_incr++;
                    }
    } else {
                    // Set invoice increment number to 1
                    $invkp_invoice_no_gen_incr = '1';
    }

    // Update options
    invkp_update_option( 'invkp_invoice_no_gen_incr', $invkp_invoice_no_gen_incr );
    invkp_update_option( 'invkp_invoice_last_post_id', $post_id );
    invkp_update_option( 'invkp_invoice_no_gen_last', $invkp_invoice_no_gen );

    // Add zero's to increment number if needed
    $CountX = substr_count($invkp_invoice_no_gen, '#');	// Number of x characters in generated invoice number 
    while (strlen($invkp_invoice_no_gen_incr) < $CountX) {	
                    $invkp_invoice_no_gen_incr = '0'.$invkp_invoice_no_gen_incr;		
    }

    // Split incr number into chars
    $invkp_invoice_no_gen_incr_chars = preg_split('//', $invkp_invoice_no_gen_incr, -1, PREG_SPLIT_NO_EMPTY);
    $invkp_invoice_no_gen_chars = preg_split('//', $invkp_invoice_no_gen, -1, 0);

    // Generate final invoice number. Replace x characters with increment number (from right to left). 
    $j = count($invkp_invoice_no_gen_incr_chars); // set array length of increment number
    $invkp_invoice_no = '';
    for ($i = count($invkp_invoice_no_gen_chars); $i > 0; $i--) {

                    if (preg_match("/#/i", $invkp_invoice_no_gen_chars[$i]) ) {
                                    $j--;
                                    $invkp_invoice_no_gen_chars[$i] = $invkp_invoice_no_gen_incr_chars[$j];
                                    $invkp_invoice_no = $invkp_invoice_no_gen_incr_chars[$j].$invkp_invoice_no;
                    } else {
                                    $invkp_invoice_no = $invkp_invoice_no_gen_chars[$i].$invkp_invoice_no;
                    }
    }

    return $invkp_invoice_no;
} // function end

function invkp_gen_filename($invoice_data) {
    $pdf_filename = get_option('invkp_pdf_filename');
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
            str_replace(array(' '), array('-'), strtolower($invoice_data->custom['invkp_company_name'][0])),
            date('Y-m-d', current_time('timestamp')),
            $invoice_data->custom['invkp_invoice_no'][0],
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
            str_replace(array(' '), array('-'), strtolower($invoice_data->custom['invkp_company_name'][0])),
            date('Y-m-d', current_time('timestamp')),
            $invoice_data->custom['invkp_invoice_no'][0],
            '-'
        );
        
        $filename = str_replace($search, $replace, $pdf_filename).'-'.$invoice_data->custom['invkp_invoice_no'][0];
    }
    
    $filename = preg_replace('/[^A-Za-z0-9\-_]/', '', $filename); // Removes special chars.

    return preg_replace('/-+/', '-', $filename);
}

// Our own update_option() function, which works from cron jobs.
function invkp_update_option( $option, $newvalue ) {	
	global $wpdb;
	$result = $wpdb->update( $wpdb->options, array( 'option_value' => $newvalue ), array( 'option_name' => $option ) );
	return true;
}


// Process the custom metabox fields
function invkp_return_fields( $id = NULL ) {
	global $post;
        if (is_null($id)) $id = $post->ID;
	$output = array();
        $output['invkp_company_name'] = get_post_meta( $id, 'invkp_company_name' );
        if (!isset($output['invkp_company_name'][0])) $output['invkp_company_name'][0] = get_option('invkp_company_name');
        
        $output['invkp_invoice_type'] = get_post_meta( $id, 'invkp_invoice_type' );
        if (!isset($output['invkp_invoice_type'][0])) $output['invkp_invoice_type'][0] = get_option('invkp_invoice_type');
        
        $output['invkp_address'] = get_post_meta( $id, 'invkp_address' );
        if (!isset($output['invkp_address'][0])) $output['invkp_address'][0] = get_option('invkp_address');
        $output['invkp_suburb'] = get_post_meta( $id, 'invkp_suburb' );
        if (!isset($output['invkp_suburb'][0])) $output['invkp_suburb'][0] = get_option('invkp_suburb');
        $output['invkp_state'] = get_post_meta( $id, 'invkp_state' );
        if (!isset($output['invkp_state'][0])) $output['invkp_state'][0] = get_option('invkp_state');
        $output['invkp_postcode'] = get_post_meta( $id, 'invkp_postcode' );
        if (!isset($output['invkp_postcode'][0])) $output['invkp_postcode'][0] = get_option('invkp_postcode');
        $output['invkp_date'][0] = (get_post_meta( $id, 'invkp_date', true ) ? get_post_meta( $id, 'invkp_date', true ) : get_the_date());
        $output['invkp_due_date'][0] = (get_post_meta( $id, 'invkp_due_date' ) ? get_post_meta( $id, 'invkp_due_date' ) : array(''));
        $output['invkp_invoice_no_label'] = get_post_meta( $id, 'invkp_invoice_no_label' );
        if (!isset($output['invkp_invoice_no_label'][0])) $output['invkp_invoice_no_label'][0] = get_option('invkp_invoice_no_label');
        
        $output['invkp_invoice_no'] = (get_post_meta( $id, 'invkp_invoice_no' ) ? get_post_meta( $id, 'invkp_invoice_no' ) : array( invkp_generate_invoice_no($id) ) );
        
        $output['invkp_po_label'] = get_post_meta( $id, 'invkp_po_label' );
        if (!isset($output['invkp_po_label'][0])) $output['invkp_po_label'][0] = get_option('invkp_po_label');
        $output['invkp_po'] = (get_post_meta( $id, 'invkp_po' ) ? get_post_meta( $id, 'invkp_po' ) : array(''));
        $output['invkp_tax_label'] = get_post_meta( $id, 'invkp_tax_label' );
        if (!isset($output['invkp_tax_label'][0])) $output['invkp_tax_label'][0] = get_option('invkp_tax_label');
        
        $output['invkp_tax_value'] = get_post_meta( $id, 'invkp_tax_value' );
        if (!isset($output['invkp_tax_value'][0])) $output['invkp_tax_value'][0] = get_option('invkp_tax_value');
        
        $output['invkp_subtotal_label'] = get_post_meta( $id, 'invkp_subtotal_label' );
        if (!isset($output['invkp_subtotal_label'][0])) $output['invkp_subtotal_label'][0] = get_option('invkp_subtotal_label');
        
        $output['invkp_discount_label'] = get_post_meta( $id, 'invkp_discount_label' );
        if (!isset($output['invkp_discount_label'][0])) $output['invkp_discount_label'][0] = get_option('invkp_discount_label');
        
        $output['invkp_total_label'] = get_post_meta( $id, 'invkp_total_label' );
        if (!isset($output['invkp_total_label'][0])) $output['invkp_total_label'][0] = get_option('invkp_total_label');
        
        $output['invkp_payment_terms'] = (get_post_meta( $id, 'invkp_payment_terms' ) ? get_post_meta( $id, 'invkp_payment_terms' ) : array(''));
        
        $output['invkp_phone'] = get_post_meta( $id, 'invkp_phone' );
        if (!isset($output['invkp_phone'][0])) $output['invkp_phone'][0] = get_option('invkp_phone');
        $output['invkp_email'] = get_post_meta( $id, 'invkp_email' );
        if (!isset($output['invkp_email'][0])) $output['invkp_email'][0] = get_option('invkp_email');
        $output['invkp_add_detail'] = get_post_meta( $id, 'invkp_add_detail' );
        if (!isset($output['invkp_add_detail'][0])) $output['invkp_add_detail'][0] = get_option('invkp_add_detail');
        $output['invkp_attn_name_label'] = get_post_meta( $id, 'invkp_attn_name_label' );
        if (!isset($output['invkp_attn_name_label'][0])) $output['invkp_attn_name_label'][0] = get_option('invkp_attn_name_label');
        $output['invkp_attn_name'] = (get_post_meta( $id, 'invkp_attn_name' ) ? get_post_meta( $id, 'invkp_attn_name' ) : array(''));
        $output['invkp_client_company'] = (get_post_meta( $id, 'invkp_client_company' ) ? get_post_meta( $id, 'invkp_client_company' ) : array(''));
        $output['invkp_client_address'] = (get_post_meta( $id, 'invkp_client_address' ) ? get_post_meta( $id, 'invkp_client_address' ) : array(''));
        $output['invkp_client_suburb'] = (get_post_meta( $id, 'invkp_client_suburb' ) ? get_post_meta( $id, 'invkp_client_suburb' ) : array(''));
        $output['invkp_client_state'] = (get_post_meta( $id, 'invkp_client_state' ) ? get_post_meta( $id, 'invkp_client_state' ) : array(''));
        $output['invkp_client_postcode'] = (get_post_meta( $id, 'invkp_client_postcode' ) ? get_post_meta( $id, 'invkp_client_postcode' ) : array(''));
        $output['invkp_client_email'] = (get_post_meta( $id, 'invkp_client_email' ) ? get_post_meta( $id, 'invkp_client_email' ) : array(''));
        $output['invkp_client_phone'] = (get_post_meta( $id, 'invkp_client_phone' ) ? get_post_meta( $id, 'invkp_client_phone' ) : array(''));
        
        $output['invkp_selected_client_company'] = (get_post_meta( $id, 'invkp_selected_client_company' ) ? get_post_meta( $id, 'invkp_selected_client_company' ) : array(''));
        $output['invkp_selected_client_attn'] = (get_post_meta( $id, 'invkp_selected_client_attn' ) ? get_post_meta( $id, 'invkp_selected_client_attn' ) : array(''));
        $output['invkp_selected_client_address'] = (get_post_meta( $id, 'invkp_selected_client_address' ) ? get_post_meta( $id, 'invkp_selected_client_address' ) : array(''));
        $output['invkp_selected_client_suburb'] = (get_post_meta( $id, 'invkp_selected_client_suburb' ) ? get_post_meta( $id, 'invkp_selected_client_suburb' ) : array(''));
        $output['invkp_selected_client_state'] = (get_post_meta( $id, 'invkp_selected_client_state' ) ? get_post_meta( $id, 'invkp_selected_client_state' ) : array(''));
        $output['invkp_selected_client_postcode'] = (get_post_meta( $id, 'invkp_selected_client_postcode' ) ? get_post_meta( $id, 'invkp_selected_client_postcode' ) : array(''));
        $output['invkp_selected_client_email'] = (get_post_meta( $id, 'invkp_selected_client_email' ) ? get_post_meta( $id, 'invkp_selected_client_email' ) : array(''));
        $output['invkp_selected_client_phone'] = (get_post_meta( $id, 'invkp_selected_client_phone' ) ? get_post_meta( $id, 'invkp_selected_client_phone' ) : array(''));
        
        $output['invkp_column'] = (get_post_meta( $id, 'invkp_column' ) ? get_post_meta( $id, 'invkp_column' ) : array(''));
        $output['invkp_subtotal'] = (get_post_meta( $id, 'invkp_subtotal' ) ? get_post_meta( $id, 'invkp_subtotal' ) : array(''));
        $output['invkp_discount'] = (get_post_meta( $id, 'invkp_discount' ) ? get_post_meta( $id, 'invkp_discount' ) : array(''));
        $output['invkp_gst'] = (get_post_meta( $id, 'invkp_gst' ) ? get_post_meta( $id, 'invkp_gst' ) : array(''));
        $output['invkp_total'] = (get_post_meta( $id, 'invkp_total' ) ? get_post_meta( $id, 'invkp_total' ) : array(''));
        $output['invkp_open_content_1'] = get_post_meta( $id, 'invkp_open_content_1' );
        if (!isset($output['invkp_open_content_1'][0])) $output['invkp_open_content_1'][0] = get_option('invkp_open_content_1');
        $output['invkp_open_content_2'] = get_post_meta( $id, 'invkp_open_content_2' );
        if (!isset($output['invkp_open_content_2'][0])) $output['invkp_open_content_2'][0] = get_option('invkp_open_content_2');
        
        $output['invkp_columns'] = get_post_meta( $id, 'invkp_columns' );
        if (!isset($output['invkp_columns'][0])) $output['invkp_columns'][0] = serialize(get_option('invkp_columns'));
        $output['invkp_column_types'] = get_post_meta( $id, 'invkp_column_types' );
        if (!isset($output['invkp_column_types'][0])) $output['invkp_column_types'][0] = serialize(get_option('invkp_column_types'));
        $output['invkp_column_widths'] = get_post_meta( $id, 'invkp_column_widths' );
        if (!isset($output['invkp_column_widths'][0])) $output['invkp_column_widths'][0] = serialize(get_option('invkp_column_widths'));
        $output['invkp_calculate_rows'] = get_post_meta( $id, 'invkp_calculate_rows' );
        if (!isset($output['invkp_calculate_rows'][0])) $output['invkp_calculate_rows'][0] = serialize(get_option('invkp_calculate_rows'));
        $output['invkp_calculate_operators'] = get_post_meta( $id, 'invkp_calculate_operators' );
        if (!isset($output['invkp_calculate_operators'][0])) $output['invkp_calculate_operators'][0] = serialize(get_option('invkp_calculate_operators'));
        $output['invkp_calculate_subtotal'] = get_post_meta( $id, 'invkp_calculate_subtotal' );
        if (!isset($output['invkp_calculate_subtotal'][0])) $output['invkp_calculate_subtotal'][0] = get_option('invkp_calculate_subtotal');
        
        $output['invkp_custom_data_1'] = (get_post_meta( $id, 'invkp_custom_data_1' ) ? get_post_meta( $id, 'invkp_custom_data_1' ) : array(''));
        $output['invkp_custom_data_2'] = (get_post_meta( $id, 'invkp_custom_data_2' ) ? get_post_meta( $id, 'invkp_custom_data_2' ) : array(''));
        $output['invkp_custom_data_3'] = (get_post_meta( $id, 'invkp_custom_data_3' ) ? get_post_meta( $id, 'invkp_custom_data_3' ) : array(''));
        $output['invkp_custom_data_4'] = (get_post_meta( $id, 'invkp_custom_data_4' ) ? get_post_meta( $id, 'invkp_custom_data_4' ) : array(''));
        $output['invkp_custom_data_5'] = (get_post_meta( $id, 'invkp_custom_data_5' ) ? get_post_meta( $id, 'invkp_custom_data_5' ) : array(''));
        $output['invkp_custom_data_6'] = (get_post_meta( $id, 'invkp_custom_data_6' ) ? get_post_meta( $id, 'invkp_custom_data_6' ) : array(''));
        $output['invkp_custom_data_7'] = (get_post_meta( $id, 'invkp_custom_data_7' ) ? get_post_meta( $id, 'invkp_custom_data_7' ) : array(''));
        $output['invkp_custom_data_8'] = (get_post_meta( $id, 'invkp_custom_data_8' ) ? get_post_meta( $id, 'invkp_custom_data_8' ) : array(''));
        $output['invkp_custom_data_9'] = (get_post_meta( $id, 'invkp_custom_data_9' ) ? get_post_meta( $id, 'invkp_custom_data_9' ) : array(''));
        $output['invkp_custom_data_10'] = (get_post_meta( $id, 'invkp_custom_data_10' ) ? get_post_meta( $id, 'invkp_custom_data_10' ) : array(''));
        
        $output['invkp_paid_invoice'] = (get_post_meta( $id, 'invkp_paid_invoice' ) ? get_post_meta( $id, 'invkp_paid_invoice' ) : array('0'));
        
        return $output;
}

function invkp_save_client_custom_fields( ) {
	global $post;	
        
        // verify nonce
        if (!isset($_POST['invkp_client_meta_box_nonce']) || !wp_verify_nonce($_POST['invkp_client_meta_box_nonce'], basename(__FILE__))) {
            return;
        }
	
	if( $_POST ) {
            if (isset($_POST['invkp_client_company_name']))
                update_post_meta( $post->ID, 'invkp_client_company_name', $_POST['invkp_client_company_name'] );
            if (isset($_POST['invkp_client_attn_name']))
                update_post_meta( $post->ID, 'invkp_client_attn_name', $_POST['invkp_client_attn_name'] );
            if (isset($_POST['invkp_client_address']))
                update_post_meta( $post->ID, 'invkp_client_address', $_POST['invkp_client_address'] );
            if (isset($_POST['invkp_client_suburb']))
                update_post_meta( $post->ID, 'invkp_client_suburb', $_POST['invkp_client_suburb'] );
            if (isset($_POST['invkp_client_state']))
                update_post_meta( $post->ID, 'invkp_client_state', $_POST['invkp_client_state'] );
            if (isset($_POST['invkp_client_postcode']))
                update_post_meta( $post->ID, 'invkp_client_postcode', $_POST['invkp_client_postcode'] );
            if (isset($_POST['invkp_client_email']))
                update_post_meta( $post->ID, 'invkp_client_email', $_POST['invkp_client_email'] );
            if (isset($_POST['invkp_client_phone']))
                update_post_meta( $post->ID, 'invkp_client_phone', $_POST['invkp_client_phone'] );
            
	}
}

add_action( 'save_post', 'invkp_save_client_custom_fields' );

// Process the custom metabox fields
function invkp_return_client_fields( $id = NULL ) {
	global $post;
        if (is_null($id)) $id = $post->ID;
	$output = array();
        
        $output['invkp_client_company_name'] = (get_post_meta( $id, 'invkp_client_company_name' ) ? get_post_meta( $post->ID, 'invkp_client_company_name' ) : array(''));
        $output['invkp_client_attn_name'] = (get_post_meta( $id, 'invkp_client_attn_name' ) ? get_post_meta( $post->ID, 'invkp_client_attn_name' ) : array(''));
        $output['invkp_client_address'] = (get_post_meta( $id, 'invkp_client_address' ) ? get_post_meta( $post->ID, 'invkp_client_address' ) : array(''));
        $output['invkp_client_suburb'] = (get_post_meta( $id, 'invkp_client_suburb' ) ? get_post_meta( $post->ID, 'invkp_client_suburb' ) : array(''));
        $output['invkp_client_state'] = (get_post_meta( $id, 'invkp_client_state' ) ? get_post_meta( $post->ID, 'invkp_client_state' ) : array(''));
        $output['invkp_client_postcode'] = (get_post_meta( $id, 'invkp_client_postcode' ) ? get_post_meta( $post->ID, 'invkp_client_postcode' ) : array(''));
        $output['invkp_client_email'] = (get_post_meta( $id, 'invkp_client_email' ) ? get_post_meta( $post->ID, 'invkp_client_email' ) : array(''));
        $output['invkp_client_phone'] = (get_post_meta( $id, 'invkp_client_phone' ) ? get_post_meta( $post->ID, 'invkp_client_phone' ) : array(''));
        
        return $output;
}

// Remove the Permalinks
function invkp_perm($return, $id, $new_title, $new_slug){
    global $post;
    if(isset($post->post_type) && $post->post_type == 'invkp_invoices') return '';
    return $return;
}
add_filter('get_sample_permalink_html', 'invkp_perm', '', 4);

// Dashboard Widget

function invkp_enqueue($hook) {
    if (invkp_check_page($hook)) :
        wp_register_style( 'invkp_jquery_ui', plugins_url('css/jquery-ui.css', dirname(__FILE__)), false, '1.9.2' );
        wp_register_style( 'invkp_css', plugins_url('css/invoicekingpro-styles.css', dirname(__FILE__)), false, '1.0.0' );
        $theme = get_option('invkp_theme');
        wp_register_style( 'invkp_'.$theme.'_css', plugins_url('themes/'.$theme.'/styles.css', dirname(__FILE__)), false, '1.0.0' );
        wp_register_style( 'fontawesome', plugins_url('css/font-awesome.min.css', dirname(__FILE__)), false, '3.2.1');

        wp_enqueue_style( 'invkp_jquery_ui' );
        wp_enqueue_style( 'invkp_css' );
        wp_enqueue_style( 'invkp_'.$theme.'_css' );
        wp_enqueue_style( 'fontawesome' );
        wp_enqueue_style( 'thickbox' );

        wp_enqueue_script( 'jquery-ui-datepicker');
        wp_register_script( 'invkp_elastic', plugins_url( '/js/jquery.elastic.source.js', dirname(__FILE__) ), array('jquery'), '1.6.11');
        wp_register_script( 'invkp_admin_js', plugins_url( '/js/invoicekingpro-admin-functions.js', dirname(__FILE__) ), array('jquery'), '1.0.0');

        wp_enqueue_script( 'invkp_elastic' );
        wp_enqueue_script( 'invkp_admin_js' );
        wp_enqueue_script( 'thickbox' );

        // in javascript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
        wp_localize_script( 'invkp_admin_js', 'invkp_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'invkp_ajaxnonce' => wp_create_nonce( 'invkpN0nc3' ) ) );
        
        // For attachments plugin
        wp_enqueue_media();
    endif;
}
add_action( 'admin_enqueue_scripts', 'invkp_enqueue' );

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

function invkp_add_parent_page() {
  if(!find_kpp_menu_item('kpp_menu')) {
    add_menu_page('King Pro Plugins','King Pro Plugins', 'manage_options', 'kpp_menu', 'kpp_menu_page');
  }
//  if(!function_exists('remove_submenu_page')) {
//    unset($GLOBALS['submenu']['kpp_menu'][0]);
//  }
//  else {
//    remove_submenu_page('kpp_menu','kpp_menu');
//  }
  
  add_submenu_page('kpp_menu', 'Invoice King Pro', 'Invoice King Pro', 'manage_options', 'invoicekingpro', 'invkp_settings_output');
}
add_action('admin_menu', 'invkp_add_parent_page');

if(!function_exists('kpp_menu_page')) {
    function kpp_menu_page() {
        include 'screens/kpp.php';
    }
}

function on_update_invkp_column_widths($new_value, $old_value = 0) {
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
add_action('pre_update_option_invkp_column_widths', 'on_update_invkp_column_widths');

function register_invkp_options() {
    
    register_setting( 'invkp-options', 'invkp_theme' );
    
    register_setting( 'invkp-options', 'invkp_revenue_currency' );
  
    register_setting( 'invkp-options', 'invkp_company_name' );
    register_setting( 'invkp-options', 'invkp_address' );
    register_setting( 'invkp-options', 'invkp_suburb' );
    register_setting( 'invkp-options', 'invkp_state' );
    register_setting( 'invkp-options', 'invkp_postcode' );
    register_setting( 'invkp-options', 'invkp_phone' );
    register_setting( 'invkp-options', 'invkp_email' );
    register_setting( 'invkp-options', 'invkp_bcc' );
    register_setting( 'invkp-options', 'invkp_add_detail' );

    register_setting( 'invkp-options', 'invkp_invoice_type' );
    register_setting( 'invkp-options', 'invkp_paid_invoice_type' );
    register_setting( 'invkp-options', 'invkp_paid_watermark' );
    register_setting( 'invkp-options', 'invkp_invoice_no_label' );
    register_setting( 'invkp-options', 'invkp_po_label' );
    register_setting( 'invkp-options', 'invkp_attn_name_label' );
    register_setting( 'invkp-options', 'invkp_tax_label' );
    register_setting( 'invkp-options', 'invkp_tax_value' );
    register_setting( 'invkp-options', 'invkp_subtotal_label' );
    register_setting( 'invkp-options', 'invkp_discount_label' );
    register_setting( 'invkp-options', 'invkp_total_label' );
    
    register_setting( 'invkp-options', 'invkp_open_content_1' );
    register_setting( 'invkp-options', 'invkp_open_content_2' );
    
    register_setting( 'invkp-options', 'invkp_columns' );
    register_setting( 'invkp-options', 'invkp_column_types' );
    register_setting( 'invkp-options', 'invkp_column_widths' );
    
    register_setting( 'invkp-options', 'invkp_invoice_no_gen' );
    register_setting( 'invkp-options', 'invkp_invoice_no_gen_last' );
    register_setting( 'invkp-options', 'invkp_invoice_no_gen_incr' );
    register_setting( 'invkp-options', 'invkp_invoice_last_post_id' );
    register_setting( 'invkp-options', 'invkp_pdf_filename' );
    
    register_setting( 'invkp-options', 'invkp_calculate_rows' );
    register_setting( 'invkp-options', 'invkp_calculate_operators' );

    register_setting( 'invkp-options', 'invkp_calculate_subtotal' );
    
    register_setting( 'invkp-options', 'invkp_from');
    register_setting( 'invkp-options', 'invkp_from_email');
    register_setting( 'invkp-options', 'invkp_email_subject');
    register_setting( 'invkp-options', 'invkp_email_message');
    register_setting( 'invkp-options', 'invkp_paid_email_subject');
    register_setting( 'invkp-options', 'invkp_paid_email_message');
    
    do_action('register_additional_invkp_options');
}
add_action( 'admin_init', 'register_invkp_options' );

function invkp_settings_output() {
    include 'screens/settings.php';
} 
?>