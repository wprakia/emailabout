<?php 
/*
 Plugin Name: WPRakia EMAILABOUT Lite
 Plugin URI: https://github.com/wprakia/emailabout
 Description: All about WP emails :)
 Author: Slavco Mihajloski
 Version: 1.0
 Author URI: https://medium.com/websec
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//define them in your wp-config.php or change values here into something appropriate/unique for your web site :-)
if ( !defined("WPRAKIA_ADMIN_EMAIL_HELLO")  ) {
    
define("WPRAKIA_ADMIN_EMAIL_HELLO", "
******************************************
*                                        *
*             hELLO Dolly                *
*                                        *
******************************************
");

}

if ( !defined("WPRAKIA_ADMIN_EMAIL_HELLO_WSOD")  ){
    
define("WPRAKIA_ADMIN_EMAIL_HELLO_WSOD",  "
************************************************************************************************
When I receive recovery email from my website WSOD this will be first part of the email message!
************************************************************************************************
");

}

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



function wp_rakia_mark_admin_email($in = array()){
    
    if ( ! is_array($in) ) return array();//against coding standards :-)
    
    if ( isset($in['to']) && get_option('admin_email') === $in['to'] && is_email($in['to']) ){
        
        if ( isset($in['message']) ){
            
            $in['message'] = WPRAKIA_ADMIN_EMAIL_HELLO.$in['message'];
        
        }else{
            
            $in['message'] = WPRAKIA_ADMIN_EMAIL_HELLO;
        
        }
        
    }
    return $in;
    
}

add_filter("wp_mail", "wp_rakia_mark_admin_email", 1, 1);



function wp_rakia_mark_recovery_email($in_email = array(), $in_url = ""){
    
    $local_to = "";
    
    if ( defined("RECOVERY_MODE_EMAIL") ){
        
        $local_to = RECOVERY_MODE_EMAIL;
    
    }else{
        
        $local_to = get_option('admin_email');
    
    }
    
    if (  is_email($local_to) && isset($in_email['to']) && $local_to === $in_email['to'] ){
        
        if ( isset($in_email['message']) ){
            
            $in_email['message'] = WPRAKIA_ADMIN_EMAIL_HELLO_WSOD.$in_email['message'];
            
        }else{
            
            $in_email['message'] = WPRAKIA_ADMIN_EMAIL_HELLO_WSOD;
            
        }
    }
    
    return $in_email;
}

add_filter("recovery_mode_email", "wp_rakia_mark_recovery_email", 1, 2);
