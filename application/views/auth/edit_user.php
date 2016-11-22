<?php
echo "<br>".$my_messages;


$form_username = array(
   'name'	=> 'username',
   'id'	=> 'username',
   'value' => set_value('username', $user['username']),
   'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
   'size'	=> 30,
);



######################################
$form_email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email', $user['email']),
	'maxlength'	=> 80,
	'size'	=> 30,
);

$password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
	'value' =>'',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);

######################################
$form_profile_name = array(
	'name'	=> 'profile_name',
	'id'	=> 'profile_name',
	'value' => set_value('name', $user['name']),
	'maxlength'	=>50,
	'size'	=> 30,
);

######################################
$form_profile_last_name = array(
	'name'=>'profile_last_name',
	'id'	=> 'profile_last_name',
	'value' => set_value('last_name', $user['last_name']),
	'maxlength'	=>50,
	'size'	=> 30,
);

?>
<p/>
<div>
   <h1><?php echo $view_labels['title_form']?></h1>
   <?php
   //echo validation_errors();
   ?>
   
   <?php //echo form_open($this->uri->uri_string()); ?>
   <?php echo form_open_multipart($this->uri->uri_string()); ?>
   
      <input type="hidden" name="user_id" value="<?php echo $user['id'];?>"/>
      
      <input type="hidden" name="redirect" value="<?php echo $redirect;?>"/>
      
      <table>
         <tr>
            <td><?php echo form_label($view_labels['user_name'].':', $form_username['id']); ?></td>
            <td><?php echo form_input($form_username); ?></td>
            <td class="error_form_field"><?php echo form_error($form_username['name']); ?></td>
         </tr>
         
         <tr>
            <td><?php echo form_label($view_labels['email'].':', $form_email['id']); ?></td>
            <td><?php echo form_input($form_email); ?></td>
            <td class="error_form_field"><?php echo form_error($form_email['name']); ?>            
         </tr>
         
         
         
         <tr>
            <td><?php echo form_label($view_labels['new_password'].':', $password['id']); ?></td>
            <td><?php echo form_password($password); ?></td>
            <td class="error_form_field"><?php echo form_error($password['name']); ?></td>
         </tr>
         
         <!--
         <tr>
            <td>New Password</td>
            <td><input type="password" name="new_password" 
            value="" 
            size="30" 
            maxlength="<?php echo $this->config->item('password_max_length', 'tank_auth')?>" /></td>
         </tr>
         -->
         
         <tr>
            <td><?php 
            //####################################################################
            echo form_label($view_labels['names'].':', $form_profile_name['id']); ?></td>
            <td><?php echo form_input($form_profile_name); ?></td>
            <td class="error_form_field"><?php echo form_error($form_profile_name['name']); ?></td>
         </tr>
         
         <tr>
            <td><?php 
            //####################################################################
            echo form_label($view_labels['last_name'].':', $form_profile_last_name['id']); ?></td>
            <td><?php echo form_input($form_profile_last_name); ?></td>
            <td class="error_form_field"><?php echo form_error($form_profile_last_name['name']); ?></td>
         </tr>
         
         
         <?php
         $uri_picture = ".".$uri_images_users.'/'.$user['id'].'_thumb_medium.jpg';
         $url_picture = base_url($uri_images_users).'/'.$user['id'].'_thumb_medium.jpg';
         
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
               <input type="file" name="photo" size="20"/>
            </td>
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
                  <option value="<?php echo $languages[$i]?>"
                          <?php
                          if( strcasecmp( $user['language'], $languages[$i]) == 0)
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
         <tr>
            <td><?php 
            //####################################################################
            echo $view_labels['status'];?></td>
            <td>
               <style>
                  .input_radio{
                     height: 10px !important;
                  }
               </style>
               <div class="enqueue_by_right">
                  <input type="radio" name="status" 
                         value="active"
                         <?php 
                         if(strcasecmp($user_status,"active")==0)
                         { 
                            echo " checked "; 
                         }
                         ?>
                         class="input_radio"/><?php echo $view_labels['status_active'];?>
               </div>
               <div class="enqueue_by_right">
                  <input type="radio" name="status" 
                         value="inactive" 
                         <?php 
                         if(strcasecmp($user_status,"inactive")==0)
                         { 
                            echo " checked "; 
                         }
                         ?>
                         class="input_radio"/><?php echo $view_labels['status_inactive'];?>
               </div>
            </td>
            
         </tr>
      </table>
      <br/>
   <?php echo form_submit('save_changes', $view_labels['btn_save_changes']); ?>
      
         <div class="button_blue">
            <a href="<?php echo site_url("auth/delete_user?user_id=".$user['user_id']."&redirect=".$_REQUEST['redirect'])?>">Delete</a>
         </div>
      
   <?php echo form_close(); ?>
</div>
