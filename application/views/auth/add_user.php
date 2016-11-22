<?php
######################################
$username = array(
	'name'=>'username',
	'id'	=> 'username',
	'value' => set_value('username'),
	'maxlength'	=>50,
	'size'	=> 30,
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);

######################################
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
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
?>
<?php 
echo $my_messages;

//echo validation_errors();
?>
<p/>
<div>
   <h1><?php echo $view_labels['title_form']?></h1>
   <?php //echo form_open($this->uri->uri_string()); ?>
   <?php echo form_open_multipart($this->uri->uri_string()); ?>
      <table>
         
         <tr>
            <td><?php 
            //####################################################################
            echo form_label($view_labels['user_name'].':', $username['id']); ?></td>
            <td><?php echo form_input($username); ?></td>
            <td style="color: red;"><?php echo form_error($username['name']); ?></td>
         </tr>
         <tr>
            <td><?php echo form_label($view_labels['email'].':', $email['id']); ?></td>
            <td><?php echo form_input($email); ?></td>
            <td style="color: red;"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></td>
         </tr>
        
         <tr>
            <td><?php echo form_label($view_labels['password'].':', $password['id']); ?></td>
            <td><?php echo form_password($password); ?></td>
            <td style="color: red;"><?php echo form_error($password['name']); ?></td>
         </tr>
         <tr>
            <td>
               <?php echo $view_labels['picture']?> [max 3MB]:
            </td>
            <td>         
               <input type="file" name="photo" size="20"/>
            </td>
         </tr>
         <tr>
            <td><?php 
            //####################################################################
            echo form_label($view_labels['names'].':', $profile_name['id']); ?></td>
            <td><?php echo form_input($profile_name); ?></td>
            <td style="color: red;"><?php echo form_error($profile_name['name']); ?></td>
         </tr>
         <tr>
            <td><?php 
            //####################################################################
            echo form_label($view_labels['last_name'].':', $profile_last_name['id']); ?></td>
            <td><?php echo form_input($profile_last_name); ?></td>
            <td style="color: red;"><?php echo form_error($profile_last_name['name']); ?></td>
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
      <br/>
   <?php echo form_submit('add_user', $view_labels['btn_add']); ?>
   <?php echo form_close(); ?>
   
   <?php /*
   <h1>Lista Usuarios</h1>
   
   <table>
      <tr class="tr_table_columns">
         <td colspan="2">User Name</td>
         <td>Profile Name</td>
         <td>Last Name</td>
         <td>Email</td>         
      </tr>
      <?php
      for($i=0; $i<count($list_users); $i++)
      {
      ?>
         <tr class="tr_row_table">
            <td>
               <img src="<?php echo base_url($uri_images_users).'/'.$list_users[$i]['id'].'_thumb_small.jpg';?>"/>
               
            </td>
            <td>
               <?php echo $list_users[$i]['username'];?>
            </td>
            <td>
               <?php echo $list_users[$i]['name'];?>
            </td>
            <td><?php echo $list_users[$i]['last_name'];?></td>
            <td><?php echo $list_users[$i]['email'];?></td>
         </tr>
      <?php
      }?>
   </table>
   */?>
</div>
