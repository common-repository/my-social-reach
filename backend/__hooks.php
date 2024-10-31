<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}


add_action( 'admin_menu', 'zpt_share_social_hooker' );



/*This will handle appending the content to single posts:
*/
function zpt_share_social_the_content_hook( $content ) { 
    global $wpdb, $post;
    
    $post_slug = get_page_link($post);
    
    $settings = $wpdb->get_results( "SELECT * FROM ".ZPT_ADMIN_SETTING_SHARE_LINKS, ARRAY_A );
    
    if( is_single() ) {
        
        wp_enqueue_style( 'zpt-my-social-reach-fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', array(), '4.6.3' );
        wp_enqueue_style( 'zpt-my-social-reach', ZPTMSSURL.'assets/css/styles.css', array(), '1.0.0' );
        
        $content .= '<div class="zpt-container"><div class="zpt-share-icons">';
        
        if( isset( $settings[0]['facebook'] ) && $settings[0]['facebook'] ){
            $social_count = $wpdb->get_results( "SELECT COUNT(id) AS total FROM ".ZPT_SHARE_LINKS_COUNT." WHERE NOT facebook = '' AND url = '$post_slug' ", ARRAY_A );
            $content .= '<a data-href="'.$post_slug.'" data-to="https://www.facebook.com/sharer.php?u=" class="zpt-social-icon" data-type="facebook" title="Share on Facebook">
            <i class="fa fa-facebook"></i>
            <i class="zpt-c">'.$social_count[0]['total'].'</i>
            </a>';
        }
        
        if( isset( $settings[0]['twitter'] ) && $settings[0]['twitter']){
            $social_count_twitter = $wpdb->get_results( "SELECT COUNT(id) AS total FROM ".ZPT_SHARE_LINKS_COUNT." WHERE NOT twitter = '' AND url = '$post_slug' ", ARRAY_A );
            $content .= '<a data-href="'.$post_slug.'" class="zpt-social-icon" data-to="http://twitter.com/share?url=" data-type="twitter" title="Share on Twitter">
            <i class="fa fa-twitter"></i>
            <i class="zpt-c">'.$social_count_twitter[0]['total'].'</i></a>';
        }
        
        if( isset( $settings[0]['telegram'] ) ){
            $social_count_telegram = $wpdb->get_results( "SELECT COUNT(id) AS total FROM ".ZPT_SHARE_LINKS_COUNT." WHERE NOT telegram = '' AND url = '$post_slug' ", ARRAY_A );
            $content .= '<a data-href="'.$post_slug.'" class="zpt-social-icon" data-to="https://telegram.me/share/url?url=" data-type="telegram" title="Share on telegram">
            <i class="fa fa-telegram"></i>
            <i class="zpt-c">'.$social_count_telegram[0]['total'].'</i></a>';
            
        }
        if( isset( $settings[0]['pinterest'] ) && $settings[0]['pinterest']){
            $social_count_pinterest = $wpdb->get_results( "SELECT COUNT(id) AS total FROM ".ZPT_SHARE_LINKS_COUNT." WHERE NOT pinterest = '' AND url = '$post_slug' ", ARRAY_A );
            $content .= '<a data-href="'.$post_slug.'" class="zpt-social-icon" data-to="https://pinterest.com/pin/create/button/?url=" data-type="pinterest" title="Share on Pinterest">
            <i class="fa fa-pinterest"></i>
            <i class="zpt-c">'.$social_count_pinterest[0]['total'].'</i></a>';
        }
        if( isset( $settings[0]['linkedin'] ) && $settings[0]['linkedin']){
            $social_count_linkedin = $wpdb->get_results( "SELECT COUNT(id) AS total FROM ".ZPT_SHARE_LINKS_COUNT." WHERE NOT linkedin = '' AND url = '$post_slug' ", ARRAY_A );
            $content .= '<a data-href="'.$post_slug.'" class="zpt-social-icon" data-to="https://www.linkedin.com/sharing/share-offsite/?url=" data-type="linkedin" title="Share on LinkedIn">
            <i class="fa fa-linkedin"></i>
            <i class="zpt-c">'.$social_count_linkedin[0]['total'].'</i></a>';
        }
        if( isset( $settings[0]['whatsapp'] ) && $settings[0]['whatsapp'] ){
            $social_count_whatsapp = $wpdb->get_results( "SELECT COUNT(id) AS total FROM ".ZPT_SHARE_LINKS_COUNT." WHERE NOT whatsapp = '' AND url = '$post_slug' ", ARRAY_A );
            $content .= '<a data-href="'.$post_slug.'" class="zpt-social-icon" data-to="https://wa.me/?text=" data-type="whatsapp" title="Share on WhatsApp">
            <i class="fa fa-whatsapp"></i>
            <i class="zpt-c">'.$social_count_whatsapp[0]['total'].'</i></a>';
        }
        if( isset( $settings[0]['email'] ) && $settings[0]['email']){
            $social_count_email = $wpdb->get_results( "SELECT COUNT(id) AS total FROM ".ZPT_SHARE_LINKS_COUNT." WHERE NOT email = '' AND url = '$post_slug' ", ARRAY_A );
            $content .= '<a data-href="'.$post_slug.'" class="zpt-social-icon" data-to="mailto:?subject='.$post->post_title.'&body=" data-type="email" title="Share via Email">
            <i class="fa fa-envelope"></i>
            <i class="zpt-c">'.$social_count_email[0]['total'].'</i></a>';
        }
        
        $content .= '</div>'; 
        $content .= '</div>'; 
        
        /* loading script */
        wp_enqueue_script( 'zpt-my-social-reach', ZPTMSSURL.'assets/js/scripts.js', array('jquery'), '1.0.0' );
        wp_localize_script( 'zpt-my-social-reach', 'ZPTMSS',
            array( 
                'ajaxurl'   => admin_url( 'admin-ajax.php' ),
                's'         => wp_create_nonce( 'zptmss_ajax_nonce' ) 
            )
        );
        
        
    }
    return $content;
}
add_filter( 'the_content', 'zpt_share_social_the_content_hook' );

