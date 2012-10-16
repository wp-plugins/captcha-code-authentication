<?php
/*
Plugin Name: Captcha Code
Plugin URI: http://fingerfish.com/captcha-code-authentication/
Description: Adds Captcha Code anti-spam methods to User front-end WordPress forms.
Version: 1.7
Author: Vinoj Cardoza
Author URI: http://fingerfish.com/about-me/
License: GPL2
*/

define('WP_CAPTCHA_DIR_URL', plugin_dir_url(__FILE__));
define('WP_CAPTCHA_DIR', dirname(__FILE__));

require 'general_options.php';

/* Hook to initalize the admin menu */
add_action('admin_menu', 'wp_captcha_admin_menu');
/* Hook to initialize sessions */
add_action('init', 'wp_captcha_init_sessions');

/* Hook to store the plugin status */
register_activation_hook(__FILE__, 'wp_captcha_enabled');
register_deactivation_hook(__FILE__, 'wp_captcha_disabled');

function wp_captcha_enabled(){
	update_option('wpcaptcha_status', 'enabled');
}
function wp_captcha_disabled(){
	update_option('wpcaptcha_status', 'disabled');
}

/* To add the menus in the admin section */
function wp_captcha_admin_menu(){
    add_menu_page(
            __('Captcha'),
            'Captcha',
            'manage_options',
            'wp_captcha_slug',
            'wp_captcha_general_options',
            WP_CAPTCHA_DIR_URL . 'public/images/captcha.gif');
}

function wp_captcha_init_sessions(){
	if(!session_id()){
		session_start();
	}
	load_plugin_textdomain('wpcaptchadomain', false, dirname( plugin_basename(__FILE__)).'/languages');
	$_SESSION['captcha_type'] = get_option('wpcaptcha_type');
	$_SESSION['captcha_letters'] = get_option('wpcaptcha_letters');
	$_SESSION['total_no_of_characters'] = get_option('wpcaptcha_total_no_of_characters');
	if(empty($_SESSION['total_no_of_characters'])){
		$_SESSION['total_no_of_characters'] = 6;
	}
}

/* Captcha for login authentication starts here */ 

$login_captcha = get_option('wpcaptcha_login');
if($login_captcha == 'yes'){
	add_action('login_form', 'include_wp_captcha_login');
	add_filter( 'login_errors', 'include_captcha_login_errors' );
	add_filter( 'login_redirect', 'include_captcha_login_redirect', 10, 3 );	
}

/* Function to include captcha for login form */
function include_wp_captcha_login(){
	echo '<p class="login-form-captcha">
			<label for="captcha"><b>'. __('Captcha', 'wpcaptchadomain').' </b></label>
			<span class="required">*</span>
			<div style="clear:both;"></div>
			<img src="'.WP_CAPTCHA_DIR_URL.'captcha_code_file.php?rand='.rand().'" />
			<div style="clear:both;"></div>';
			
	/* Will retrieve the get varibale and prints a message from url if the captcha is wrong */
	if( $_GET['captcha'] == 'confirm_error' ) {
		echo '<label style="color:#FF0000;" id="capt_err" for="captcha_code_error">'.$_SESSION['captcha_error'].'</label><div style="clear:both;"></div>';;
		$_SESSION['captcha_error'] = '';
	}
	
	echo '<label for="captcha_code">'.__('Type the text displayed above', 'wpcaptchadomain').':</label>
			<input id="captcha_code" name="captcha_code" size="15" type="text" />
			</p>';
	return true;
}

/* Hook to find out the errors while logging in */
function include_captcha_login_errors($errors){
	if( isset( $_REQUEST['action'] ) && 'register' == $_REQUEST['action'] )
		return($errors);
	
	if($_SESSION['captcha_code'] != $_REQUEST['captcha_code']){
		return $errors.'<label id="capt_err" for="captcha_code_error">'.__('Captcha confirmation error!', 'wpcaptchadomain').'</label>';
	}
	return $errors;
}

/* Hook to redirect after captcha confirmation */
function include_captcha_login_redirect($url){
	
	/* Captcha mismatch */
	if($_SESSION['captcha_code'] != $_REQUEST['captcha_code']){
		$_SESSION['captcha_error'] = __('Incorrect captcha confirmation!', 'wpcaptchadomain');
		wp_clear_auth_cookie();
		return $_SERVER["REQUEST_URI"]."/?captcha='confirm_error'";
	}
	/* Captcha match: take to the admin panel */
	else{
		return home_url('/wp-admin/');	
	}
}

