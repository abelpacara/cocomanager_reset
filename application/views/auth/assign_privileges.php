<div>
   <h1>
      <?php echo $view_labels['title_form']?></h1>   
      <?php echo form_open($this->uri->uri_string()); ?>
      <fieldset>
         <legend>
            
         </legend>
      </fieldset>      
      <?php

      for($i=0; $i<count($list_modules); $i++)
      {?>
         <h1><?php echo $list_modules[$i]['name']?></h1>
         <table class="bg_white" border="1">                     
            <tr class="tr_table_columns">
               <td></td>
               <?php 
               for($l=0; $l<count($list_privileges); $l++)
               {
                  if(strcasecmp($list_modules[$i]['id'], $list_privileges[$l]['module_id']) ==0)
                  {?>
                     <td class="td_separator-column-table"><?php echo $list_privileges[$l]['name']?></td>
                     <?php
                  }
               }
               ?>
            </tr>
         <?php
         for($j=0; $j<count($list_roles); $j++)
         {?>
            <tr class="tr_row_table">
               <td class="tr_table_columns"><?php echo $list_roles[$j]['name']?></td>
               <?php
               for($k=0; $k<count($list_privileges); $k++)
               {
                  $is_assigned = false;
                  if(strcasecmp($list_modules[$i]['id'], $list_privileges[$k]['module_id']) ==0)
                  {
                     for($m=0; $m<count($list_roles_privileges_actived); $m++)
                     {

                        if(strcasecmp($list_modules[$i]['id'], $list_roles_privileges_actived[$m]['module_id']) ==0 AND
                           strcasecmp($list_roles[$j]['id'], $list_roles_privileges_actived[$m]['role_id']) ==0 AND
                           strcasecmp($list_privileges[$k]['id'], $list_roles_privileges_actived[$m]['privilege_id']) ==0)
                        {
                           $is_assigned = true;
                           break;
                        }
                     }
                     ?>
                     <td>
                        <input type="checkbox"
                               name="role_privilege_ids_<?php echo $list_roles[$j]['id'].'_'.$list_privileges[$k]['id'];?>"                               
                        <?php 
                        if($is_assigned)
                        {
                           echo " checked ='yes' ";
                        }
                        ?>
                        />
                     </td> 
                     <?php
                  }
               }?>
               </tr>
            <?php
         }
         ?>
         </table>
         <br/>
         <br/>
         <?php
      }
      ?>
            
         
   <?php echo form_submit('save', $view_labels['btn_changes']); ?>
   <?php echo form_close(); ?>
</div>
