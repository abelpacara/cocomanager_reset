<H1><?php echo $view_labels['form_title']?></H1>
<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email';
} else {
	$login_label = 'Email';
}
?>
<?php echo form_open($this->uri->uri_string()); ?>
<table>
	<tr>
		<td><?php echo form_label($login_label, $login['id']); ?></td>
		<td><?php echo form_input($login); ?></td>
      <td class="validation_message">
         <?php echo form_error($login['name']); ?>
         <?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?>
      </td>
	</tr>
</table>
<?php echo form_submit('reset', $view_labels['get_new_password']); ?>
<p class="back_link">
<?php echo anchor('/auth/login/', $view_labels['auth_back_to_login_page']); ?>
</p>
<?php echo form_close(); ?>
