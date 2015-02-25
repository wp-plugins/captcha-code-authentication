<?php
/* Function to configure Captcha for Wordpress */
function wp_captcha_general_options(){
?>
	<div class="wrap">
	<table>
		<tr>
			<td width="50"><img src="<?php echo WP_CAPTCHA_DIR_URL . 'public/images/form_captcha.gif';?>" /></td>
			<td><h1><?php _e('CAPTCHA', 'wpcaptchadomain');?></h1></td>
		</tr>
	</table>
<?php
if(isset($_POST['submit'])){
?>
    <div id="message" class="updated fade"><p><strong><?php _e('Options saved.', 'wpcaptchadomain'); ?></strong></p></div>
<?php
	if(isset($_POST['captcha_login'])) 
		update_option('wpcaptcha_login', $_POST['captcha_login']);
	if(isset($_POST['captcha_register'])) 
		update_option('wpcaptcha_register', $_POST['captcha_register']);
	if(isset($_POST['captcha_lost'])) 
		update_option('wpcaptcha_lost', $_POST['captcha_lost']);
	if(isset($_POST['captcha_comments'])) 
		update_option('wpcaptcha_comments', $_POST['captcha_comments']);
	if(isset($_POST['captcha_registered'])) 
		update_option('wpcaptcha_registered', $_POST['captcha_registered']);
	if(isset($_POST['captcha_type'])) 
		update_option('wpcaptcha_type', $_POST['captcha_type']);
	if(isset($_POST['captcha_letters'])) 
		update_option('wpcaptcha_letters', $_POST['captcha_letters']);
	if(isset($_POST['total_no_of_characters'])) 
		update_option('wpcaptcha_total_no_of_characters', $_POST['total_no_of_characters']);
}

	$c_login = get_option('wpcaptcha_login');
	$c_login_yes = null;
	$c_login_no = null;
	if(!empty($c_login) && $c_login == 'yes') $c_login_yes = 'selected="selected"';
	else $c_login_no = 'selected="selected"';
	
	$c_register = get_option('wpcaptcha_register');
	$c_register_yes = null;
	$c_register_no = null;
	if(!empty($c_register) && $c_register == 'yes') $c_register_yes = 'selected="selected"';
	else $c_register_no = 'selected="selected"';
	
	$c_lost = get_option('wpcaptcha_lost');
	$c_lost_yes = null;
	$c_lost_no = null;
	if(!empty($c_lost) && $c_lost == 'yes') $c_lost_yes = 'selected="selected"';
	else $c_lost_no = 'selected="selected"';
	
	$c_comments = get_option('wpcaptcha_comments');
	$c_comments_yes = null;
	$c_comments_no = null;
	if(!empty($c_register) && $c_comments == 'yes') $c_comments_yes = 'selected="selected"';
	else $c_comments_no = 'selected="selected"';
	
	$c_registered = get_option('wpcaptcha_registered');
	$c_registered_yes = null;
	$c_registered_no = null;
	if(!empty($c_registered) && $c_registered == 'yes') $c_registered_yes = 'selected="selected"';
	else $c_registered_no = 'selected="selected"';
	
	$c_type = get_option('wpcaptcha_type');
	$c_letters = get_option('wpcaptcha_letters');
	$c_total_no_of_characters = get_option('wpcaptcha_total_no_of_characters');
?>
	<form method="post" action="">
        <table class="form-table">
            <tr valign="top">
                <th scope="row" style="width:260px;"><?php _e('Select Captcha letters type', 'wpcaptchadomain');?>:</th>
                <td>
                    <select name="captcha_letters" style="margin:0;">
                            <option value="capital" <?php if($c_letters == 'capital') echo 'selected="selected"';?>><?php _e('Capital letters only', 'wpcaptchadomain');?></option>
                            <option value="small" <?php if($c_letters == 'small') echo 'selected="selected"';?>><?php _e('Small letters only', 'wpcaptchadomain');?></option>
                            <option value="capitalsmall" <?php if($c_letters == 'capitalsmall') echo 'selected="selected"';?>><?php _e('Capital & Small letters', 'wpcaptchadomain');?></option>
                    </select>			
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Select a Captcha type', 'wpcaptchadomain');?>: </th>
                <td>
                    <select name="captcha_type" style="margin:0;">
                        <option value="alphanumeric" <?php if($c_type == 'alphanumeric') echo 'selected="selected"';?>><?php _e('Alphanumeric', 'wpcaptchadomain');?></option>
                        <option value="alphabets" <?php if($c_type == 'alphabets') echo 'selected="selected"';?>><?php _e('Alphabets only', 'wpcaptchadomain');?></option>
                        <option value="numbers" <?php if($c_type == 'numbers') echo 'selected="selected"';?>><?php _e('Numbers only', 'wpcaptchadomain');?></option>
                    </select>			
                </td>
        	</tr>
			<tr valign="top">
                <th scope="row"><?php _e('Total number of Captcha Characters', 'wpcaptchadomain');?>: </th>
                <td>
                    <select name="total_no_of_characters" style="margin:0;width: 50px;">
					<?php 
						for($i=3; $i<=6; $i++){
                        	print '<option value="'.$i.'" ';
							if($c_total_no_of_characters == $i) echo 'selected="selected"';
							print '>'.$i.'</option>';
						}
					?>
                    </select>			
                </td>
        	</tr>
        </table>
		<h3><?php _e('Captcha display Options', 'wpcaptchadomain');?></h3>
    	<table class="form-table">
            <tr valign="top">
                    <th scope="row" style="width:260px;"><?php _e("Enable Captcha for Login form", "wpcaptchadomain");?>: </th>
                    <td>
                            <select name="captcha_login" style="width:75px;margin:0;">
                                    <option value="yes" <?php echo $c_login_yes;?>><?php _e('Yes', 'wpcaptchadomain');?></option>
                                    <option value="no" <?php echo $c_login_no;?>><?php _e('No', 'wpcaptchadomain');?></option>
                            </select>			
                    </td>
            </tr>
            <tr valign="top">
                    <th scope="row"><?php _e('Enable Captcha for Register form', 'wpcaptchadomain');?>: </th>
                    <td>
                            <select name="captcha_register" style="width:75px;margin:0;">
                                    <option value="yes" <?php echo $c_register_yes;?>><?php _e('Yes', 'wpcaptchadomain');?></option>
                                    <option value="no" <?php echo $c_register_no;?>><?php _e('No', 'wpcaptchadomain');?></option>
                            </select>			
                    </td>
            </tr>
            <tr valign="top">
                    <th scope="row"><?php _e('Enable Captcha for Lost Password form', 'wpcaptchadomain');?>: </th>
                    <td>
                            <select name="captcha_lost" style="width:75px;margin:0;">
                                    <option value="yes" <?php echo $c_lost_yes;?>><?php _e('Yes', 'wpcaptchadomain');?></option>
                                    <option value="no" <?php echo $c_lost_no;?>><?php _e('No', 'wpcaptchadomain');?></option>
                            </select>			
                    </td>
            </tr>
            <tr valign="top">
                    <th scope="row"><?php _e('Enable Captcha for Comments form', 'wpcaptchadomain');?>: </th>
                    <td>
                            <select name="captcha_comments" style="width:75px;margin:0;">
                                    <option value="yes" <?php echo $c_comments_yes;?>><?php _e('Yes', 'wpcaptchadomain');?></option>
                                    <option value="no" <?php echo $c_comments_no;?>><?php _e('No', 'wpcaptchadomain');?></option>
                            </select>			
                    </td>
            </tr>
            <tr valign="top">
                    <th scope="row"><?php _e('Hide Captcha for logged in users', 'wpcaptchadomain');?>: </th>
                    <td>
                            <select name="captcha_registered" style="width:75px;margin:0;">
                                    <option value="yes" <?php echo $c_registered_yes;?>><?php _e('Yes', 'wpcaptchadomain');?></option>
                                    <option value="no" <?php echo $c_registered_no;?>><?php _e('No', 'wpcaptchadomain');?></option>
                            </select>			
                    </td>
            </tr>
            <tr height="60">
                <td><?php submit_button();?></td>
                <td></td>
            </tr>
	</table>
	
	</form>
	</div>
<?php
}
?>