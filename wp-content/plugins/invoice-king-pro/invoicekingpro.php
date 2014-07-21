<?php
/*
    Plugin Name: Invoice King Pro
    Plugin URI: http://kingpro.me/plugins/invoice-king-pro/
    Description: Invoice King Pro allows you to create, send and manage invoices for whatever purpose. If AdKingPro is installed as well, automatically generate invoices for revenue outputs.
    Version: 1.1.7
    Author: Ash Durham
    Author URI: http://durham.net.au/
    License: GPL2

    Copyright 2013  Ash Durham  (email : plugins@kingpro.me)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

    // INSTALL

    global $invkp_db_version;
    $invkp_db_version = "1.1.7";
    $invkp_safe_theme = '1.2';
    
    function invkp_install() {
       global $wpdb;
       global $invkp_db_version;
       global $invkp_safe_theme;

       require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
       
       add_option("invpk_db_version", $invkp_db_version);
       add_option('invkp_safe_theme', $invkp_safe_theme);
    }
    
    // Register hooks at activation
    register_activation_hook(__FILE__,'invkp_install');
    
    // END INSTALL
    
    function invkp_languages_init() {
        load_plugin_textdomain('invkptext', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }
    add_action('plugins_loaded', 'invkp_languages_init');
    
    if (get_option("invpk_db_version") != $invkp_db_version) {
        // Execute your upgrade logic here
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Then update the version value
        update_option("invpk_db_version", $invkp_db_version);
        update_option('invkp_safe_theme', $invkp_safe_theme);
    }
    
    function invkp_settings_link($action_links,$plugin_file){
            if($plugin_file==plugin_basename(__FILE__)){
                    $invkp_settings_link = '<a href="admin.php?page=' . str_replace('-', '', dirname(plugin_basename(__FILE__))) . '">' . __("Settings") . '</a>';
                    array_unshift($action_links,$invkp_settings_link);
            }
            return $action_links;
    }
    add_filter('plugin_action_links','invkp_settings_link',10,2);
    
    require_once plugin_dir_path(__FILE__).'includes/admin_area.php';
    require_once plugin_dir_path(__FILE__).'js/invoicekingpro-js.php';
    
?>