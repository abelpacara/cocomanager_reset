<table>
   <tr class="menu_projects">
      <?php      
      $array_menu[ $view_labels['menu_project']['home'] ] = site_url("/pm/home");      
      
      if(isset($project))
      {  
         $project['name']  = word_limiter($project['name'], 7);
         
         $array_menu[ $project['name'] ] = site_url("/pm/view_project?project_id=".$project['object_id']);
         $array_menu[ $view_labels['menu_project']['discussions'] ] = array(site_url("/pm/discussions/?project_id=".$project['object_id']),
                                                                            site_url("/pm/view_comment/?project_id=".$project['object_id']),
                                                                            site_url("/pm/save_comment/?project_id=".$project['object_id']),
                                                                            );
         
         $array_menu[ $view_labels['menu_project']['members'] ] = array(site_url("/pm/members/?project_id=".$project['object_id']),
                                                                        site_url("/pm/add_member/?project_id=".$project['object_id']),
                                                                        );
         $array_menu[ $view_labels['menu_project']['time_records'] ] = array(site_url("/pm/time_records/?project_id=".$project['object_id']),
                                                                             site_url("/pm/save_time_record/?project_id=".$project['object_id']));
         $array_menu[ $view_labels['menu_project']['trash'] ] = site_url("/pm/trash/?project_id=".$project['object_id']);
      }
      else
      {
         $array_menu[ $view_labels['menu_project']['trash'] ] = site_url("/pm/trash/");
      }
      
      foreach ( $array_menu as $key => $value )
      {
         $segment_view = $this->uri->segment(1)."/".$this->uri->segment(2);
         
         $style_select_menu ="";
         
            if((!is_array($value) AND stripos($value, $segment_view)!==false) OR
                (is_array($value) AND stripos_in_array($value, $segment_view) )
              )
            {
               $style_select_menu = " pm_menu_item_select ";
            }
            else
            {
               $style_select_menu = " pm_menu_item ";
            }
            ?>      
            <th class="<?php echo $style_select_menu?>">         
               <a href="<?php 
               if(is_array($value))
               {                  
                  echo $value[0];
               }
               else
               {
                  echo $value;
               }
               ?>">
                  <?php echo $key;?>
               </a>
            </th>
            <?php
      }
      ?>
   </tr>   
</table>
<table>
   <?php
   if(!empty($task_actived))
   {
      $url = site_url("pm/view_comment/?project_id=".$task_actived['project_id']."&comment_id=".$task_actived['parent_id']."#item_comment_".$task_actived['id_object']);
      ?>
      <tr>
         <td>
            <fieldset style="border: 1px solid #dcdcdc; padding: 5px; margin: 5px">
               <legend><?php echo $view_labels['menu_project']['task_in_process']?></legend>            
                  <a href="<?php echo $url?>">
                     <?php echo nl2br(decode_chars_special($task_actived['description']));?>
                  </a>
            </fieldset>
         </td>
      </tr>
      <?php
   }
   ?>
</table>
<?php
function stripos_in_array($array, $str_search)
{
   foreach ( $array as $key => $value )
   {
      if(stripos($value, $str_search)!==false)
      {
         return true;
      }
   }
}