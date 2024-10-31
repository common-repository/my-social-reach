<?php
/**
 * Plugin Name:       My Social Reach
 * Plugin URI:        https://www.tectera.com/my-social-reach-wp-plugin/
 * Description:       Social Share Plugin. Reach more with "My Social Share"
 * Author:            Tectera
 * Author URI:        https://www.tectera.com
 * Version:           1.0.0
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       zpt-my-social-reach
 * 
 * 
*/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

define( "ZPTMSSPATH", dirname( __FILE__ ) );

define( "ZPTMSSURL", plugin_dir_url( __FILE__ ) );

include_once( ZPTMSSPATH.'/autoload.php' );

register_activation_hook(__FILE__, 'zpt_social_share_links_activation');

register_deactivation_hook(__FILE__, 'zpt_social_share_deactivation');


function zpt_social_share_links_activation(){
    global $wpdb;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
    $share_social_links = ZPT_ADMIN_SETTING_SHARE_LINKS;
    if($wpdb->get_var("SHOW TABLES LIKE '$share_social_links'") != $share_social_links) {
        $share_social_links_query = "CREATE TABLE $share_social_links (
        id int(11) NOT NULL AUTO_INCREMENT,
        facebook boolean  NOT NULL DEFAULT 1,
        telegram boolean  NOT NULL DEFAULT 1,
        twitter boolean  NOT NULL DEFAULT 1,

        pinterest boolean  NOT NULL DEFAULT 1, 
        
        linkedin boolean  NOT NULL DEFAULT 1,
        email boolean  NOT NULL DEFAULT 1,
        whatsapp boolean  NOT NULL DEFAULT 1,

        PRIMARY KEY (id)
        )";
        dbDelta($share_social_links_query); 
        
        $wpdb->insert( 
            ZPT_ADMIN_SETTING_SHARE_LINKS, 
            array(
                'facebook'  =>  1,
                'telegram'  =>  1,
                'twitter'   =>  1,
                'pinterest' =>  1,
                'linkedin'  =>  1,
                'email'     =>  1,
                'whatsapp'  =>  1
            )
        );
        
    }    
    
    $share_social_links_setting = ZPT_SHARE_LINKS_COUNT;
    if($wpdb->get_var("SHOW TABLES LIKE '$share_social_links_setting'") != $share_social_links_setting) {
        $share_social_links_query = "CREATE TABLE $share_social_links_setting (
        id int(11) NOT NULL AUTO_INCREMENT,
        facebook text  NULL,
        twitter text  NULL,
        telegram text  NULL,
        pinterest text  NULL, 
        linkedin text  NULL,
        email text  NULL, 
        whatsapp text NULL,
        url text NOT NULL,
        PRIMARY KEY (id)
        )";
        dbDelta($share_social_links_query); 
        
    }
    
}

function zpt_social_share_deactivation(){
    
}




