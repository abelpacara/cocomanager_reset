<?php 
$max_upload = (int)(ini_get('upload_max_filesize'));
$max_post = (int)(ini_get('post_max_size'));
$memory_limit = (int)(ini_get('memory_limit'));
$upload_mb = min($max_upload, $max_post, $memory_limit);
?>
<script>
$(function() {
   $( "#start_date" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });
   $( "#end_date" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });
});
$(function(){
   $('.style_mask').customStyle();
});
</script>
<script>
$(document).ready(function(){
         $(".delete_file").click(function () {
            
            var id_deleter = $(this).attr('id');      
            
            $('#row_id_'+id_deleter).toggleClass("over_row_time");          
         });
      });

</script>
<?php
$styles_mask="class='style_mask' style='width:100px; white-space: nowrap;'";
?>
<?php 
echo form_open_multipart($this->uri->uri_string());
?>
<h1>
<?php
echo $view_labels['title_form'];   
?>
</h1>

<table>
   <tr>
      <td class="text_label_project">
         <?php echo $view_labels['title']?>
         <span class="pm_bullet_required">*</span>
      </td>
      <?php
      $description = isset($task['description'])?$task['description']:"";
      ?>
      <td colspan="4"><textarea name="description" rows="5" class="pm_text_field pm_width_text_field"><?php echo $description?></textarea></td>
   </tr>
  
   
   
   <tr>
      <td></td>
      <td>
         <table style="width:100%">
            <tr>               
               <td class="text_label_project">
                  <?php echo $view_labels['is_private'] ?>?:
                  <input type="checkbox" name="is_private" id="_is_private"  class="_displayer_members" value="ok"
                         
                         <?php
                         if(isset($task['is_private']))
                         {
                            echo "checked='yes'";
                         }
                         ?>
                         />
               </td>
            </tr>
         </table>
      </td>
   </tr>
   
   
   <tr>
      <td class="text_label_project">
         <?php echo $view_labels['assigned_to']?>
      </td>
      <td colspan="4">
          <?php
          echo get_display_selection_membership("", $list_membership, $uri_images_users, $user_id);
          ?>
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
   <?php
   if(isset($task['start_date']))
   {
      list($start_date, $start_time) = explode(" ", $task['start_date']);
   }
   else
   {
      list($start_date, $start_time) = explode(" ", $current_date_time);
   }
   ?>
   <tr>
      <td class="text_label_project"><?php echo $view_labels['start_date']?></td>
      <td>
         <input id="start_date" name="start_date" value="<?php echo $start_date?>"  type="text"  class="arrow_drowpdown" readonly=""/>
      
         <span class="text_label_project" style="padding-left: 20px;padding-right: 10px;"><?php echo $view_labels['hour']?></span>
         <?php                
         echo form_dropdown_time("start_", $start_time, $styles_mask);
         ?>
      </td>
   </tr>
   <?php
   if(isset($task['end_date']))
   {
      list($end_date, $end_time) = explode(" ", $task['end_date']);
   }
   else
   {
      $end_date = "";
      $end_time = "";
   }
   ?>
   <tr>
      <td class="text_label_project"><?php echo $view_labels['end_date']?></td>
      <td>
         <input id="end_date" name="end_date" type="text" value="<?php echo $end_date?>"  class="arrow_drowpdown" readonly=""/>
         <span class="text_label_project" style="padding-left: 20px;padding-right: 10px;"><?php echo $view_labels['hour']?></span>
         <?php echo form_dropdown_time("end_",$end_time, $styles_mask);?>
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
   <tr>
      <td class="text_label_project">      
      <?php echo $view_labels['points']?>
      </td>
      <?php
      $points = isset($task['points'])?$task['points']:"";
      ?>
      <td>
         <?php         
         if($has_privilege_edit_task_points)
         {
            ?>
            <input type="text" name="points" value="<?php echo $points?>" size="4"/>
            <?php
         }
         else
         {
            echo $points;
         }
         ?>
      </td>
   </tr>   
