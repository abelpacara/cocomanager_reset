<?php 
echo form_open($this->uri->uri_string());
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
<?php
$styles_mask="class='style_mask' style='width:70px;'";
?>

<h1><?php echo $view_labels['title_form']?></h1>
<table class="table_form_project">
   <tr>
      <td class="text_label_project "><?php echo $view_labels['title']?>:</td>
      <?php
      $name = isset($project['name'])?$project['name']:"";
      ?>
      <td colspan="2"><input name="name" class="label_description" value="<?php echo $name?>" size="70"/></td>
   </tr>
   <tr>
      <td class="text_label_project"><?php echo $view_labels['start_date']?>:</td>
      <td>
         <?php
         if(isset($project['start_date']))
         {
            list($start_date, $start_time) = explode(" ", $project['start_date']);
         }
         else
         {
            list($start_date, $start_time) = explode(" ", $current_date_time);
         }
         ?>
         <input value="<?php echo $start_date?>" name="start_date" readonly="true" id="start_date"  class="arrow_drowpdown"/>
         <?php
         echo form_dropdown_time("start_", $start_time, $styles_mask);
         ?>         
      </td>     
   </tr>
   <tr>
      <td class="text_label_project"><?php echo $view_labels['end_date']?>:</td>
      <td>
         <?php
         if(isset($project['end_date']))
         {
            list($end_date, $end_time) = explode(" ", $project['end_date']);
         }
         else
         {
            $end_date = "";
            $end_time = "";
         }
         ?>
         <input value="<?php echo $end_date?>" name="end_date" readonly="true" id="end_date" class="arrow_drowpdown"/>
         <?php
         echo form_dropdown_time("end_", $end_time, $styles_mask);

         ?>
      </td>
   </tr>
   <tr>
      <td class="text_label_project"><?php echo $view_labels['priority']?>:</td>
      <td>
         <select name="priority_select_id" class="style_mask">
         <?php
         for($i=0;$i<count($list_priorities);$i++)
         {?>
            <option value="<?php echo $list_priorities[$i]['id']?>"
               <?php
               if(isset($project['priority_select_id']) AND strcasecmp($list_priorities[$i]['id'],$project['priority_select_id'])==0)
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
      <td class="text_label_project"><?php echo $view_labels['status']?>:</td>
      <td>
         <select name="status_select_id" class="style_mask">
         <?php         
         foreach($list_status AS $item)
         {?>
            <option value="<?php echo $item['id']?>"
               <?php
               if(isset($project['action_status_select_id']) AND strcasecmp($item['id'],$project['action_status_select_id'])==0)
               {
                  echo " SELECTED ";
               }
               ?>
            >
            <?php echo $view_labels['action_status'][$item['value_select']]?>
            </option>
         <?php
         }
         ?>
         </select>
      </td>
   </tr>

   <tr>
      <td class="text_label_project">
      <?php echo $view_labels['points']?>
      </td>
      <?php
      $points = isset($project['points'])?$project['points']:"";
      ?>
      <td>
         <?php         
         if($has_privilege_edit_project_points)
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
   
   <tr>
      <td class="text_label_project"><?php echo $view_labels['content']?>:</td>
      <?php
      $description = isset($project['description'])?$project['description']:"";
      ?>
      <td colspan="4">
         <textarea name="description" cols="62" rows="10" class="label_description"><?php echo $description?></textarea>
      </td>
   </tr>
   <tr>

      <td colspan="3">
         <?php
         if(isset($project['object_id']))
         {
         ?>
            <input type="hidden" name="project_id" value="<?php echo $project_id?>"/>
         <?php
         }
         if( ! isset($project))
         {?>
            <input type="hidden" name="add" value="ok"/>
         <?php
         }
         ?>         
         <input type="submit" name="save_go_to_list" value="<?php echo $view_labels['save_buttons']['save_go_back_list'] ?>"/>
         <input type="submit" name="delete" value="<?php echo $view_labels['save_buttons']['move_to_trash'] ?>"/>         
         <div class="button_blue">
            <?php
            //$url_cancel = site_url("pm/view_project?project_id=".$project_id);
            ?>
            <a href="javascript:window.history.go(-1)">
               <?php echo $view_labels['save_buttons']['cancel'] ?>
            </a>
         </div>
      </td>
   </tr>
</table>
</form>