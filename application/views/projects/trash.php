<?php 
echo form_open($this->uri->uri_string());
?>


<h1 class="enqueue_by_right">
   <?php echo $view_labels['title_form'];?>
</h1>
<!--
<span class="pm_link_button_blue enqueue_by_left">
   <a href="http://localhost/cocomanager/pm/save_project">
      Restaurar Todo
   </a>
</span> 

<span class="pm_link_button enqueue_by_left">
   <a href="http://localhost/cocomanager/pm/save_project">
      Borrar Todo
   </a>
</span>
-->
<br/>
<table width="100%">      
   <?php
   foreach($list_trash_objects AS $object_trash)
   {
   ?>
   <tr class="hr_top tr_row_table">   
      <td>
         <h3 class="font_bold">
         <?php 
         if(isset($object_trash['name']))
         {
            echo nl2br( word_limiter( decode_chars_special(  $object_trash['name']), 35 ));
         }
         else
         {
            echo nl2br( word_limiter( decode_chars_special(  $object_trash['description']), 35 ));
         }
         ?>
         </h3>
                 
      <br/> 
      <b class="list_pm_item_sub_text2"><?php echo $view_labels['type_object'][$object_trash['type']]?>
      |
      <?php echo $view_labels['created_by'];?>: 
            <?php                         
            $owner_show_name ="";

            if(isset($object_trash['owner_name']))
            {
               $owner_show_name = $object_trash['owner_name']." ".$object_trash['owner_last_name'];
            }
            else
            {
               $owner_show_name = $object_trash['owner_email'];
            }         
            $url_profile_user = site_url("/auth/public_profile?user_id=".$object_trash['user_id']);
            ?>
               <a href="<?php echo $url_profile_user?>">
                  <?php echo $owner_show_name;?>
               </a>
            |
            &nbsp; <?php echo $view_labels['in'];?>: <b class="list_pm_item_sub_text2"><?php echo $object_trash['project_owner']?></b>
            |
            &nbsp; <b class="list_pm_item_sub_text2">
               <?php
               echo call_user_func('get_date_literal_'.$this->config->item('language'), $object_trash['register_date']);
               ?>
            </b>
      </b>
      </td>
      <td class="font_italic"><?php echo $view_labels['type_object'][$object_trash['type']]?></td>
      <td>
         <?php         
         $uri_project_id ="";
         if(isset($_REQUEST['project_id']) AND strcasecmp($_REQUEST['project_id'],"")!=0)
         {
            $uri_project_id ="&project_id=".$_REQUEST['project_id'];
         }
         
         if($has_privilege_delete_permanently)
         {
         ?>
         <a href="<?php echo base_url()."pm/delete_permanently?object_id=".$object_trash['id_object'].$uri_project_id ?>">
            <?php echo $view_labels['delete_permanently'];?>
         </a>
         <?php
         }
         ?>
      </td>
      <td>
         <?php
         if($has_privilege_restore)
         {?>
            <a href="<?php echo base_url()."/pm/restore?object_id=".$object_trash['id_object'].$uri_project_id ?>">
               <?php echo $view_labels['restore'];?></a>
         <?php
         }
         ?>
      </td>
   </tr>
   <?php
   }
   ?>      
</table>
</form>