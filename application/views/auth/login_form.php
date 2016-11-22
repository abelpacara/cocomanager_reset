<h1>
   <?php
   $prefix_logo="default";
   
   if(isset($company_logged) AND count($company_logged)>0 AND isset($company_logged['id']))
   {
      $prefix_logo = $company_logged['id'];
   }
   ?>
   <img src="<?php echo site_url($uri_images_companies."/".$prefix_logo."_login-logo.jpg");?>"/>
</h1>
<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 50,
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 50,
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);

//message send email forgot password
echo '<span class="validation_message">'.$message.'</span>';

?>

<?php echo form_open($this->uri->uri_string()); ?>
<table>
	<tr>
		<td><?php echo form_label($login_label, $login['id']); ?>:</td>
		<td><?php echo form_input($login); ?></td>
		<td class="validation_message"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td>
	</tr>
	<tr>
		<td><?php echo form_label($view_labels['password'].':', $password['id']); ?></td>
		<td><?php echo form_password($password); ?></td>
		<td class="validation_message"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></td>
	</tr>

	<?php if ($show_captcha) {
		if ($use_recaptcha) { ?>
	<tr>
		<td colspan="2">
			<div id="recaptcha_image"></div>
		</td>
		<td>
			<a href="javascript:Recaptcha.reload()"><?php echo $view_labels['captcha_another']?></a>
			<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')"><?php echo $view_labels['captcha_audio']?></a></div>
			<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')"><?php echo $view_labels['captcha_image']?></a></div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="recaptcha_only_if_image"><?php echo $view_labels['captcha_above']?></div>
			<div class="recaptcha_only_if_audio"><?php echo $view_labels['captcha_numbers']?></div>
		</td>
		<td><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></td>
		<td style="color: red;"><?php echo form_error('recaptcha_response_field'); ?></td>
		<?php echo $recaptcha_html; ?>
	</tr>
	<?php   
   } 
   else 
   {?>
      <tr>
         <td colspan="3">
            <p><?php echo $view_labels['captcha_exactly']?>:</p>
            <?php echo $captcha_html; ?>
         </td>
      </tr>
      <tr>
         <td><?php echo form_label($view_labels['captcha_confirmacion'].':', $captcha['id']); ?></td>
         <td><?php echo form_input($captcha); ?></td>
         <td class="validation_message"><?php echo form_error($captcha['name']); ?></td>
      </tr>
      <?php
   }
}?>

	<tr>
		<td colspan="3">
			<?php echo form_checkbox($remember); ?>
			<?php echo form_label($view_labels['remember_password'].':', $remember['id']); ?>
			 &#124; 
			<?php echo anchor('/auth/forgot_password/', $view_labels['forgot_password']); ?>
          
         <?php 
         if( ! (isset($company_logged) AND count($company_logged)>0 AND isset($company_logged['id'])) )
         {?>
			 &#124; 
            <?php
            if ($this->config->item('allow_registration', 'tank_auth')) echo anchor('/auth/register/', $view_labels['btn_create_company']); 
         }
         ?>
		</td>
	</tr>
</table>
<?php echo form_submit('submit', $view_labels['btn_login']); ?>
<?php echo form_close(); ?>