<div>
   <h1><?php echo $view_labels['title_form']?></h1>
   
   <?php echo form_open($this->uri->uri_string()); ?>
      <table border="1" class="bg_white">         
         <tr class="tr_table_columns">
            <td></td>            
            <?php
            if(count($list_privileges)>0)
            {
               $array_modules=array();

               $index_priv=0;
               $index_module=0;

               //$parts_path=explode("/",$list_privileges[$index_priv]['module_name']);
               $array_modules[$index_module]['module_name'] = $list_privileges[$index_priv]['module_name'];//$parts_path[0];
               $array_modules[$index_module]['count_privileges']=1;
               
               $module_previous = $array_modules[$index_module]['module_name'];
               
               do
               {
                  $index_priv++;
                  if($index_priv<count($list_privileges))
                  {
                     $module_current = $list_privileges[$index_priv]['module_name'];
                     
                     if(strcasecmp($module_previous, $module_current)!=0)
                     {
                        $index_module++;
                        $array_modules[$index_module]['module_name'] = $module_current;
                        $array_modules[$index_module]['count_privileges'] = 1;
                     }
                     else
                     {
                        $array_modules[$index_module]['count_privileges'] = $array_modules[$index_module]['count_privileges']+1;
                     }
                     $module_previous = $module_current;
                  }
               }
               while($index_priv<count($list_privileges));
               
               for($i=0;$i<count($array_modules);$i++)
               {?>               
                  <td class="td_separator-column-table" colspan="<?php echo $array_modules[$i]['count_privileges']?>">
                     <?php echo $view_labels['modules'][$array_modules[$i]['module_name']];?>
                  </td>
               <?php
               }?>
            <?php
            }?>
         </tr>
         
         <tr class="tr_table_columns">
           
         </tr>
         
         <?php
         for($i=0;$i<count($list_roles);$i++)
         {?>
            <tr class="tr_row_table">
               <td class="tr_table_columns"><?php echo $list_roles[$i]['name'];?></td>            
               <?php
               for($j=0;$j<count($list_privileges);$j++)
               {
                  $is_assigned=FALSE;
                  for($k=0; $k<count($list_roles_privileges_actived); $k++)
                  {
                     if(strcasecmp($list_roles[$i]['id'], $list_roles_privileges_actived[$k]['role_id'])==0 AND
                        strcasecmp($list_privileges[$j]['id'], $list_roles_privileges_actived[$k]['privilege_id'])==0)
                     {
                        $is_assigned = TRUE;
                        break;
                     }
                  }
                  ?>
                  <td><?php echo form_checkbox('role_privilege_ids_'.$list_roles[$i]['id'].'_'.$list_privileges[$j]['id'], 
                                 'x',$is_assigned);?></td>
               <?php
               }?>
            </tr>
         <?php
         }?>
      </table>
   <?php echo form_submit('save', $view_labels['btn_changes']); ?>
   <?php echo form_close(); ?>
</div>
