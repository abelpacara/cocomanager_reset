<script>
$(function() {
   $( "#start_date" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });
   $( "#end_date" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });
});
$(function(){
   $('.style_mask').customStyle();
});
</script>
<?php
$styles_mask="class='style_mask' style='width:70px;white-space: nowrap;'";
?>
<?php 
echo form_open_multipart($this->uri->uri_string());
?>
<h1>
<?php
echo $view_labels['title_form'];   
?>
</h1>

<table id="entries">
   <tr>
      <td class="text_label_project">
         <?php echo $view_labels['title']?>
         <span class="pm_bullet_required">*</span>
      </td>
      <?php
      $title = isset($task['name'])?$task['name']:"";
      ?>
      <td colspan="3"><?php echo $title?></td>
   </tr>
  
   <tr>
      <td class="text_label_project">
         <?php echo $view_labels['assigned_to']?>
      </td>
         <td colspan="4">
         <?php
         for($i=0; $i<count($list_members); $i++)
         {?>
            <div class="enqueue_by_right" style="padding:10px;">
               <table>
                  <tr>
                     <td>
                        <?php
                       $url_picture = '.'.$uri_images_users.'/'.$list_members[$i]['user_id'].'_thumb_small.jpg';
                       
                       if( ! file_exists($url_picture))
                       {
                          $url_picture = '.'.$uri_images_users.'/default.jpg';
                       }
                       ?>
                       <a href="<?php echo site_url('/auth/edit_profile');?>">
                        <img src="<?php echo site_url($url_picture);?>"/>
                       </a>
                     </td>                     
                     <td>
                        <table>
                           <tr>
                              <td  style="font-size: 12px !important; padding: 1px !important; padding-left: 10px !important;">
                                 <?php 
                                 $member_name ="";
                                 if(isset($list_members[$i]['name']))
                                 {
                                    $member_name = $list_members[$i]['name']." ".$list_members[$i]['last_name'];
                                 }
                                 else
                                 {
                                    $member_name = $list_members[$i]['email'];
                                 }
                                 echo $member_name;
                                 ?> 
                              </td>
                           </tr>
                           <tr>
                              <td  class="list_pm_item_sub_text2"  style="font-size: 9px !important;  padding: 1px !important; padding-left: 10px !important;"><?php echo $list_members[$i]['role']?></td>
                           </tr>
                           
                        </table>
                     </td>
                  </tr>
               </table>
            </div>
         <?php
         }?>
      </td>
   </tr>
   <?php
   list($current_date, $current_time) = explode(" ",$current_date_time);
   ?>
   
   <tr>
      <td class="text_label_project"><?php echo $view_labels['status']?></td>
      <td>
         <select name="status_select_id" class="style_mask">
         <?php         
         foreach($list_status AS $item)
         {?>
            <option value="<?php echo $item['id']?>"
               <?php
               if(isset($task['action_status_select_id']) AND strcasecmp($item['id'],$task['action_status_select_id'])==0)
               {
                  echo " SELECTED ";
               }
               ?>
            >
            <?php echo $view_labels['object_status'][$item['value_select']]?>
            </option>
         <?php
         }
         ?>
         </select>
         <span class="text_label_project" style="padding-left: 20px;padding-right: 10px;"><?php echo $view_labels['priority']?></span>
         <select name="priority_select_id" class="style_mask">
         <?php
         for($i=0;$i<count($list_priorities);$i++)
         {?>
            <option value="<?php echo $list_priorities[$i]['id']?>"
               <?php
               if(isset($task['priority_select_id']) AND strcasecmp($list_priorities[$i]['id'],$task['priority_select_id'])==0)
               {
                  echo " SELECTED ";
               }
               ?>
            >
            <?php echo $list_priorities[$i]['value_select']?>
            </option>
         <?php
         }
         ?>
         </select>
      </td>
   </tr>
   
   <tr>
      <td class="text_label_project"><?php echo $view_labels['start_date']?></td>
      <td>
         <?php echo $task['start_date'];
         ?>
      </td>
   </tr>   
   <tr>
      <td class="text_label_project"><?php echo $view_labels['end_date']?></td>
      <td>         
         <?php echo $task['end_date'];?>
      </td>
   </tr>
   <tr>
      <td class="text_label_project"><?php echo $view_labels['percent_completed']?></td>
      <td>         
         <?php
         $percent_completed = isset($task['percent_completed'])? $task['percent_completed']: 0;
         
         $array_percents = array();
         for($i=0;$i<=100; $i++)
         {
            $array_percents[$i]= $i;
         }
         echo form_dropdown("percent_completed", $array_percents, $percent_completed, $styles_mask);         
         ?>
         %
      </td>
   </tr>
</table>




<table style="width: 100%">
   <tr>
      <td colspan="3">
         <?php
         if(isset($task['id_object']))
         {
         ?>
            <input type="hidden" name="task_id" value="<?php echo $task['id_object']?>"/>
         <?php
         }
         
        
         
         if(isset($parent_id))
         {
         ?>
            <input type="hidden" name="parent_id" value="<?php echo $parent_id?>"/>
         <?php
         }
         ?>
         <input type="hidden" name="project_id" value="<?php echo $project_id?>"/>
         <input type="submit" name="save_go_to_list" value="<?php echo $view_labels['save_buttons']['save_go_back_list'] ?>"/>         
         <input type="reset" name="cancel" value="<?php echo $view_labels['save_buttons']['cancel'] ?>"/>
      </td>
   </tr>
</table>


</form>