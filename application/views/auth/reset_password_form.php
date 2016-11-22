<?php
$new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
	'id'	=> 'confirm_new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size' 	=> 30,
);
?>
<?php echo form_open($this->uri->uri_string()); ?>
<h1>
   <img src="<?php echo site_url($uri_images_companies."/default_main-logo.jpg");?>"/>   
</h1>
<table>
	<tr>
		<td><?php echo form_label($view_labels['new_password'].':', $new_password['id']); ?></td>
		<td><?php echo form_password($new_password); ?></td>
		<td style="color: red;"><?php echo form_error($new_password['name']); ?>
         <?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?>
      </td>
	</tr>
	<tr>
		<td><?php echo form_label($view_labels['confirm_new_password'].':', $confirm_new_password['id']); ?></td>
		<td><?php echo form_password($confirm_new_password); ?></td>
		<td style="color: red;">
         <?php echo form_error($confirm_new_password['name']); ?>
         <?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?>
      </td>
	</tr>
</table>
<?php echo form_submit('change', $view_labels['btn_save_password']); ?>
<?php echo form_close(); ?>