function zpt_share_social_hooker() {
	 
	add_menu_page('My Social Reach', 'My Social Reach', 'administrator', 'zpt-my-social-reach','zpt_social_share_admin_settings_page', 'dashicons-share');
	
}


function zpt_social_share_admin_settings_page(){
    
    global $wpdb;
    
    $settings = $wpdb->get_results( "SELECT * FROM ".ZPT_ADMIN_SETTING_SHARE_LINKS, ARRAY_A );
    
    if( isset ( $_POST['zpt_my_social_reach_settings_update'] ) ) {

        if ( ! isset( $_POST['zpt_my_social_reach_nonce'] ) 
            || ! wp_verify_nonce( $_POST['zpt_my_social_reach_nonce'], 'zpt_my_social_reach_nonce_' ) 
        ) {
            wp_die ( "Invalid Nonce. Reload the page and try again!" );
        }
        
        if( !isset( $settings[0]['facebook'] ) ){
            
            $settings = array();
            
        }
        
        if( isset( $_POST['zpts_facebook'] ) ){
            
            $settings[0]['facebook'] = 1;
            
        }else{
            
            $settings[0]['facebook'] = 0;
            
        }
       
        if( isset( $_POST['zpts_twitter'] ) ){
            
            $settings[0]['twitter'] = 1;
            
        }else{
            
            $settings[0]['twitter'] = 0;
            
        }
        
        if( isset( $_POST['zpts_telegram'] ) ){
            
            $settings[0]['telegram'] = 1;
            
        }else{
            
            $settings[0]['telegram'] = 0;
            
        }
        if( isset( $_POST['zpts_pinterest'] ) ){
            
            $settings[0]['pinterest'] = 1;
            
        }else{
            
            $settings[0]['pinterest'] = 0;
            
        }
        if( isset( $_POST['zpts_linkedin'] ) ){
            
            $settings[0]['linkedin'] = 1;
            
        }else{
            
            $settings[0]['linkedin'] = 0;
            
        }
        if( isset( $_POST['zpts_email'] ) ){
            
            $settings[0]['email'] = 1;
            
        }else{
            
            $settings[0]['email'] = 0;
            
        }
        if( isset( $_POST['zpts_whatsapp'] ) ){
            
            $settings[0]['whatsapp'] = 1;
            
        }else{
            
            $settings[0]['whatsapp'] = 0;
            
        }
        
        $wpdb->update( ZPT_ADMIN_SETTING_SHARE_LINKS, array(
            'facebook'  =>   $settings[0]['facebook'],
            'twitter'   =>   $settings[0]['twitter'],
            'telegram' =>   $settings[0]['telegram'],
            'pinterest' =>   $settings[0]['pinterest'],
            'linkedin'  =>   $settings[0]['linkedin'],
            'email'     =>   $settings[0]['email'],
            'whatsapp'  =>   $settings[0]['whatsapp']
        ),
        array(
            'id'    =>  1
        )
        );
        
        $settings = $wpdb->get_results( "SELECT * FROM ".ZPT_ADMIN_SETTING_SHARE_LINKS, ARRAY_A );
    }
    
    
    
    ?>
    <style>
        /* The container_checkboxes */
        .container_checkboxes {
          display: block;
          position: relative;
          padding-left: 40px;
          margin-bottom: 12px;
          padding-top: 3px;
          cursor: pointer;
          font-size: 15px;
          -webkit-user-select: none;
          -moz-user-select: none;
          -ms-user-select: none;
          user-select: none;
        }
        
        /* Hide the browser's default checkbox */
        .container_checkboxes input {
          position: absolute;
          opacity: 0;
          cursor: pointer;
          height: 0;
          width: 0;
        }
        
        /* Create a custom checkbox */
        .checkmark {
          position: absolute;
          top: 0;
          left: 0;
          height: 25px;
          width: 25px;
          background-color: #d4d4d4;
        }
        
        /* On mouse-over, add a grey background color */
        .container_checkboxes:hover input ~ .checkmark {
          background-color: #ccc;
        }
        
        /* When the checkbox is checked, add a blue background */
        .container_checkboxes input:checked ~ .checkmark {
          background-color: #2196F3;
        }
        
        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
          content: "";
          position: absolute;
          display: none;
        }
        
        /* Show the checkmark when checked */
        .container_checkboxes input:checked ~ .checkmark:after {
          display: block;
        }
        
        /* Style the checkmark/indicator */
        .container_checkboxes .checkmark:after {
          left: 9px;
          top: 5px;
          width: 5px;
          height: 10px;
          border: solid white;
          border-width: 0 3px 3px 0;
          -webkit-transform: rotate(45deg);
          -ms-transform: rotate(45deg);
          transform: rotate(45deg);
        }
    </style>
    <div class="wrap social-share-icons">
        <h1>Settings</h1>
        <hr>
        <form class="" method="post">
            <?php wp_nonce_field( 'zpt_my_social_reach_nonce_', 'zpt_my_social_reach_nonce' ) ?>
            <h3>Choose social icons to display</h3>
            <label class="container_checkboxes">Facebook
              <input name="zpts_facebook" type="checkbox" value="yes" <?php echo (isset( $settings[0]['facebook'] ) && $settings[0]['facebook'] == 1) ? 'checked="checked"' : ''; ?>>
              <span class="checkmark"></span>
            </label>
            <label class="container_checkboxes">Twitter
              <input name="zpts_twitter" type="checkbox" value="yes" <?php echo (isset( $settings[0]['twitter'] ) && $settings[0]['twitter'] == 1) ? 'checked="checked"' : ''; ?>>
              <span class="checkmark"></span>
            </label>
            <label class="container_checkboxes">Telegram
              <input name="zpts_telegram" type="checkbox" value="yes" <?php echo (isset( $settings[0]['telegram'] ) && $settings[0]['telegram'] == 1) ? 'checked="checked"' : ''; ?>>
              <span class="checkmark"></span>
            </label>
            <label class="container_checkboxes">Pinterest
              <input name="zpts_pinterest" type="checkbox" value="yes" <?php echo (isset( $settings[0]['pinterest'] ) && $settings[0]['pinterest'] == 1) ? 'checked="checked"' : ''; ?>>
              <span class="checkmark"></span>
            </label><label class="container_checkboxes">Linkedin
              <input name="zpts_linkedin" type="checkbox" value="yes" <?php echo (isset( $settings[0]['linkedin'] ) && $settings[0]['linkedin'] == 1) ? 'checked="checked"' : ''; ?>>
              <span class="checkmark"></span>
            </label><label class="container_checkboxes">Email
              <input name="zpts_email" type="checkbox" value="yes" <?php echo (isset( $settings[0]['email'] ) && $settings[0]['email'] == 1) ? 'checked="checked"' : ''; ?>>
              <span class="checkmark"></span>
            </label><label class="container_checkboxes">WhatsApp
              <input name="zpts_whatsapp" type="checkbox" value="yes" <?php echo (isset( $settings[0]['whatsapp'] ) && $settings[0]['whatsapp'] == 1) ? 'checked="checked"' : ''; ?>>
              <span class="checkmark"></span>
            </label>
            <p style="margin-top:25px;">
                <button type="submit" class="button button-primary" name="zpt_my_social_reach_settings_update">Submit</button>
            </p>
        </form>
    </div>
    <?php
}