/* <!-- Captcha for login authentication ends here --> */

/* Captcha for Comments ends here */
$comment_captcha = get_option('wpcaptcha_comments');
if($comment_captcha == 'yes'){
	global $wp_version;
	if( version_compare($wp_version,'3','>=') ) { // wp 3.0 +
		add_action( 'comment_form_after_fields', 'include_wp_captcha_comment_form_wp3', 1 );
		add_action( 'comment_form_logged_in_after', 'include_wp_captcha_comment_form_wp3', 1 );
	}	
	// for WP before WP 3.0
	add_action( 'comment_form', 'include_captcha_comment_form' );	
	add_filter( 'preprocess_comment', 'include_captcha_comment_post' );
}

/* Function to include captcha for comments form */
function include_captcha_comment_form(){
	$c_registered = get_option('wpcaptcha_registered');
	if ( is_user_logged_in() && $c_registered == 'yes') {
		return true;
	}
	echo '<p class="comment-form-captcha">
		<label for="captcha"><b>'. __('Captcha', 'wpcaptchadomain').' </b></label>
		<span class="required">*</span>
		<div style="clear:both;"></div>
		<img src="'.WP_CAPTCHA_DIR_URL.'captcha_code_file.php?rand='.rand().'" />
		<div style="clear:both;"></div>
		<label for="captcha_code">'.__('Type the text displayed above', 'wpcaptchadomain').':</label>
		<input id="captcha_code" name="captcha_code" size="15" type="text" />
		<div style="clear:both;"></div>
		</p>';
	return true;
}

/* Function to include captcha for comments form > wp3 */
function include_wp_captcha_comment_form_wp3(){
	$c_registered = get_option('wpcaptcha_registered');
	if ( is_user_logged_in() && $c_registered == 'yes') {
		return true;
	}
	
	echo '<p class="comment-form-captcha">
		<label for="captcha"><b>'. __('Captcha', 'wpcaptchadomain').' </b></label>
		<span class="required">*</span>
		<div style="clear:both;"></div>
		<img src="'.WP_CAPTCHA_DIR_URL.'captcha_code_file.php?rand='.rand().'" />
		<div style="clear:both;"></div>
		<label for="captcha_code">'.__('Type the text displayed above', 'wpcaptchadomain').':</label>
		<input id="captcha_code" name="captcha_code" size="15" type="text" />
		<div style="clear:both;"></div>
		</p>';
		
	remove_action( 'comment_form', 'include_captcha_comment_form' );
	
	return true;
}

// this function checks captcha posted with the comment
function include_captcha_comment_post($comment) {	
	$c_registered = get_option('wpcaptcha_registered');
	if (is_user_logged_in() && $c_registered == 'yes') {
		return $comment;
	}

	// skip captcha for comment replies from the admin menu
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'replyto-comment' &&
	( check_ajax_referer( 'replyto-comment', '_ajax_nonce', false ) || check_ajax_referer( 'replyto-comment', '_ajax_nonce-replyto-comment', false ) ) ) {
		// skip capthca
		return $comment;
	}

	// Skip captcha for trackback or pingback
	if ( $comment['comment_type'] != '' && $comment['comment_type'] != 'comment' ) {
		 // skip captcha
		 return $comment;
	}
	
	// If captcha is empty
	if(empty($_REQUEST['captcha_code']))
		wp_die( __('CAPTCHA cannot be empty.', 'wpcaptchadomain' ) );

	// captcha was matched
	if($_SESSION['captcha_code'] == $_REQUEST['captcha_code']) return($comment);
	else wp_die( __('Error: Incorrect CAPTCHA. Press your browser\'s back button and try again.', 'wpcaptchadomain'));
} 

/* <!-- Captcha for Comments authentication ends here --> */

// Add captcha in the register form
$register_captcha = get_option('wpcaptcha_register');
if($register_captcha == 'yes'){
	add_action('register_form', 'include_wp_captcha_register');
	add_action( 'register_post', 'include_captcha_register_post', 10, 3 );
	add_action( 'signup_extra_fields', 'include_wp_captcha_register' );
	add_filter( 'wpmu_validate_user_signup', 'include_captcha_register_validate' );
}

