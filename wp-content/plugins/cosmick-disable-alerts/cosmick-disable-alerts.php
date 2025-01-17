<?php
/**
 * Plugin Name: Control WP alerts
 * Plugin URI: https://www.cosmicktechnologies.com/
 * Description: Plugin for disabling update alerts and XML-RPC (responsible for pingback comment moderation) alerts
 * Version: 1.0.0
 * Author: Cosmick Technologies
 * Author URI: https://www.cosmicktechnologies.com/
 * License: GPLv2
**/

require 'update/plugin-update-checker.php';    
$update_check = PucFactory::buildUpdateChecker( 'http://cosmickupdates.com/updates/plugins/?action=get_metadata&slug=cosmick-disable-alerts', __FILE__ );

/*
 * Disable pingbacks. Comment moderation due to pingback
 */
add_filter( 'xmlrpc_enabled', 'wp_cosmick_xmlrpc_enabled' );

function wp_cosmick_xmlrpc_enabled( $is_enabled ){
    
    if(wp_cosmick_check_alert( 'xmlrpc' )){
        return false;
    }
    
    return $is_enabled;
}

/*
 * Disable theme update notification.
 */
add_filter( 'auto_theme_update_send_email', 'wp_cosmick_auto_theme_update_send_email' );

function wp_cosmick_auto_theme_update_send_email( $is_enabled ){
    
    if(wp_cosmick_check_alert( 'theme' )){
        return false;
    }
    
    return $is_enabled;
}

/*
 * Disable plugin update notification.
 */
add_filter( 'auto_plugin_update_send_email', 'wp_cosmick_auto_plugin_update_send_email' );

function wp_cosmick_auto_plugin_update_send_email( $is_enabled ){
    
    if(wp_cosmick_check_alert( 'plugin' )){
        return false;
    }
    
    return $is_enabled;
}

/*
 * Disable send a debugging email for each automatic background update.
 */
add_filter( 'automatic_updates_send_debug_email', 'wp_cosmick_automatic_updates_send_debug_email' );

function wp_cosmick_automatic_updates_send_debug_email( $is_enabled ){
    
    if(wp_cosmick_check_alert( 'wpdebug' )){
        return false;
    }
    
    return $is_enabled;
}

/*
 * Disable WP core update email notification if the update was successful.
 */
add_filter( 'auto_core_update_send_email', 'wp_cosmick_stop_auto_update_emails', 10, 4 );
  
function wp_cosmick_stop_auto_update_emails( $send, $type, $core_update, $result ) {
    
    if ( ! empty( $type ) && $type == 'success' ) {
        return false;
    }
    
    return true;    
}

function wp_cosmick_check_alert( $option ){
    
    if(empty($option)){
        return false;
    }
    
    $cosmick_alerts_options = get_option('cosmick_alerts_options', get_default_cosmick_alerts_options() );
    
    if( isset($cosmick_alerts_options[$option]) && $cosmick_alerts_options[$option] ){
        return true;
    }
    
    return false;
}

/*
 * Add option page. If need individual control over the notification
 */

require ( dirname(__FILE__) . '/settings.php' );
