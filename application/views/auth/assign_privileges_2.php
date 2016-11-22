<div>
   <h1>
      <?php echo $view_labels['title_form']?></h1>   
      <?php 
      echo form_open($this->uri->uri_string());       
      ?>
      <fieldset>
         <legend>
            
         </legend>
      </fieldset>      
         <?php
         /*
         echo "<pre>";
         print_r($list_privileges);         
         echo "</pre>";
         */
         for($i=0;$i<count($list_modules);$i++)
         {?>
            <br/>
            <h1><?php echo $list_modules[$i]['name']?></h1>
            <table class="bg_white">
                  <?php
                  for($j=0; $j<count($list_roles);$j++)
                  {?>
                     <tr class="tr_row_table">
                        <td class="tr_table_columns">
                           <?php echo $list_roles[$j]['name'];?>
                        </td>
                        <?php
                        for($k=0;$k<count($list_privileges);$k++)
                        {
                           if((strcasecmp($list_modules[$i]['id'], $list_privileges[$k]['module_id'])==0) AND
                              (strcasecmp($list_roles[$j]['id'], $list_privileges[$k]['role_id'])==0))
                           {?>
                           <td>
                              <?php echo form_checkbox('role_privilege_ids_'.$list_roles[$j]['id']."_", 'x', $is_assigned=null);?>
                           </td>
                           <?php
                           }
                        }
                        ?>
                     </tr>
                     <?php
                  }
                  ?>
            </table>
            <?php
         }
         ?>
   <?php echo form_submit('save', $view_labels['btn_changes']); ?>
   <?php echo form_close(); ?>
</div>
