<?php

function invkp_save_client() {
    if (wp_verify_nonce( $_POST['ajaxnonce'], 'invkpN0nc3' )) {
	global $wpdb;
        // Check if client email exists
        $existing_client = query_posts(array('post_type'=>'invkp_clients', 'meta_key'=>'invkp_client_email', 'meta_value'=>$_POST['email']));
        
        if (empty($existing_client)) :
            $post_id = wp_insert_post(array(
                'post_title'=>$_POST['company_name'],
                'post_type'=>'invkp_clients',
                'post_status'=>'publish'
            ));

            update_post_meta( $post_id, 'invkp_client_attn_name', $_POST['attn_name'] );
            update_post_meta( $post_id, 'invkp_client_address', $_POST['address'] );
            update_post_meta( $post_id, 'invkp_client_suburb', $_POST['suburb'] );
            update_post_meta( $post_id, 'invkp_client_state', $_POST['state'] );
            update_post_meta( $post_id, 'invkp_client_postcode', $_POST['postcode'] );
            update_post_meta( $post_id, 'invkp_client_email', $_POST['email'] );
            update_post_meta( $post_id, 'invkp_client_phone', $_POST['phone'] );
            
            $output['msg'] = "SAVED";
            $output['id'] = $post_id;
            echo json_encode($output);
        else :
            wp_update_post(array(
                'ID'=>$existing_client[0]->ID,
                'post_title'=>$_POST['company_name']
            ));
        
            update_post_meta( $existing_client[0]->ID, 'invkp_client_attn_name', $_POST['attn_name'] );
            update_post_meta( $existing_client[0]->ID, 'invkp_client_address', $_POST['address'] );
            update_post_meta( $existing_client[0]->ID, 'invkp_client_suburb', $_POST['suburb'] );
            update_post_meta( $existing_client[0]->ID, 'invkp_client_state', $_POST['state'] );
            update_post_meta( $existing_client[0]->ID, 'invkp_client_postcode', $_POST['postcode'] );
            update_post_meta( $existing_client[0]->ID, 'invkp_client_email', $_POST['email'] );
            update_post_meta( $existing_client[0]->ID, 'invkp_client_phone', $_POST['phone'] );
            $output['msg'] = "UPDATED";
            $output['id'] = $existing_client[0]->ID;
            echo json_encode($output);
        endif;
    }
    
    die();
}
add_action('wp_ajax_invkp_save_client', 'invkp_save_client');

?>