/* Function to include captcha for register form */
function include_wp_captcha_register($default){
	echo '<p class="register-form-captcha">	
			<label for="captcha"><b>'. __('Captcha', 'wpcaptchadomain').' </b></label>
			<span class="required">*</span>
			<div style="clear:both;"></div>
			<img src="'.WP_CAPTCHA_DIR_URL.'captcha_code_file.php?rand='.rand().'" />
			<div style="clear:both;"></div>
			<label for="captcha_code">'.__('Type the text displayed above', 'wpcaptchadomain').':</label>
			<input id="captcha_code" name="captcha_code" size="15" type="text" />
			</p>';
	return true;
}

/* This function checks captcha posted with registration */
function include_captcha_register_post($login,$email,$errors) {

	// If captcha is blank - add error
	if ( isset( $_REQUEST['captcha_code'] ) && "" ==  $_REQUEST['captcha_code'] ) {
		$errors->add('captcha_blank', '<strong>'.__('ERROR', 'wpcaptchadomain').'</strong>: '.__('Please complete the CAPTCHA.', 'wpcaptchadomain'));
		return $errors;
	}

	if ( isset( $_REQUEST['captcha_code'] ) && ($_SESSION['captcha_code'] == $_REQUEST['captcha_code'] )) {
					// captcha was matched						
	} else {
		$errors->add('captcha_wrong', '<strong>'.__('ERROR', 'wpcaptchadomain').'</strong>: '.__('That CAPTCHA was incorrect.', 'wpcaptchadomain'));
	}
  return($errors);
} 
/* End of the function include_captcha_register_post */

function include_captcha_register_validate($results) {
	if ( isset( $_REQUEST['captcha_code'] ) && "" ==  $_REQUEST['captcha_code'] ) {
		$results['errors']->add('captcha_blank', '<strong>'.__('ERROR', 'wpcaptchadomain').'</strong>: '.__('Please complete the CAPTCHA.', 'wpcaptchadomain'));
		return $results;
	}

	if ( isset( $_REQUEST['captcha_code'] ) && ($_SESSION['captcha_code'] == $_REQUEST['captcha_code'] )) {
					// captcha was matched						
	} else {
		$results['errors']->add('captcha_wrong', '<strong>'.__('ERROR', 'wpcaptchadomain').'</strong>: '.__('That CAPTCHA was incorrect.', 'wpcaptchadomain'));
	}
  return($results);
}
/* End of the function include_captcha_register_validate */

$lost_captcha = get_option('wpcaptcha_lost');
// Add captcha into lost password form
if($lost_captcha == 'yes'){
	add_action( 'lostpassword_form', 'include_wp_captcha_lostpassword' );
	add_action( 'lostpassword_post', 'include_wp_captcha_lostpassword_post', 10, 3 );
}

/* Function to include captcha for lost password form */
function include_wp_captcha_lostpassword($default){
	echo '<p class="lost-form-captcha">
		<label for="captcha"><b>'. __('Captcha', 'wpcaptchadomain').' </b></label>
		<span class="required">*</span>
		<div style="clear:both;"></div>
		<img src="'.WP_CAPTCHA_DIR_URL.'captcha_code_file.php?rand='.rand().'" />
		<div style="clear:both;"></div>
		<label for="captcha_code">'.__('Type the text displayed above', 'wpcaptchadomain').':</label>
		<input id="captcha_code" name="captcha_code" size="15" type="text" />
		</p>';	
}

function include_wp_captcha_lostpassword_post() {
	if( isset( $_REQUEST['user_login'] ) && "" == $_REQUEST['user_login'] )
		return;

	// If captcha doesn't entered
	if ( isset( $_REQUEST['captcha_code'] ) && "" ==  $_REQUEST['captcha_code'] ) {
		wp_die( __( 'Please complete the CAPTCHA.', 'wpcaptchadomain' ) );
	}
	
	// Check entered captcha
	if ( isset( $_REQUEST['captcha_code'] ) && ($_SESSION['captcha_code'] == $_REQUEST['captcha_code'] )) {
		return;
	} else {
		wp_die( __( 'Error: Incorrect CAPTCHA. Press your browser\'s back button and try again.', 'wpcaptchadomain' ) );
	}
} // function cptch_lostpassword_post
?>
