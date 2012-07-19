<?php
/* Function to configure Captcha for Wordpress */
function wp_captcha_general_options(){
?>
	<div class="wrap" style="font-family: Verdana;">
	<table>
		<tr>
			<td width="50"><img src="<?php echo WP_CAPTCHA_DIR_URL . 'public/images/form_captcha.gif';?>" /></td>
			<td><h2>Captcha Code Authentication for Wordpress - Options</h2></td>
		</tr>
	</table>
	<br /><br />
<?php
if(isset($_POST['save_captcha_options'])){
?>
    <div id="message" class="updated fade"><p><strong><?php _e('Options saved.', 'wpcpatcha_tans_domain' ); ?></strong></p></div>
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
}
?>
	<form method="post" action="">
	<table>
		<tr height="40">
			<td><b><?php print __("Enable Captcha for Login form");?>: </b></td>
			<td>
			<?php
				$c_login = get_option('wpcaptcha_login');
				if($c_login == 'yes') $c_login_yes = 'selected="selected"';
				else $c_login_no = 'selected="selected"';
				$c_register = get_option('wpcaptcha_register');
				if($c_register == 'yes') $c_register_yes = 'selected="selected"';
				else $c_register_no = 'selected="selected"';
				$c_lost = get_option('wpcaptcha_lost');
				if($c_lost == 'yes') $c_lost_yes = 'selected="selected"';
				else $c_lost_no = 'selected="selected"';
				$c_comments = get_option('wpcaptcha_comments');
				if($c_comments == 'yes') $c_comments_yes = 'selected="selected"';
				else $c_comments_no = 'selected="selected"';
				$c_registered = get_option('wpcaptcha_registered');
				if($c_registered == 'yes') $c_registered_yes = 'selected="selected"';
				else $c_registered_no = 'selected="selected"';
			?>
				<select name="captcha_login" style="width:75px;margin:0;">
					<option value="yes" <?php echo $c_login_yes;?>>Yes</option>
					<option value="no" <?php echo $c_login_no;?>>No</option>
				</select>			
			</td>
		</tr>
		<tr height="40">
			<td><b><?php print __('Enable Captcha for Register form');?>: </b></td>
			<td>
				<select name="captcha_register" style="width:75px;margin:0;">
					<option value="yes" <?php echo $c_register_yes;?>>Yes</option>
					<option value="no" <?php echo $c_register_no;?>>No</option>
				</select>			
			</td>
		</tr>
		<tr height="40">
			<td><b><?php print __('Enable Captcha for Lost Password form');?>: </b></td>
			<td>
				<select name="captcha_lost" style="width:75px;margin:0;">
					<option value="yes" <?php echo $c_lost_yes;?>>Yes</option>
					<option value="no" <?php echo $c_lost_no;?>>No</option>
				</select>			
			</td>
		</tr>
		<tr height="40">
			<td><b><?php print __('Enable Captcha for Comments form');?>: </b></td>
			<td>
				<select name="captcha_comments" style="width:75px;margin:0;">
					<option value="yes" <?php echo $c_comments_yes;?>>Yes</option>
					<option value="no" <?php echo $c_comments_no;?>>No</option>
				</select>			
			</td>
		</tr>
		<tr height="40">
			<td><b><?php print __('Hide Captcha for logged in users');?>: </b></td>
			<td>
				<select name="captcha_registered" style="width:75px;margin:0;">
					<option value="yes" <?php echo $c_registered_yes;?>>Yes</option>
					<option value="no" <?php echo $c_registered_no;?>>No</option>
				</select>			
			</td>
		</tr>
		<tr height="60">
			<td><input type="submit" name="save_captcha_options" value="Save options" /></td>
			<td></td>
		</tr>
	</table>
	</form>
	</div>
<?php
}
?>