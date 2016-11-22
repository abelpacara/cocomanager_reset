<H1><?php echo $view_labels['form_title']?></H1>

<?php
if ($use_username) {
	$username = array(
		'name'	=> 'username',
		'id'	=> 'username',
		'value' => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	=> 30,
	);
}

$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'value' => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);

######################################
$company_name = array(
	'name'	=> 'company_name',
	'id'	=> 'company_name',
	'value' => set_value('company_name'),
	'maxlength'	=>50,
	'size'	=> 30,
);




######################################
$profile_name = array(
	'name'	=> 'profile_name',
	'id'	=> 'profile_name',
	'value' => set_value('profile_name'),
	'maxlength'	=>50,
	'size'	=> 30,
);

######################################
$profile_last_name = array(
	'name'=>'profile_last_name',
	'id'	=> 'profile_last_name',
	'value' => set_value('profile_last_name'),
	'maxlength'	=>50,
	'size'	=> 30,
);
######################################




$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>
<?php echo form_open_multipart($this->uri->uri_string()); ?>
<table>
	<?php if ($use_username) { ?>
	<tr>
		<td><?php echo form_label($view_labels['username'].':', $username['id']); ?></td>
		<td><?php echo form_input($username); ?></td>
		<td class="validation_message"><?php echo form_error($username['name']); ?><?php echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?></td>
      
	</tr>
	<?php } ?>
	<tr>
		<td><?php echo form_label($view_labels['email'].':', $email['id']); ?></td>
		<td><?php echo form_input($email); ?></td>
		<td class="validation_message"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></td>
	</tr>
	<tr>
		<td><?php echo form_label($view_labels['password'].':', $password['id']); ?></td>
		<td><?php echo form_password($password); ?></td>
		<td class="validation_message"><?php echo form_error($password['name']); ?></td>
	</tr>
	<tr>
		<td><?php echo form_label($view_labels['re_password'].':', $confirm_password['id']); ?></td>
		<td><?php echo form_password($confirm_password); ?></td>
		<td class="validation_message"><?php echo form_error($confirm_password['name']); ?></td>
	</tr>
   
   <tr>
		<td><?php 
      //####################################################################
      echo form_label($view_labels['company_name'].':', $company_name['id']); ?></td>
		<td><?php echo form_input($company_name); ?></td>
		<td class="validation_message"><?php echo form_error($company_name['name']); ?></td>
	</tr>

   
   
   <tr>
      <td>
         <?php echo form_label($view_labels['company_logo'].':','company_logo'); ?>
      </td>
		<td>
         <input type="file" name="company_logo" id="company_logo"/>
      </td>
   </tr>
   
   
   
   <tr>
		<td><?php 
      //####################################################################
      echo form_label($view_labels['name'].':', $profile_name['id']); ?></td>
		<td><?php echo form_input($profile_name); ?></td>
		<td class="validation_message"><?php echo form_error($profile_name['name']); ?></td>
	</tr>
   <tr>
		<td><?php 
      //####################################################################
      echo form_label($view_labels['last_name'].':', $profile_last_name['id']); ?></td>
		<td><?php echo form_input($profile_last_name); ?></td>
		<td class="validation_message"><?php echo form_error($profile_last_name['name']); ?></td>
	</tr>
   
   <tr>
      <td>
         <?php echo $view_labels['language']?>:
      </td>
      <td>  
         <select name="language">
         <?php
         $languages = $this->config->item("languages");
         for($i=0; $i<count($languages);$i++)
         {?>
            <option value="<?php echo $languages[$i]?>">
               <?php echo $languages[$i]?>
            </option>
         <?php
         }
         ?>
         </select>
      </td>
   </tr>
   
	<?php if ($captcha_registration) {
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
	<?php } else { ?>
	<tr>
		<td colspan="3">
			<p><?php echo $view_labels['captcha_exactly']?>:</p>
			<?php echo $captcha_html; ?>
		</td>
	</tr>
	<tr>
		<td><?php echo form_label($view_labels['captcha_confirmation'].':', $captcha['id']); ?></td>
		<td><?php echo form_input($captcha); ?></td>
		<td style="color: red;"><?php echo form_error($captcha['name']); ?></td>
	</tr>
	<?php }
	} ?>
</table>
<?php echo form_submit('register', $view_labels['btn_register']); ?>
<?php echo form_close(); ?>
<p class="back_link">
<?php echo anchor('/auth/login/', $view_labels['auth_back_to_login_page']); ?>
</p>