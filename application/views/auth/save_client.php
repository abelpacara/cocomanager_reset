<?php
/*
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);*/
?>
<?php 
echo $my_messages;

//echo validation_errors();
?>
<script>
$(document).ready(function(){
   $('#list_client_companies').change(function(){      
     var value_select = $(this).val();
     
      var company_id = value_select.split('|');     
     $('#client_company').attr('value',company_id[1]);
   });
});
</script>
<p/>
<div>
   <?php
   if($has_privilege_manager_clients)
   {?>
      <span class="pm_link_button enqueue_by_left">
         <a href="<?php echo site_url("auth/manager_clients?client_company_id=".$client_company['id_client_company'])?>"><?php echo $view_labels['link_manager_clients']?>
         </a>
      </span>
   <?php
   }?>
   <h1><?php echo $view_labels['title_form']?></h1>
   <?php //echo form_open($this->uri->uri_string()); ?>
   <?php echo form_open_multipart($this->uri->uri_string()); ?>
   
      <table>
         <tr>
            <td colspan="2">
             <?php 
               $uri_image = ".".$array_uri_logo['uri']."/".$client_company['id_client_company']."_login-logo.jpg";               
               $url_image ="";
               $style = "";
               
               $url = site_url("auth/save_client_company?client_company_id=".$client_company['id_client_company']);
               
               if(file_exists($uri_image))
               {
                  $url_image = site_url($uri_image);
               }
               else
               {
                  $url_image = site_url(".".$array_uri_logo['uri']."/default_login-logo.jpg");
                  $style = "style='opacity:0.1'";
               }
               ?>
               <a href="<?php echo $url?>">
                  <img src="<?php echo $url_image?>" <?php echo $style?>/>
               </a>            
            </td>
         </tr>
         <tr>
            <td><?php echo $view_labels['client_company']?>:</td><td><?php echo $client_company['name']?></td>
         </tr>
         <tr>
            <td><?php echo $view_labels['client_company_description']?>:</td><td><?php echo $client_company['description']?></td>
         </tr>
      </table>
   
      <br/>
      <br/>
      <br/>
      
      <table>         
         <tr>
            <td>
            <?php 
            //####################################################################
            echo $view_labels['user_name'].':'; ?>
               <?php
               if(!empty($client))
               {
               ?>
                  <input type="hidden" value="ok" name="is_edit" />
               <?php
               }?>
            </td>
            <?php
            $username = isset($client['username'])?$client['username']:"";
            ?>
            <td><input type="text" name="username" value="<?php echo $username?>"/></td>            
         </tr>
         <tr>
            <td><?php echo $view_labels['email'].':'; ?></td>
            <?php
            $email = isset($client['email'])?$client['email']:"";
            ?>
            <td><input type="text" name="email" value="<?php echo $email?>"/></td>
         </tr>
        
         <tr>
            <td><?php echo $view_labels['password'].':'; ?></td>
            
            <td><input type="password" name="password" /></td>
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
            
            if(isset($client_company['id_client_company']))
            {?>
               <input type="hidden" name="client_company_id" value="<?php echo $client_company['id_client_company']?>"/>
            <?php
            }
            //####################################################################
            echo $view_labels['names'].':';             
            $name = isset($client['name'])?$client['name']:"";            
            ?></td>
            
            <td><input type="text" name="profile_name" value="<?php echo $name?>"/></td>
         </tr>
         <tr>
            <td><?php 
            //####################################################################
            echo $view_labels['last_name'].':'; ?>
            </td>
            <td>
            <?php 
            $last_name = isset($client['last_name'])?$client['last_name']:"";            
            ?>
               <input type="text" name="profile_last_name" value="<?php echo $last_name?>"/>
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
                          if(isset($client['language']) AND strcasecmp( $client['language'], $languages[$i]) == 0)
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
         <input type="hidden" name="user_id_edit" value="<?php echo $user_id_edit?>" />
      </table>
      <br/>
   <?php echo form_submit('save_client', $view_labels['btn_save']); ?>
   <?php
if($has_privilege_delete_client)
{
   if(isset($client['username']))
   {
      $url_delete = base_url("auth/delete_client?client_user_id=".$client['user_id']."&client_company_id=".$client_company['id_client_company']);
      ?>      
      <div class="button_blue">
         <a href="<?php echo $url_delete?>">
            DELETE
         </a>
      </div>
      <?php 
   }
}
echo form_close(); 
?>
</div>
