<div>
   <?php 
   echo form_open($this->uri->uri_string()); 
   ?>
   <h1><?php echo $view_labels['title_form']?></h1>   
      <table border="1" class="bg_white">         
         <tr class="tr_table_columns">
            <td></td>            
            <?php
            for($i=0;$i<count($list_roles);$i++)
            {?>
            <td class="td_separator-column-table"><?php echo $list_roles[$i]['name'];?></td>
            <?php
            }?>
         </tr>
         <?php
         for($i=0;$i<count($list_users);$i++)
         {
            ?>
            <tr class="tr_row_table">
               
               <td class="tr_table_columns"><?php echo $list_users[$i]['username'];?></td>            
               <?php
               for($j=0;$j<count($list_roles);$j++)
               {
                  $is_assigned=FALSE;
                  for($k=0; $k<count($list_users_roles_activated); $k++)
                  {
                     if(strcasecmp($list_users[$i]['id'], $list_users_roles_activated[$k]['user_id'])==0 AND
                        strcasecmp($list_roles[$j]['id'], $list_users_roles_activated[$k]['role_id'])==0)
                     {
                        $is_assigned = TRUE;
                        break;
                     }
                  }
                  ?>
                  <td>
                     <input type="radio" 
                           value="<?php echo $list_users[$i]['id'].'|'.$list_roles[$j]['id']?>" 
                           name="<?php echo 'user_role_id_'.$list_users[$i]['id']?>"
                           <?php
                           if($is_assigned)
                           {
                              echo ' checked="checked"';
                           }
                           
                           if(strcasecmp($user_id_current,$list_users[$i]['id'])==0)
                           {
                              echo ' disabled="disabled"';
                           }
                           ?>/>
                 
                  </td>
               <?php
               }?>
            </tr>
         <?php
         }?>
      </table>
   <?php echo form_submit('save', $view_labels['btn_save_changes']); ?>
   <?php echo form_close(); ?>
</div>