/* Handling ajax request */
add_action("wp_ajax_zptmss_social", "zptmss_social_ajax_handle");
add_action("wp_ajax_nopriv_zptmss_social", "zptmss_social_ajax_handle");

function zptmss_social_ajax_handle(){
    global $wpdb;
    if ( ! isset( $_POST['nnc'] ) 
        || ! wp_verify_nonce( $_POST['nnc'], 'zptmss_ajax_nonce' ) 
    ) {
        wp_die ( "Invalid Nonce. Reload the page and try again!" );
    }
    
    if( !isset( $_POST['slug'] )  ){
        wp_die ( "Missing slug!" );
    }
    
    if( !isset( $_POST['type'] ) ){
        wp_die ( "Missing type!" );
    }
    
    $type = sanitize_text_field( $_POST['type'] );
    
    /* Sanitizing slug as string */
    $slug = sanitize_text_field( $_POST['slug'] );
    
    switch( $type ){
        
        case 'facebook':
            $col = 'facebook';
            break;
        case 'telegram':
            $col = 'telegram';
            break;
        case 'twitter':
            $col = 'twitter';
            break;
        case 'pinterest':
            $col = 'pinterest';
            break;
        case 'email':
            $col = 'email';
            break;
        case 'linkedin':
            $col = 'linked';
            break;
        case 'whatsapp':
            $col = 'whatsapp';
            break;
        
    }
    
    if( isset( $col, $slug ) ){
        $rr = $wpdb->query( "INSERT INTO ".ZPT_SHARE_LINKS_COUNT." SET url='$slug', `$col` = '".time()."'" );
    }
    exit(1);
}
