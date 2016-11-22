<?php
echo "<br>".$my_messages;

$form_username = array(
   'name'	=> 'username',
   'id'	=> 'username',
   'value' => set_value('username', $username),
   'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
   'size'	=> 30,
);


$form_password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'value' =>'',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);


$form_new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
	'value' =>'',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);

######################################
$form_email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email', $email),
	'maxlength'	=> 80,
	'size'	=> 30,
);

######################################
$form_profile_name = array(
	'name'	=> 'profile_name',
	'id'	=> 'profile_name',
	'value' => set_value('profile_name', $profile_name),
	'maxlength'	=>50,
	'size'	=> 30,
);

######################################
$form_profile_last_name = array(
	'name'=>'profile_last_name',
	'id'	=> 'profile_last_name',
	'value' => set_value('profile_last_name', $profile_last_name),
	'maxlength'	=>50,
	'size'	=> 30,
);
?>
<?php
echo validation_errors();
?>
<p/>
<div>
   <h1><?php echo $view_labels['title_form'];?></h1>
      
   
   <?php //echo form_open($this->uri->uri_string()); ?>
   <?php echo form_open_multipart($this->uri->uri_string()); ?>
      <table>
          <tr>
            <td><?php echo form_label($view_labels['user_name'], $form_username['id']); ?></td>
            <td><?php echo form_input($form_username); ?></td>
            <td class="error_form_field"><?php echo form_error($form_username['name']); ?></td>
         </tr>
         
         <tr>
            <td><?php echo form_label($view_labels['password'], $form_password['id']); ?></td>
            <td><?php echo form_password($form_password); ?></td>
            <td class="error_form_field"><?php echo form_error($form_password['name']); ?></td>
         </tr>
         
         
         <tr>
            <td><?php echo form_label($view_labels['new_password'], $form_new_password['id']); ?></td>
            <td><?php echo form_password($form_new_password); ?></td>
            <td class="error_form_field"><?php echo form_error($form_new_password['name']); ?></td>
         </tr>
         
         <tr>
            <td><?php echo form_label($view_labels['email_address'], $form_email['id']); ?></td>
            <td><?php echo form_input($form_email); ?></td>
            <td class="error_form_field"><?php echo form_error($form_email['name']); ?>
            <?php echo isset($errors[$form_email['name']])?$errors[$form_email['name']]:''; ?></td>
         </tr>
         
         <tr>
            <td><?php 
            //####################################################################
            echo form_label($view_labels['name'], $form_profile_name['id']); ?></td>
            <td><?php echo form_input($form_profile_name); ?></td>
            <td class="error_form_field"><?php echo form_error($form_profile_name['name']); ?></td>
         </tr>
         
         <tr>
            <td><?php 
               //####################################################################
               echo form_label($view_labels['last_name'], $form_profile_last_name['id']); ?>
            </td>
            <td><?php echo form_input($form_profile_last_name); ?></td>
            <td  class="error_form_field"><?php echo form_error($form_profile_last_name['name']); ?></td>
         </tr>
         <?php
         $uri_picture = ".".$uri_images_users.'/'.$user_id.'_thumb_medium.jpg';
         $url_picture = base_url($uri_images_users).'/'.$user_id.'_thumb_medium.jpg';
         
         if(file_exists($uri_picture))
         {
            ?>
            <tr>
               <td>
                  <?php echo $view_labels['current_picture']?>:
               </td>
               <td>
                  <img src="<?php echo $url_picture;?>"/>
               </td>
            </tr>
            <?php
         }?>
         <tr>
            <td>
               <?php echo $view_labels['picture']?> [max 3MB]:
            </td>
            <td>         
               <input type="file" name="picture" size="20"/>
            </td>
         </tr>
         <tr>
            <td>
               <?php echo $view_labels['language']?>
            </td>
            <td>  
               <select name="language">
               <?php
               $languages = $this->config->item("languages");
               for($i=0; $i<count($languages);$i++)
               {?>
                  <option value="<?php echo $languages[$i]?>"
                          <?php
                          if( strcasecmp( $language, $languages[$i]) == 0)
                          {
                             echo " SELECTED ";
                          }
                          ?>
                          >
                     <?php echo $languages[$i]?>
                  </option>
               <?php
               }
               ?>
               </select>
            </td>
         </tr>
         
         
      </table>
   <?php echo form_submit('save_changes', $view_labels['save_changes']); ?>
   <?php echo form_close(); ?>
</div>
