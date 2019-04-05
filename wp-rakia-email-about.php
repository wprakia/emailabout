<?php 
/*
 Plugin Name: WPRakia EMAILABOUT Lite
 Plugin URI: https://github.com/wprakia/emailabout
 Description: All about WP emails :)
 Author: Slavco Mihajloski
 Version: 1.0
 Author URI: https://medium.com/websec
 */

function wp_rakia_stop_admin_email_leak( $errors, $sanitized_user_login, $user_email ){
    
    if ( mb_strlen(trim($sanitized_user_login)) > 60 ){
        
        if ( $errors instanceof WP_Error ){
            
            $errors->add( 'user_login_too_long', __( 'Username may not be longer than 60 characters.' ) );                           
        
        }else{
            
            return new WP_Error( 'user_login_too_long', __( 'Username may not be longer than 60 characters.' ) );
        
        }
    }
    
    return $errors;

}

add_filter("registration_errors", "wp_rakia_stop_admin_email_leak", 100, 3);