</table>










<?php
$quantity_files = 3;
?>   
<input type="hidden" id="quantity_files" name="quantity_files" value="<?php echo $quantity_files?>"/>

<script>
$(document).ready(function(){

   $('#add_file').click(function(){
         
         var quantity_files= $('#quantity_files').attr('value');
         quantity_files++;
         $('#quantity_files').attr('value', quantity_files);
         
         var str_append = "";
         str_append += "<tr>";      
         str_append += "<td style='white-space: nowrap'   class='text_label_project'><?php echo $view_labels['new_attachment_file']." "?>"+quantity_files+":</td>";
         str_append += "<td>";
         str_append += "<input type='file' name='file_"+quantity_files+"' class='pm_text_field' size='40'/>";
         str_append += "&nbsp;<?php echo $upload_mb?> MB max per file";
         str_append += "</td>";
         str_append += "</tr>";

         $('#entries').append(str_append);   
         
         
   });
});
</script>

<table id="entries">      
   
   <?php
   for($i=1;$i<=$quantity_files;$i++)
   {
      ?>      
      <tr>
         <td class="text_label_project"><?php echo $view_labels['new_attachment_file'] ?> <?php echo $i?>:</td>
         <td>
            <input type="file" name="file_<?php echo $i?>" class="pm_text_field" size="40"/>
            &nbsp;<?php echo $upload_mb?> MB max per file
         </td>
      </tr>
      <?php
   }   
   ?>
   
</table>

<table style="width: 100%">   
   <tr>
      <td>
         <input type="button" name="" id="add_file" value="+" style="float: right; cursor:pointer;"/>
      </td>
      <td style="width: 350px"></td>
         
   </tr>
</table>




<table style="width: 100%">   
   
   <tr>
      <td colspan="3">
         <table>
            <?php
            if(isset($task['id_object']))
            {
               for($i=0;$i<count($list_attachment_files);$i++)
               {
                  $url_file = site_url($this->config->item("uri_comment_files")."/".$task['id_object']."/".$list_attachment_files[$i]['name']);
                  ?>
                  <tr id="row_id_<?php echo $i?>">
                     <td class="text_label_project"><?php echo $view_labels['new_attachment_file'] ?> <?php echo ($i+1)?>:</td>
                     <td class="pm_container_file_uploaded pm_width_text_field"><a href="<?php echo $url_file?>"><?php echo $list_attachment_files[$i]['name']?></a>                  
                        &nbsp;&nbsp;
                        <div class="enqueue_by_left">
                        <input type="checkbox" class="delete_file" id="<?php echo $i?>" name="ids_delete_object[]" value="<?php echo $list_attachment_files[$i]['id_object']?>"/>
                        <?php echo $view_labels['move_to_trash'] ?>
                        </div>
                        <input type="hidden" name="names_delete_object[]" value="<?php echo $list_attachment_files[$i]['name']?>"/>
                     </td>
                  </tr>
                  <?php
               }
            }
            ?>
         </table>
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
         
         if( ! isset($task) )
         {?>
            <input type="hidden" name="add" value="ok"/>
         <?php
         }
         
         if(isset($parent_id))
         {
         ?>
            <input type="hidden" name="parent_id" value="<?php echo $parent_id?>"/>
         <?php
         }
         
         if(isset($redirect))
         {
            ?>
            <input type="hidden" name="redirect" value="<?php echo $redirect?>"/>
            <?php
         }
         ?>
            
            
         <input type="hidden" name="project_id" value="<?php echo $project_id?>"/>
         <input type="submit" name="save_go_to_list" value="<?php echo $view_labels['save_buttons']['save_go_back_list'] ?>"/>
         <input type="submit" name="delete" value="<?php echo $view_labels['save_buttons']['move_to_trash'] ?>"/>
         <input type="reset" name="cancel" value="<?php echo $view_labels['save_buttons']['cancel'] ?>"/>
      </td>
   </tr>
</table>


</form>