<?php
/*
    Plugin Name: BB Agency Invoices
    Plugin URI: http://www.turnpiece.com/
    Description: BB Agency Invoices allows you to create, send and manage invoices to your clients. This plugin has been adapted from Ash Durham's Invoice King Pro.
    Version: 1.1.8
    Author: Ash Durham & Paul Jenkins
    Author URI: http://www.turnpiece.com/
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

    global $bbinv_db_version;
    $bbinv_db_version = "1.1.8";
    $bbinv_safe_theme = '1.2';
    
    function bbinv_install() {
       global $wpdb;
       global $bbinv_db_version;
       global $bbinv_safe_theme;

       require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
       
       add_option("invpk_db_version", $bbinv_db_version);
       add_option('bbinv_safe_theme', $bbinv_safe_theme);
    }
    
    // Register hooks at activation
    register_activation_hook(__FILE__,'bbinv_install');
    
    // END INSTALL
    
    function bbinv_languages_init() {
        load_plugin_textdomain('bbinvtext', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }
    add_action('plugins_loaded', 'bbinv_languages_init');
    
    if (get_option("invpk_db_version") != $bbinv_db_version) {
        // Execute your upgrade logic here
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Then update the version value
        update_option("invpk_db_version", $bbinv_db_version);
        update_option('bbinv_safe_theme', $bbinv_safe_theme);
    }
    
    function bbinv_settings_link($action_links,$plugin_file){
            if($plugin_file==plugin_basename(__FILE__)){
                    $bbinv_settings_link = '<a href="admin.php?page=' . str_replace('-', '', dirname(plugin_basename(__FILE__))) . '">' . __("Settings") . '</a>';
                    array_unshift($action_links,$bbinv_settings_link);
            }
            return $action_links;
    }
    add_filter('plugin_action_links','bbinv_settings_link',10,2);
    
    require_once plugin_dir_path(__FILE__).'includes/admin_area.php';
    require_once plugin_dir_path(__FILE__).'js/bb-agency-invoices-js.php';
    
?>